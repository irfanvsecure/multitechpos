<?php

namespace App\Mcp\Support;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

/**
 * Everything the audits need about every live page and post, built once per request:
 * rendered HTML, the page's own text, headings, links and JSON-LD types.
 */
class SiteIndex
{
    private static ?array $all = null;

    private static int $version = 0;

    /** @return array<string, array> path => entry */
    public static function all(bool $includeDrafts = false): array
    {
        if (self::$all !== null && ! $includeDrafts && self::$version === \App\Cms\Cms::version()) {
            return self::$all;
        }
        self::$version = \App\Cms\Cms::version();
        $out = [];
        $pages = Page::query()->with(['sections', 'seo'])->when(! $includeDrafts, fn ($q) => $q->where('status', 'published'))->get();
        foreach ($pages as $p) {
            $out[$p->path] = self::entry($p);
        }
        $posts = Post::query()->with('seo')->when(! $includeDrafts, fn ($q) => $q->live())->get();
        foreach ($posts as $p) {
            $out[$p->path] = self::entry($p);
        }
        if (! $includeDrafts) {
            self::$all = $out;
        }

        return $out;
    }

    public static function forget(): void
    {
        self::$all = null;
        Render::forget();
    }

    public static function entry(Model $owner): array
    {
        $html = Render::full($owner);
        $main = Render::main($html);
        $own = Render::ownText($owner);
        preg_match('#<title>(.*?)</title>#s', $html, $t);
        preg_match('#<meta name="description" content="([^"]*)"#', $html, $d);
        preg_match('#<meta name="robots" content="([^"]*)"#', $html, $r);
        preg_match('#<link rel="canonical" href="([^"]*)"#', $html, $c);
        preg_match('#<meta property="og:image" content="([^"]*)"#', $html, $og);
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $ld);
        $types = [];
        foreach ($ld[1] as $json) {
            $data = json_decode($json, true) ?? [];
            foreach (($data['@graph'] ?? [$data]) as $node) {
                foreach ((array) ($node['@type'] ?? []) as $type) {
                    $types[] = $type;
                }
            }
        }
        preg_match('#<p\b[^>]*>(.*?)</p>#s', preg_replace('#<(nav|header)\b.*?</\1>#s', '', $main), $firstP);
        $h1 = array_values(array_filter(Html::headings($main), fn ($h) => $h['level'] === 1));

        return [
            'owner' => $owner,
            'kind' => $owner instanceof Post ? 'post' : 'page',
            'id' => $owner->getKey(),
            'path' => $owner->path,
            'page_type' => $owner instanceof Page ? $owner->page_type : 'post',
            'title' => html_entity_decode($t[1] ?? '', ENT_QUOTES | ENT_HTML5),
            'description' => html_entity_decode($d[1] ?? '', ENT_QUOTES | ENT_HTML5),
            'robots' => $r[1] ?? '',
            'canonical' => $c[1] ?? '',
            'og_image' => $og[1] ?? '',
            'og_image_is_default' => str_ends_with($og[1] ?? '', (string) config('site.logo')),
            'focus_keyword' => $owner->seo?->focus_keyword,
            'h1' => $h1[0]['text'] ?? null,
            'headings' => Html::headings($main),
            'full_headings' => Html::headings($html),
            'links' => Html::links($main),
            'own_text' => $own,
            'own_words' => Html::words($own),
            'words' => Html::words(Html::text($main)),
            'schema_types' => array_values(array_unique($types)),
            'first_paragraph' => mb_strimwidth(Html::text($firstP[1] ?? ''), 0, 400, '…'),
            'main' => $main,
        ];
    }

    /** Inbound internal links per target path (from live pages' main content). */
    public static function inbound(): array
    {
        $in = [];
        foreach (self::all() as $path => $e) {
            foreach ($e['links'] as $l) {
                if ($l['internal']) {
                    $target = rtrim(strtolower($l['path']), '/').'/';
                    if ($target !== rtrim(strtolower($path), '/').'/') {
                        $in[$target][$path] = $l['anchor'];
                    }
                }
            }
        }

        return $in;
    }

    /** 5-word shingles of a text (lowercase). */
    public static function shingles(string $text, int $n = 5): array
    {
        $words = preg_split('/\W+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $out = [];
        for ($i = 0, $c = count($words) - $n; $i <= $c; $i++) {
            $out[implode(' ', array_slice($words, $i, $n))] = true;
        }

        return $out;
    }
}
