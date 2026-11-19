<?php

namespace App\Mcp\Tools;

use App\Cms\Cms;
use App\Cms\Seo;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Html;
use App\Mcp\Support\SiteIndex;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;

class SeoTools extends ToolSet
{
    private const ROBOTS = ['index, follow', 'noindex, follow', 'noindex, nofollow', 'index, nofollow',
        'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'];

    public function tools(): array
    {
        $fields = [
            'title' => self::s('Meta title (aim for 50-60 characters)'),
            'description' => self::s('Meta description (aim for 140-160 characters)'),
            'focus_keyword' => self::s('Focus keyword (reference only, not output)'),
            'canonical' => self::s('Canonical URL or path; empty = the page itself'),
            'robots' => self::e('Robots meta (SITE_NOINDEX overrides it while on)', self::ROBOTS),
            'og_title' => self::s('Social share title (default: meta title)'),
            'og_description' => self::s('Social share description (default: meta description)'),
            'og_image' => self::s('Social share image path or https URL (default: logo)'),
            'og_type' => self::e('og:type', ['website', 'article', 'profile']),
            'twitter_card' => self::e('Twitter card', ['summary', 'summary_large_image']),
            'exclude_from_sitemap' => self::b('Leave out of sitemap.xml'),
        ];

        return [
            $this->read('get_seo', 'What a page or post shows search engines now: stored SEO fields and the effective title, description, canonical, robots and OG image.', self::ownerProps(), [], fn ($a) => self::seoOut($this->findOwner($a))),

            $this->write('update_seo', 'Change SEO fields of a page or post. Only the fields you send change; send "" to clear one.', self::ownerProps() + $fields + [
                'expected_updated_at' => self::s('seo.updated_at from get_seo (null if the page has no SEO row yet)'),
            ], [], fn ($a) => $this->update($a), true),

            $this->write('bulk_update_seo', 'Change SEO fields of several pages/posts at once. dry_run defaults to true and returns before/after for each.', [
                'items' => self::a('[{page_id|post_id|path, expected_updated_at, title?, description?, ...}]', ['type' => 'object']),
                'dry_run' => self::dryRun(),
            ], ['items'], fn ($a) => $this->bulk($a)),

            $this->read('audit_meta', 'Titles (50-60 chars) and descriptions (140-160): missing, too short/long, duplicates; missing OG image; canonical pointing elsewhere; noindex pages in the sitemap.', [
                'page_type' => self::e('Only this type (or "post")', array_merge(Page::TYPES, ['post'])),
            ], [], fn ($a) => $this->auditMeta($a)),

            $this->read('audit_seo', 'Site-wide SEO check per page: meta, H1, headings, image alt, schema, internal links in/out, thin content. Worst pages first.', [
                'page_type' => self::e('Only this type (or "post")', array_merge(Page::TYPES, ['post'])),
                'limit' => self::i('Pages to list (default 40)'),
            ], [], fn ($a) => $this->auditSeo($a)),

            $this->read('audit_cannibalisation', 'Pages that compete for the same search: shared focus keyword, near-identical titles/H1s, or high text similarity. Grouped, with a suggested primary page. Read-only.', [
                'min_similarity' => ['type' => 'number', 'description' => 'Text similarity threshold 0-1 (default 0.35)'],
                'page_type' => self::e('Only this type', array_merge(Page::TYPES, ['post'])),
            ], [], fn ($a) => $this->cannibalisation($a)),

            $this->read('audit_thin_content', 'Unique word count per page (own content only: no header, footer or shared components) and % of its text that also appears on other pages. Read-only.', [
                'page_type' => self::e('Only this type (or "post")', array_merge(Page::TYPES, ['post'])),
                'max_words' => self::i('Only pages with fewer own words than this'),
            ], [], fn ($a) => $this->thin($a)),

            $this->read('seo_report', 'Everything about one page: outline, word counts, meta, schema types, internal links in/out, image issues, first paragraph. Read-only.', self::ownerProps(), [], fn ($a) => $this->report($this->findOwner($a))),
        ];
    }

    public static function seoOut(Model $owner): array
    {
        $m = $owner->seo()->first();
        $eff = Seo::for($owner, $owner instanceof Post ? $owner->title : $owner->name);
        $site = rtrim((string) config('app.url'), '/');

        return [
            'url' => $owner->path,
            'stored' => $m ? collect(Seo::FIELDS)->mapWithKeys(fn ($f) => [$f => $m->{$f}])->all() : null,
            'updated_at' => Guard::stamp($m),
            'effective' => [
                'title' => $eff['title'],
                'title_length' => mb_strlen($eff['title']),
                'description' => $eff['description'],
                'description_length' => mb_strlen((string) $eff['description']),
                'canonical' => $eff['canonical'] ?: $site.$owner->path,
                'robots' => Cms::noindex() ? 'noindex, nofollow (SITE_NOINDEX is on)' : ($eff['robots'] ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'),
                'og_image' => $eff['og_image'] ?: config('site.logo').' (default logo)',
                'in_sitemap' => ! \App\Cms\Sitemap::excluded($m) && ($owner instanceof Post ? $owner->status !== 'draft' : $owner->status === 'published'),
            ],
        ];
    }

    private function clean(array $a): array
    {
        $fields = [];
        foreach (Seo::FIELDS as $f) {
            if (! array_key_exists($f, $a)) {
                continue;
            }
            $v = $a[$f];
            if ($f === 'exclude_from_sitemap') {
                $fields[$f] = (bool) $v;

                continue;
            }
            $v = $v === null ? null : trim(strip_tags((string) $v));
            $fields[$f] = $v === '' ? null : $v;
        }
        if (! empty($fields['canonical']) && ! str_starts_with($fields['canonical'], '/') && ! str_starts_with($fields['canonical'], 'https://')) {
            throw new ToolError('canonical must be a path (/page/) or an https:// URL.');
        }
        if (! empty($fields['og_image']) && ! str_starts_with($fields['og_image'], '/') && ! str_starts_with($fields['og_image'], 'https://')) {
            throw new ToolError('og_image must be a path (/images/...) or an https:// URL.');
        }
        if (! empty($fields['og_image']) && str_starts_with($fields['og_image'], '/') && ! is_file(public_path(ltrim($fields['og_image'], '/')))) {
            throw new ToolError('og_image file not found: '.$fields['og_image']);
        }

        return $fields;
    }

    private function warnings(array $fields): array
    {
        $w = [];
        if (isset($fields['title']) && (mb_strlen($fields['title']) < 30 || mb_strlen($fields['title']) > 65)) {
            $w[] = 'Title is '.mb_strlen($fields['title']).' characters (aim for 50-60).';
        }
        if (isset($fields['description']) && (mb_strlen($fields['description']) < 120 || mb_strlen($fields['description']) > 165)) {
            $w[] = 'Description is '.mb_strlen($fields['description']).' characters (aim for 140-160).';
        }
        if (Cms::noindex() && isset($fields['robots'])) {
            $w[] = 'SITE_NOINDEX is on: the robots value is saved but every page says noindex until launch.';
        }

        return $w;
    }

    private function apply(Model $owner, array $a, bool $dry): array
    {
        $m = $owner->seo()->first();
        if ($m) {
            Guard::check($m, $a['expected_updated_at'] ?? null);
        }
        $fields = $this->clean($a);
        if (! $fields) {
            throw new ToolError('No SEO fields to change.');
        }
        $before = $m ? collect($m->only(array_keys($fields)))->all() : array_fill_keys(array_keys($fields), null);
        $after = array_merge($before, $fields);
        if (! $dry) {
            $m ??= $owner->seo()->make();
            $m->fill($fields)->save();
            if ($owner instanceof Page || $owner instanceof Post) {
                $owner->touch();
            }
            Activity::log('update', $m, $before, $after, 'SEO of '.$owner->path, [$owner->path]);
        }

        return ['url' => $owner->path, 'before' => $before, 'after' => $after, 'warnings' => $this->warnings($fields)];
    }

    private function update(array $a): array
    {
        $owner = $this->findOwner($a);
        $result = $this->apply($owner, $a, false);

        return $result + ['seo' => self::seoOut($owner->fresh())];
    }

    private function bulk(array $a): array
    {
        $dry = $a['dry_run'] ?? true;
        $out = [];
        foreach ($a['items'] as $i => $item) {
            try {
                $out[] = $this->apply($this->findOwner($item), $item, $dry);
            } catch (ToolError $e) {
                $out[] = ['item' => $i, 'error' => $e->getMessage()] + $e->details;
            }
        }

        return ['dry_run' => $dry, 'results' => $out, 'next' => $dry ? 'Run again with dry_run=false to apply.' : null];
    }

    private function entries(?string $type): array
    {
        return array_filter(SiteIndex::all(), fn ($e) => ! $type || $e['page_type'] === $type);
    }

    private function auditMeta(array $a): array
    {
        $entries = $this->entries($a['page_type'] ?? null);
        $titles = [];
        $descs = [];
        $issues = [];
        foreach ($entries as $path => $e) {
            $p = [];
            $tl = mb_strlen($e['title']);
            $dl = mb_strlen($e['description']);
            if ($tl === 0) {
                $p[] = 'title missing';
            } elseif ($tl < 50 || $tl > 60) {
                $p[] = "title $tl chars";
            }
            if ($dl === 0) {
                $p[] = 'description missing';
            } elseif ($dl < 140 || $dl > 160) {
                $p[] = "description $dl chars";
            }
            if ($e['og_image_is_default']) {
                $p[] = 'no OG image (logo used)';
            }
            $own = rtrim((string) config('app.url'), '/').$path;
            if ($e['canonical'] && ! str_ends_with($e['canonical'], $path)) {
                $p[] = 'canonical points elsewhere: '.$e['canonical'];
            }
            $titles[mb_strtolower($e['title'])][] = $path;
            if ($e['description'] !== '') {
                $descs[mb_strtolower($e['description'])][] = $path;
            }
            if ($p) {
                $issues[] = ['url' => $path, 'title' => $e['title'], 'issues' => $p];
            }
        }

        return [
            'pages_checked' => count($entries),
            'with_issues' => count($issues),
            'duplicate_titles' => array_values(array_filter(array_map(fn ($v, $k) => count($v) > 1 ? ['title' => $k, 'urls' => $v] : null, $titles, array_keys($titles)))),
            'duplicate_descriptions' => array_values(array_filter(array_map(fn ($v, $k) => count($v) > 1 ? ['description' => $k, 'urls' => $v] : null, $descs, array_keys($descs)))),
            'site_noindex' => Cms::noindex(),
            'issues' => $issues,
        ];
    }

    private function auditSeo(array $a): array
    {
        $entries = $this->entries($a['page_type'] ?? null);
        $inbound = SiteIndex::inbound();
        $rows = [];
        $totals = [];
        foreach ($entries as $path => $e) {
            $p = [];
            $h1s = count(array_filter($e['headings'], fn ($h) => $h['level'] === 1));
            if ($h1s !== 1) {
                $p[] = "$h1s H1";
            }
            $prev = 1;
            foreach ($e['headings'] as $h) {
                if ($h['level'] > $prev + 1) {
                    $p[] = 'skipped heading level (h'.$prev.' → h'.$h['level'].')';
                    break;
                }
                $prev = $h['level'];
            }
            $tl = mb_strlen($e['title']);
            if ($tl < 50 || $tl > 60) {
                $p[] = "title $tl chars";
            }
            $dl = mb_strlen($e['description']);
            if ($dl < 140 || $dl > 160) {
                $p[] = $dl ? "description $dl chars" : 'no description';
            }
            preg_match_all('#<img\s[^>]*>#i', $e['main'], $imgs);
            $noAlt = count(array_filter($imgs[0], fn ($i) => ! preg_match('/alt="[^"]+"/', $i)));
            if ($noAlt) {
                $p[] = "$noAlt images without alt";
            }
            $in = count($inbound[rtrim(strtolower($path), '/').'/'] ?? []);
            if ($in === 0 && $path !== '/') {
                $p[] = 'no internal links point here (orphan)';
            }
            if ($e['own_words'] < 300) {
                $p[] = $e['own_words'].' own words (thin)';
            }
            if (! in_array('BreadcrumbList', $e['schema_types'], true) && $path !== '/') {
                $p[] = 'no BreadcrumbList schema';
            }
            foreach ($p as $issue) {
                $totals[preg_replace('/^\d+ |\d+ chars|\(h\d → h\d\)/', '', $issue)] = ($totals[preg_replace('/^\d+ |\d+ chars|\(h\d → h\d\)/', '', $issue)] ?? 0) + 1;
            }
            $rows[] = ['url' => $path, 'type' => $e['page_type'], 'score' => max(0, 100 - 12 * count($p)), 'issues' => $p,
                'links_in' => $in, 'links_out' => count(array_filter($e['links'], fn ($l) => $l['internal'])), 'own_words' => $e['own_words'], 'schema' => $e['schema_types']];
        }
        usort($rows, fn ($x, $y) => $x['score'] <=> $y['score']);
        arsort($totals);

        return [
            'site_noindex' => Cms::noindex() ? 'ON: nothing is indexed until launch' : 'off',
            'pages_checked' => count($rows),
            'average_score' => $rows ? round(array_sum(array_column($rows, 'score')) / count($rows)) : null,
            'issue_counts' => $totals,
            'pages' => array_slice($rows, 0, (int) ($a['limit'] ?? 40)),
        ];
    }

    private function cannibalisation(array $a): array
    {
        $min = (float) ($a['min_similarity'] ?? 0.35);
        $entries = array_values($this->entries($a['page_type'] ?? null));
        $inbound = SiteIndex::inbound();
        $sh = [];
        foreach ($entries as $i => $e) {
            $sh[$i] = SiteIndex::shingles($e['own_text']);
        }
        $norm = fn ($s) => trim(preg_replace('/\b(uae|dubai|abu dhabi|sharjah|in|the|for|and|&|multitech|pos|software|system|systems|\|)\b/u', ' ', mb_strtolower((string) $s)));
        $pairs = [];
        for ($i = 0, $n = count($entries); $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $reasons = [];
                $ei = $entries[$i];
                $ej = $entries[$j];
                if ($ei['focus_keyword'] && mb_strtolower($ei['focus_keyword']) === mb_strtolower((string) $ej['focus_keyword'])) {
                    $reasons[] = 'same focus keyword: '.$ei['focus_keyword'];
                }
                similar_text($norm($ei['title']), $norm($ej['title']), $tp);
                if ($tp >= 85 && $norm($ei['title']) !== '') {
                    $reasons[] = 'near-identical titles ('.round($tp).'%)';
                }
                similar_text($norm($ei['h1']), $norm($ej['h1']), $hp);
                if ($hp >= 85 && $norm($ei['h1']) !== '') {
                    $reasons[] = 'near-identical H1s ('.round($hp).'%)';
                }
                $inter = count(array_intersect_key($sh[$i], $sh[$j]));
                $union = count($sh[$i]) + count($sh[$j]) - $inter;
                $sim = $union ? $inter / $union : 0;
                if ($sim >= $min) {
                    $reasons[] = 'text similarity '.round($sim * 100).'%';
                }
                if ($reasons) {
                    $pairs[] = [$i, $j, $reasons];
                }
            }
        }
        // group connected pages
        $parent = range(0, count($entries) - 1);
        $find = function ($x) use (&$parent, &$find) {
            return $parent[$x] === $x ? $x : ($parent[$x] = $find($parent[$x]));
        };
        foreach ($pairs as [$i, $j]) {
            $parent[$find($i)] = $find($j);
        }
        $groups = [];
        foreach ($pairs as [$i, $j, $reasons]) {
            $g = $find($i);
            $groups[$g]['members'][$i] = true;
            $groups[$g]['members'][$j] = true;
            $groups[$g]['why'][] = $entries[$i]['path'].' ↔ '.$entries[$j]['path'].': '.implode('; ', $reasons);
        }
        $rank = ['core_pos' => 0, 'business_size' => 1, 'feature' => 2, 'erp' => 2, 'industry' => 3, 'download' => 4, 'standard' => 5, 'legal' => 6, 'post' => 7];
        $out = [];
        foreach ($groups as $g) {
            $members = array_map(fn ($i) => $entries[$i], array_keys($g['members']));
            usort($members, fn ($x, $y) => [count($inbound[$y['path']] ?? []), -($rank[$y['page_type']] ?? 9), $y['own_words']] <=> [count($inbound[$x['path']] ?? []), -($rank[$x['page_type']] ?? 9), $x['own_words']]);
            $out[] = [
                'pages' => array_map(fn ($m) => ['url' => $m['path'], 'type' => $m['page_type'], 'title' => $m['title'], 'h1' => $m['h1'], 'links_in' => count($inbound[$m['path']] ?? []), 'own_words' => $m['own_words']], $members),
                'suggested_primary' => $members[0]['path'],
                'why' => array_slice($g['why'], 0, 12),
            ];
        }
        usort($out, fn ($x, $y) => count($y['pages']) <=> count($x['pages']));

        return ['groups' => count($out), 'note' => 'Suggested primary = most internal links in, then the more central page type, then the most own words. Differentiate the others (different intent/angle, unique content) or merge them with a 301.', 'clusters' => $out];
    }

    private function thin(array $a): array
    {
        $entries = array_values(SiteIndex::all());
        $sh = array_map(fn ($e) => SiteIndex::shingles($e['own_text']), $entries);
        $count = [];
        foreach ($sh as $s) {
            foreach ($s as $k => $_) {
                $count[$k] = ($count[$k] ?? 0) + 1;
            }
        }
        $rows = [];
        foreach ($entries as $i => $e) {
            if (! empty($a['page_type']) && $e['page_type'] !== $a['page_type']) {
                continue;
            }
            $total = count($sh[$i]);
            $dup = 0;
            foreach ($sh[$i] as $k => $_) {
                if ($count[$k] > 1) {
                    $dup++;
                }
            }
            $pct = $total ? round($dup / $total * 100) : 0;
            if (! empty($a['max_words']) && $e['own_words'] >= $a['max_words']) {
                continue;
            }
            $rows[] = ['url' => $e['path'], 'type' => $e['page_type'], 'own_words' => $e['own_words'], 'unique_words_estimate' => (int) round($e['own_words'] * (100 - $pct) / 100),
                'duplicated_pct' => $pct, 'flag' => $e['own_words'] < 300 ? 'thin' : ($pct >= 40 ? 'mostly shared text' : null)];
        }
        usort($rows, fn ($x, $y) => $x['unique_words_estimate'] <=> $y['unique_words_estimate']);

        return ['pages' => count($rows), 'note' => 'Own content = html sections, hero title/text or post body; header, footer and shared components are left out. duplicated_pct = share of 5-word phrases that also appear on another page.', 'rows' => $rows];
    }

    private function report(Model $owner): array
    {
        SiteIndex::forget();
        $e = SiteIndex::entry($owner);
        $inbound = SiteIndex::inbound();
        preg_match_all('#<img\s[^>]*>#i', $e['main'], $imgs);
        $imageIssues = [];
        foreach ($imgs[0] as $img) {
            preg_match('#src="([^"]+)"#', $img, $s);
            $p = [];
            if (! preg_match('/alt="[^"]+"/', $img)) {
                $p[] = 'no alt';
            }
            if (! str_contains($img, 'width=')) {
                $p[] = 'no width/height';
            }
            if ($p) {
                $imageIssues[] = ['src' => $s[1] ?? null, 'issues' => $p];
            }
        }

        return [
            'url' => $e['path'],
            'type' => $e['page_type'],
            'meta' => ['title' => $e['title'], 'title_length' => mb_strlen($e['title']), 'description' => $e['description'], 'description_length' => mb_strlen($e['description']),
                'canonical' => $e['canonical'], 'robots' => $e['robots'], 'og_image' => $e['og_image'], 'focus_keyword' => $e['focus_keyword']],
            'outline' => array_map(fn ($h) => str_repeat('  ', $h['level'] - 1).'h'.$h['level'].': '.$h['text'], $e['headings']),
            'words' => ['main' => $e['words'], 'own' => $e['own_words']],
            'schema_types' => $e['schema_types'],
            'links_out' => array_values(array_map(fn ($l) => ['to' => $l['path'] ?? $l['href'], 'anchor' => $l['anchor'], 'internal' => $l['internal']], $e['links'])),
            'links_in' => collect($inbound[rtrim(strtolower($e['path']), '/').'/'] ?? [])->map(fn ($anchor, $from) => ['from' => $from, 'anchor' => $anchor])->values()->all(),
            'image_issues' => $imageIssues,
            'first_paragraph' => $e['first_paragraph'],
            'site_noindex' => Cms::noindex(),
        ];
    }
}
