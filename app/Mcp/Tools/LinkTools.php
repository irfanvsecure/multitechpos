<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Html;
use App\Mcp\Support\Render;
use App\Mcp\Support\SiteIndex;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class LinkTools extends ToolSet
{
    private const GENERIC_ANCHORS = ['click here', 'here', 'read more', 'learn more', 'more', 'this', 'link', 'this page', 'see more', 'details'];

    public function tools(): array
    {
        $owner = self::ownerProps();
        $expected = self::s('updated_at of the page (or post) from get_page / get_blog');

        return [
            $this->read('get_internal_links', 'Internal links of a page/post: links out (with anchor text) and links in from other pages.', $owner, [], fn ($a) => $this->links($this->findOwner($a))),

            $this->read('find_orphan_pages', 'Published pages and posts that no other page links to (from page content; menus/footer not counted unless include_menus=true).', [
                'include_menus' => self::b('Count header/footer menu links too'),
            ], [], fn ($a) => $this->orphans($a)),

            $this->write('add_internal_link', 'Link the first exact occurrence of anchor_text (in text that is not already a link) to url. Refused if the text is not found, is already linked, or the page already links to that URL.', $owner + [
                'anchor_text' => self::s('Exact words on the page to turn into the link'),
                'url' => self::s('Target path (/retail-pos-software-uae/) or https URL'),
                'section_id' => self::i('Only look in this section'),
                'new_tab' => self::b('Open in a new tab (default false)'),
                'expected_updated_at' => $expected,
            ], ['anchor_text', 'url', 'expected_updated_at'], fn ($a) => $this->add($a)),

            $this->write('update_internal_link', 'Point an existing link to a new URL (anchor text kept). Optional anchor_text to pick one link when several go to old_url.', $owner + [
                'old_url' => self::s('Current target'),
                'new_url' => self::s('New target'),
                'anchor_text' => self::s('Only the link with this text'),
                'section_id' => self::i('Only in this section'),
                'expected_updated_at' => $expected,
            ], ['old_url', 'new_url', 'expected_updated_at'], fn ($a) => $this->change($a, 'relink')),

            $this->write('remove_internal_link', 'Remove a link but keep its text.', $owner + [
                'url' => self::s('Link target to remove'),
                'anchor_text' => self::s('Only the link with this text'),
                'section_id' => self::i('Only in this section'),
                'expected_updated_at' => $expected,
            ], ['url', 'expected_updated_at'], fn ($a) => $this->change($a, 'unlink')),

            $this->write('replace_link_sitewide', 'Change every link to old_url into new_url in all pages, posts and component options. dry_run defaults to true.', [
                'old_url' => self::s('Current target (path or URL)'),
                'new_url' => self::s('New target'),
                'dry_run' => self::dryRun(),
            ], ['old_url', 'new_url'], fn ($a) => $this->sitewide($a)),

            $this->read('anchor_text_report', 'Anchor texts used for each internal target: generic anchors ("click here"), one anchor used for several targets, and targets with only one anchor wording.', [
                'target' => self::s('Only links to this path'),
            ], [], fn ($a) => $this->anchors($a)),

            $this->read('check_broken_links', 'Check links in page content: internal links to missing pages or redirects, and (external=true) external URLs that fail. Reports page and section.', $owner + [
                'external' => self::b('Also request external URLs (default true; at most 150)'),
            ], [], fn ($a) => $this->broken($a), true),
        ];
    }

    /** Internal links to $path in stored content (sections + posts). */
    public static function countLinksTo(string $path): int
    {
        $n = 0;
        foreach (PageSection::query()->where('type', 'html')->where('content', 'like', '%'.rtrim($path, '/').'%')->cursor() as $s) {
            $n += count(array_filter(Html::links((string) $s->content), fn ($l) => Html::samePath($l['path'], $path)));
        }
        foreach (Post::query()->where('content', 'like', '%'.rtrim($path, '/').'%')->cursor() as $p) {
            $n += count(array_filter(Html::links((string) $p->content), fn ($l) => Html::samePath($l['path'], $path)));
        }

        return $n;
    }

    private function links(Model $owner): array
    {
        $e = SiteIndex::entry($owner);
        $in = SiteIndex::inbound()[rtrim(strtolower($owner->path), '/').'/'] ?? [];
        $out = array_values(array_filter($e['links'], fn ($l) => $l['internal']));

        return [
            'url' => $owner->path,
            'links_out' => array_map(fn ($l) => ['to' => $l['path'], 'anchor' => $l['anchor']], $out),
            'external_out' => array_values(array_map(fn ($l) => ['to' => $l['href'], 'anchor' => $l['anchor']], array_filter($e['links'], fn ($l) => ! $l['internal'] && preg_match('#^https?://#', $l['href'])))),
            'links_in' => collect($in)->map(fn ($anchor, $from) => ['from' => $from, 'anchor' => $anchor])->values()->all(),
            'counts' => ['out' => count($out), 'in' => count($in)],
        ];
    }

    private function orphans(array $a): array
    {
        $inbound = SiteIndex::inbound();
        if ($a['include_menus'] ?? false) {
            $sample = SiteIndex::all()['/'] ?? null;
            $html = $sample ? Render::full($sample['owner']) : '';
            foreach (Html::links($html) as $l) {
                if ($l['internal']) {
                    $inbound[rtrim(strtolower($l['path']), '/').'/']['menu'] = $l['anchor'];
                }
            }
        }
        $orphans = [];
        foreach (SiteIndex::all() as $path => $e) {
            if ($path !== '/' && empty($inbound[rtrim(strtolower($path), '/').'/'])) {
                $orphans[] = ['url' => $path, 'type' => $e['page_type'], 'title' => $e['title']];
            }
        }

        return ['orphans' => count($orphans), 'pages' => $orphans];
    }

    /** Target -> stored href ({{base}}/path/ for internal), refusing dead internal targets. */
    private function target(string $url): array
    {
        $path = Html::internalPath($url);
        if ($path === null) {
            if (! preg_match('#^https://#i', $url)) {
                throw new ToolError('url must be an internal path (/page/) or an https:// URL.');
            }

            return [$url, null];
        }
        if (! $this->resolves($path)) {
            throw new ToolError("$path is not a live page or post on this site.");
        }
        $q = parse_url($url, PHP_URL_FRAGMENT);

        return ['{{base}}'.$path.($q ? '#'.$q : ''), $path];
    }

    private function resolves(string $path): bool
    {
        $slug = trim($path, '/');

        return Page::query()->where('slug', $slug)->where('status', 'published')->exists()
            || Post::query()->live()->where('slug', $slug)->exists();
    }

    /** html "fields" a page/post owns: list of [model, attribute] */
    private function targets(Model $owner, ?int $sectionId): array
    {
        if ($owner instanceof Post) {
            return [[$owner, 'content']];
        }
        $list = [];
        foreach ($owner->sections as $s) {
            if ($s->type === 'html' && (! $sectionId || $s->id === $sectionId)) {
                $list[] = [$s, 'content'];
            }
        }
        if ($sectionId && ! $list) {
            throw new ToolError('section_id is not an html section of this page.');
        }

        return $list;
    }

    private function add(array $a): array
    {
        $owner = $this->guard($this->findOwner($a), $a, 'page');
        [$href, $path] = $this->target($a['url']);
        $already = array_filter(SiteIndex::entry($owner)['links'], fn ($l) => $path ? Html::samePath($l['path'], $path) : $l['href'] === $a['url']);
        if ($already) {
            throw new ToolError('This page already links to '.$a['url'].' (anchor: "'.reset($already)['anchor'].'"). One link per target is enough.');
        }
        if ($path && Html::samePath($path, $owner->path)) {
            throw new ToolError('A page should not link to itself.');
        }
        $attrs = ($a['new_tab'] ?? false) ? ['target' => '_blank', 'rel' => 'noopener'] : (! $path ? ['target' => '_blank', 'rel' => 'noopener'] : []);
        $reason = 'not_found';
        foreach ($this->targets($owner, $a['section_id'] ?? null) as [$model, $field]) {
            [$html, $why] = Html::linkFirst((string) $model->{$field}, $a['anchor_text'], $href, $attrs);
            if ($html !== null) {
                $before = $model instanceof PageSection ? PageTools::sectionOut($model) : BlogTools::postOut($model);
                Activity::revision($model, 'before add_internal_link');
                $model->update([$field => Validate::html($html, $model instanceof PageSection ? count($model->embeds ?? []) : null)[0]]);
                $owner->touch();
                Activity::log('update', $model, $before, $model instanceof PageSection ? PageTools::sectionOut($model) : BlogTools::postOut($model), 'link "'.$a['anchor_text'].'" → '.$a['url'].' on '.$owner->path, [$owner->path]);

                return ['linked' => true, 'where' => $model instanceof PageSection ? ['section_id' => $model->id, 'key' => $model->key] : ['post_id' => $model->id], 'page_updated_at' => Guard::stamp($owner->fresh())];
            }
            if ($why === 'already_linked') {
                $reason = 'already_linked';
            }
        }
        throw new ToolError($reason === 'already_linked'
            ? '"'.$a['anchor_text'].'" is only found inside an existing link.'
            : '"'.$a['anchor_text'].'" was not found in the page text (it must match exactly, outside links and the H1).');
    }

    private function change(array $a, string $op): array
    {
        $owner = $this->guard($this->findOwner($a), $a, 'page');
        $total = 0;
        $where = [];
        foreach ($this->targets($owner, $a['section_id'] ?? null) as [$model, $field]) {
            if ($op === 'relink') {
                [$href] = $this->target($a['new_url']);
                [$html, $n] = Html::relink((string) $model->{$field}, $a['old_url'], $href === $a['new_url'] ? $a['new_url'] : (Html::internalPath($a['new_url']) ?? $a['new_url']), $a['anchor_text'] ?? null);
            } else {
                [$html, $n] = Html::unlink((string) $model->{$field}, Html::internalPath($a['url']) ?? $a['url'], $a['anchor_text'] ?? null);
            }
            if ($n) {
                $before = $model instanceof PageSection ? PageTools::sectionOut($model) : BlogTools::postOut($model);
                Activity::revision($model, 'before '.$op);
                $model->update([$field => $html]);
                $total += $n;
                $where[] = $model instanceof PageSection ? $model->id : 'post';
                Activity::log('update', $model, $before, $model instanceof PageSection ? PageTools::sectionOut($model) : BlogTools::postOut($model), $op.' on '.$owner->path, [$owner->path]);
            }
        }
        if (! $total) {
            throw new ToolError('No matching link in the page content (links from shared components such as the CTA band are code).');
        }
        $owner->touch();

        return ['changed_links' => $total, 'sections' => $where, 'page_updated_at' => Guard::stamp($owner->fresh())];
    }

    private function sitewide(array $a): array
    {
        $dry = $a['dry_run'] ?? true;
        $from = Html::internalPath($a['old_url']) ?? $a['old_url'];
        [$href] = $this->target($a['new_url']);
        $newTarget = Html::internalPath($a['new_url']) ?? $a['new_url'];
        $changes = [];
        $total = 0;
        foreach (PageSection::query()->with('page')->get() as $s) {
            if ($s->type === 'html') {
                [$html, $n] = Html::relink((string) $s->content, $from, $newTarget);
                $fields = $n ? ['content' => $html] : [];
            } else {
                $n = 0;
                $attrs = $s->attrs ?? [];
                array_walk_recursive($attrs, function (&$v, $k) use ($from, $newTarget, &$n) {
                    if ($k === 'url' && is_string($v) && Html::samePath(Html::internalPath($v) ?? $v, $from)) {
                        $v = $newTarget;
                        $n++;
                    }
                });
                $fields = $n ? ['attrs' => $attrs] : [];
            }
            if ($n) {
                $total += $n;
                $changes[] = ['page' => $s->page?->path, 'section_id' => $s->id, 'links' => $n];
                if (! $dry) {
                    Activity::revision($s, 'before replace_link_sitewide');
                    $before = PageTools::sectionOut($s);
                    $s->update($fields);
                    Activity::log('update', $s, $before, PageTools::sectionOut($s), "link $from → $newTarget", [$s->page?->path]);
                }
            }
        }
        foreach (Post::query()->get() as $p) {
            [$html, $n] = Html::relink((string) $p->content, $from, $newTarget);
            if ($n) {
                $total += $n;
                $changes[] = ['page' => $p->path, 'post_id' => $p->id, 'links' => $n];
                if (! $dry) {
                    Activity::revision($p, 'before replace_link_sitewide');
                    $before = BlogTools::postOut($p);
                    $p->update(['content' => $html]);
                    Activity::log('update', $p, $before, BlogTools::postOut($p), "link $from → $newTarget", [$p->path]);
                }
            }
        }

        return ['dry_run' => $dry, 'links' => $total, 'changes' => $changes, 'next' => $dry && $total ? 'Run again with dry_run=false to apply.' : null];
    }

    private function anchors(array $a): array
    {
        $byTarget = [];
        $byAnchor = [];
        foreach (SiteIndex::all() as $path => $e) {
            foreach ($e['links'] as $l) {
                if (! $l['internal']) {
                    continue;
                }
                $t = rtrim(strtolower($l['path']), '/').'/';
                if (! empty($a['target']) && ! Html::samePath($t, $a['target'])) {
                    continue;
                }
                $anchor = mb_strtolower(trim($l['anchor']));
                $byTarget[$t][$anchor] = ($byTarget[$t][$anchor] ?? 0) + 1;
                $byAnchor[$anchor][$t] = true;
            }
        }
        $generic = [];
        foreach ($byAnchor as $anchor => $targets) {
            if (in_array($anchor, self::GENERIC_ANCHORS, true) || $anchor === '') {
                $generic[] = ['anchor' => $anchor === '' ? '(no text, e.g. image link)' : $anchor, 'targets' => count($targets)];
            }
        }
        $ambiguous = [];
        foreach ($byAnchor as $anchor => $targets) {
            if (count($targets) > 1 && ! in_array($anchor, self::GENERIC_ANCHORS, true) && $anchor !== '' && mb_strlen($anchor) > 3) {
                $ambiguous[] = ['anchor' => $anchor, 'targets' => array_keys($targets)];
            }
        }
        $targets = [];
        foreach ($byTarget as $t => $anchors) {
            arsort($anchors);
            $targets[] = ['target' => $t, 'links' => array_sum($anchors), 'anchors' => $anchors];
        }
        usort($targets, fn ($x, $y) => $y['links'] <=> $x['links']);

        return ['generic_anchors' => $generic, 'same_anchor_different_targets' => array_slice($ambiguous, 0, 50), 'targets' => $targets];
    }

    private function broken(array $a): array
    {
        $entries = (! empty($a['page_id']) || ! empty($a['post_id']) || isset($a['path'])) ? [SiteIndex::entry($this->findOwner($a))] : array_values(SiteIndex::all());
        $redirects = Redirect::query()->get()->keyBy('from_path');
        $problems = [];
        $external = [];
        foreach ($entries as $e) {
            $owner = $e['owner'];
            $sources = $owner instanceof Page
                ? $owner->sections->filter(fn ($s) => $s->type === 'html')->map(fn ($s) => [$s->id, $s->key, (string) $s->content])->all()
                : [[null, 'post body', (string) $owner->content]];
            foreach ($sources as [$sid, $key, $html]) {
                foreach (Html::links($html) as $l) {
                    if ($l['internal']) {
                        $path = $l['path'];
                        if (preg_match('#\.(jpe?g|png|webp|gif|svg|pdf|avif)$#i', $path)) {
                            if (! is_file(public_path(ltrim(rawurldecode($path), '/')))) {
                                $problems[] = ['page' => $e['path'], 'section_id' => $sid, 'section' => $key, 'link' => $path, 'anchor' => $l['anchor'], 'problem' => 'file missing'];
                            }

                            continue;
                        }
                        if ($this->resolves($path) || $path === '/') {
                            continue;
                        }
                        $r = $redirects[$path] ?? $redirects[rtrim($path, '/').'/'] ?? null;
                        $problems[] = ['page' => $e['path'], 'section_id' => $sid, 'section' => $key, 'link' => $path, 'anchor' => $l['anchor'],
                            'problem' => $r ? ($r->status_code === 410 ? '410 Gone' : 'redirects to '.$r->to_url.' (update the link)') : 'no such page (falls to the 301-to-home catch-all)'];
                    } elseif (preg_match('#^https?://#i', $l['href'])) {
                        $external[$l['href']][] = ['page' => $e['path'], 'section_id' => $sid, 'section' => $key, 'anchor' => $l['anchor']];
                    }
                }
            }
        }

        $externalResults = [];
        if (($a['external'] ?? true) && $external) {
            $urls = array_slice(array_keys($external), 0, 150);
            foreach (array_chunk($urls, 15) as $chunk) {
                $responses = Http::pool(fn (Pool $pool) => array_map(fn ($u) => $pool->as($u)->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; SiteLinkCheck/1.0)'])->timeout(8)->withoutRedirecting()->head($u), $chunk));
                foreach ($chunk as $u) {
                    $res = $responses[$u] ?? null;
                    $status = $res instanceof \Illuminate\Http\Client\Response ? $res->status() : 0;
                    if (in_array($status, [403, 405, 0], true)) {
                        try {
                            $status = Http::withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; SiteLinkCheck/1.0)'])->timeout(8)->get($u)->status();
                        } catch (\Throwable) {
                            $status = 0;
                        }
                    }
                    if ($status === 0 || $status >= 400) {
                        foreach ($external[$u] as $where) {
                            $externalResults[] = $where + ['link' => $u, 'problem' => $status ? "HTTP $status" : 'no response'];
                        }
                    }
                }
            }
        }

        return [
            'pages_checked' => count($entries),
            'internal_problems' => $problems,
            'external_checked' => ($a['external'] ?? true) ? min(150, count($external)) : 0,
            'external_problems' => $externalResults,
        ];
    }
}
