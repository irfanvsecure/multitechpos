<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Phase 1b: keeps the ranking value of the old WordPress URLs.
 *
 * Sources (database/content/wordpress/):
 *   wordpress-meta-2026-10.json   title/description/og/body classes of every WP page, captured 2026-10-19 before the move
 *   wayback-sitemap-2025-11.txt   the Yoast sitemap as archived by the Wayback Machine (Nov 2025)
 *   wayback-paths.txt             every multitechpos.com path the Wayback Machine has a 200 capture of
 *
 * Writes docs/url-map.csv, docs/meta-differences.md, database/content/redirects.json and fills
 * empty titles/descriptions in database/content/site.json from WordPress.
 */
class CmsSeoCarryover extends Command
{
    protected $signature = 'cms:seo-carryover';

    protected $description = 'Map the old WordPress URLs and meta onto the new site';

    /** Paths that changed or are gone: old path => [new path or null for 410, note]. */
    private const MOVED = [
        '/download/laundrypro/' => ['/laundrypro/', 'WP Download Manager file page → product page'],
        '/download/salonpro/' => ['/salonpro/', 'WP Download Manager file page → product page'],
        '/download/tailorpro/' => ['/tailorpro/', 'WP Download Manager file page → product page'],
        '/download/multitechpos-retailpro/' => ['/downloads/', 'RetailPro has no page any more → downloads hub (confirm)'],
        '/author/mark/' => ['/our-blogs/', 'old WP author with no posts on the new site → blog (confirm)'],
        '/home/feed/' => ['/our-blogs/', 'WP feed'],
        '/support/' => ['/support-policy/', 'old page → closest page (confirm)'],
        '/reseller/' => ['/contact/', 'old page → closest page (confirm)'],
        // in the Nov 2025 Yoast sitemap, removed from WordPress before the move
        '/restorantpro/' => ['/restropro/', 'old misspelt RestroPro page → RestroPro'],
        '/pricing-restropro/' => ['/restropro/', 'old price page → product page (the site shows no prices)'],
        '/pricing-salonpro/' => ['/salonpro/', 'old price page → product page (the site shows no prices)'],
        '/pricing-laundrypro/' => ['/laundrypro/', 'old price page → product page (the site shows no prices)'],
        '/pricing-tailorpro/' => ['/tailorpro/', 'old price page → product page (the site shows no prices)'],
        '/pricing/' => ['/contact/', 'old price list → quote request (the site shows no prices; confirm)'],
        '/one-time-pricing/' => ['/contact/', 'old price list → quote request (the site shows no prices; confirm)'],
        '/clients/' => ['/about-us/', 'old client list → about us (confirm)'],
        '/portfolio-types/international/' => ['/about-us/', 'old client portfolio → about us (confirm)'],
        '/portfolio-types/national/' => ['/about-us/', 'old client portfolio → about us (confirm)'],
        '/login/' => [null, 'old WooCommerce account page, no equivalent'],
        '/register/' => [null, 'old WooCommerce account page, no equivalent'],
        '/my-account/' => [null, 'old WooCommerce account page, no equivalent'],
        '/trackback/' => [null, 'WP trackback endpoint, no content'],
        '/image/video/about-us-video.webm' => [null, 'old theme video, file no longer exists'],
        '/vendors/linear-icon/fonts/Linearicons-Free.eot' => [null, 'old theme font'],
        '/vendors/themify-icon/fonts/themify.eot' => [null, 'old theme font'],
    ];

    /** Handled by routes/web.php already (kept as code: patterns). */
    private const CODE = [
        '/feed/' => '/our-blogs/', '/comments/feed/' => '/our-blogs/', '/blog/' => '/our-blogs/',
        '/download/restropro/' => '/restropro/', '/sitemap_index.xml' => '/sitemap.xml', '/page-sitemap.xml' => '/sitemap.xml',
        '/post-sitemap.xml' => '/sitemap.xml', '/wp-sitemap.xml' => '/sitemap.xml',
        '/portfolio-types-sitemap.xml' => null, '/index.php' => '/',
    ];

    public function handle(): int
    {
        $dir = base_path('database/content/wordpress');
        $site = json_decode(File::get(base_path('database/content/site.json')), true);
        $wpMeta = json_decode(File::get($dir.'/wordpress-meta-2026-10.json'), true);

        $new = [];
        foreach ($site['pages'] as $i => $p) {
            $new['/'.($p['slug'] === '' ? '' : $p['slug'].'/')] = ['kind' => 'page', 'i' => $i, 'seo' => $p['seo']];
        }
        foreach ($site['posts'] as $i => $p) {
            $new['/'.$p['slug'].'/'] = ['kind' => 'post', 'i' => $i, 'seo' => $p['seo']];
        }

        // every old URL we know about, with where we saw it
        $old = [];
        foreach (array_keys($wpMeta) as $slug) {
            $old['/'.$slug.'/']['WP capture 2026-10'] = true;
        }
        foreach (file($dir.'/wayback-sitemap-2025-11.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $url) {
            $path = parse_url(trim($url), PHP_URL_PATH) ?: '/';
            $old[$path]['Yoast sitemap 2025-11'] = true;
        }
        foreach (file($dir.'/wayback-paths.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $path) {
            $path = trim($path);
            if (str_contains($path, '?')) {
                $path = Str::before($path, '?'); // utm-tagged links: same page
            }
            $old[$path]['Wayback capture'] = true;
        }
        $old['/']['Yoast sitemap 2025-11'] = true;
        ksort($old);

        $rows = [['old_url', 'seen_in', 'new_url', 'status', 'note']];
        $redirects = [];
        $gone = [];
        foreach ($old as $path => $seen) {
            $seenIn = implode(' + ', array_keys($seen));
            if (isset($new[$path])) {
                $rows[] = [$path, $seenIn, $path, '200', 'same URL (no redirect needed)'];
            } elseif (array_key_exists($path, self::CODE)) {
                $to = self::CODE[$path];
                $rows[] = [$path, $seenIn, $to ?? '', $to ? '301' : '301', $to ? 'redirect in routes/web.php' : 'any old sitemap → 301 to home by the catch-all'];
            } elseif (isset(self::MOVED[$path])) {
                [$to, $note] = self::MOVED[$path];
                $redirects[] = ['from_path' => $path, 'to_url' => $to, 'status_code' => $to ? 301 : 410, 'note' => 'WordPress: '.$note];
                $rows[] = [$path, $seenIn, $to ?? '', $to ? '301' : '410', $note];
                if (! $to) {
                    $gone[] = [$path, $note];
                }
            } else {
                $rows[] = [$path, $seenIn, '/', '301', 'NOT MAPPED: falls to the catch-all (301 to home). Please review'];
            }
        }

        // /?p=ID and /?page_id=ID links (WordPress short links) from the body classes of the capture
        foreach ($wpMeta as $slug => $m) {
            if (preg_match('/\b(?:page-id|postid)-(\d+)\b/', $m['body'] ?? '', $id)) {
                $to = '/'.$slug.'/';
                foreach (['p', 'page_id'] as $q) {
                    $redirects[] = ['from_path' => '/?'.$q.'='.$id[1], 'to_url' => $to, 'status_code' => 301, 'note' => 'WordPress short link'];
                }
                $rows[] = ['/?p='.$id[1].' and /?page_id='.$id[1], 'WP capture 2026-10 (body class)', $to, '301', 'WordPress short link'];
            }
        }

        // WordPress image URLs: routes/web.php sends /wp-content/uploads/* to /images/* (same file names)
        $rows[] = ['/wp-content/uploads/*', 'all WP image URLs', '/images/*', '301', 'redirect in routes/web.php, same file names'];
        $rows[] = ['/category/*', 'WP category archives', '/our-blogs/', '301', 'redirect in routes/web.php'];
        $rows[] = ['/author/multitech_admin/page/N/', 'WP author archive pages', '/author/multitech_admin/', '301', 'redirect in routes/web.php'];

        // meta: fill empty values from WordPress, list the differences
        $diffs = [];
        $filled = [];
        foreach ($wpMeta as $slug => $m) {
            $path = '/'.$slug.'/';
            if (! isset($new[$path])) {
                continue;
            }
            $ref = &$this->seoRef($site, $new[$path]);
            foreach (['title', 'description'] as $field) {
                $wp = trim(html_entity_decode((string) ($m[$field] ?? ''), ENT_QUOTES | ENT_HTML5));
                $now = trim((string) ($ref[$field] ?? ''));
                if ($wp === '') {
                    continue;
                }
                if ($now === '' || $this->placeholder($now)) {
                    $ref[$field] = $wp;
                    $filled[] = [$path, $field, $now, $wp];
                } elseif ($now !== $wp) {
                    $diffs[] = [$path, $field, $wp, $now];
                }
            }
            unset($ref);
        }

        // write
        $fh = fopen(base_path('docs/url-map.csv'), 'w');
        foreach ($rows as $r) {
            fputcsv($fh, $r);
        }
        fclose($fh);
        File::put(base_path('database/content/redirects.json'), json_encode($redirects, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
        File::put(base_path('database/content/site.json'), json_encode($site, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n");
        File::put(base_path('docs/meta-differences.md'), $this->report($diffs, $filled, $rows, $gone));

        $unmapped = count(array_filter($rows, fn ($r) => str_starts_with($r[4], 'NOT MAPPED')));
        $this->info(count($old).' old URLs: '.count(array_filter($rows, fn ($r) => $r[3] === '200')).' unchanged, '.count($redirects).' DB redirects, '.$unmapped.' unmapped, '.count($diffs).' meta differences, '.count($filled).' empty meta filled from WordPress.');

        return self::SUCCESS;
    }

    private function &seoRef(array &$site, array $entry): array
    {
        $list = $entry['kind'] === 'page' ? 'pages' : 'posts';

        return $site[$list][$entry['i']]['seo'];
    }

    private function placeholder(string $value): bool
    {
        return in_array(strtolower($value), ['multitech pos', 'multitechpos', 'untitled', 'page title', 'lorem ipsum'], true)
            || str_contains(strtolower($value), 'lorem ipsum');
    }

    private function report(array $diffs, array $filled, array $rows, array $gone): string
    {
        $md = "# Meta differences: live WordPress (Yoast) vs the new site\n\n";
        $md .= "WordPress values: captured from the live WordPress pages on 2026-10-19, just before the move\n";
        $md .= "(`database/content/wordpress/wordpress-meta-2026-10.json`). The new values are the hand-written titles and\n";
        $md .= "descriptions already on the Laravel site. Rule applied: **where the new value was empty, the WordPress value was\n";
        $md .= "imported; where both exist and differ, the new value is kept** and listed here for you to review.\n\n";
        $md .= '- Filled from WordPress: **'.count($filled)."**\n";
        $md .= '- Different (new value kept): **'.count($diffs)."**\n\n";
        if ($filled) {
            $md .= "## Filled from WordPress\n\n| Page | Field | Was | Now (from WordPress) |\n|---|---|---|---|\n";
            foreach ($filled as [$p, $f, $was, $now]) {
                $md .= "| `$p` | $f | ".($was === '' ? '_(empty)_' : $this->cell($was)).' | '.$this->cell($now)." |\n";
            }
            $md .= "\n";
        }
        $md .= "## Different (new value kept)\n\n| Page | Field | WordPress (Yoast) | New site (kept) |\n|---|---|---|---|\n";
        foreach ($diffs as [$p, $f, $wp, $now]) {
            $md .= "| `$p` | $f | ".$this->cell($wp).' | '.$this->cell($now)." |\n";
        }
        if ($gone) {
            $md .= "\n## URLs answered with 410 Gone\n\n";
            foreach ($gone as [$p, $note]) {
                $md .= "- `$p`: $note\n";
            }
        }

        return $md;
    }

    private function cell(string $s): string
    {
        return str_replace(['|', "\n"], ['\\|', ' '], $s);
    }
}
