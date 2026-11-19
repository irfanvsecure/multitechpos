<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Compares two `site:snapshot` folders: status, redirect target, and per page the
 * normalised HTML plus title, meta, canonical, JSON-LD, headings, links, images, forms and text.
 */
class SiteDiff extends Command
{
    protected $signature = 'site:diff {before} {after} {--report=} {--title=Diff}';

    protected $description = 'Compare two site snapshots and write a report';

    /** What is ignored, so the report can say so. */
    public const IGNORED = [
        'CSRF token values (`name="_token" value="…"`, `<meta name="csrf-token">`)',
        'Whitespace: runs of spaces/newlines are collapsed and whitespace between tags is removed (line endings, indentation)',
        'Asset version strings (`?v=NN` on CSS/JS)',
        'Sitemap `<lastmod>` dates (were Blade file times, now database update times)',
        'Order of attributes is NOT ignored; nothing random is rendered (A/B test is off, CRO widgets are picked by crc32 of the slug)',
    ];

    public function handle(): int
    {
        $a = base_path($this->argument('before'));
        $b = base_path($this->argument('after'));
        $ia = json_decode(File::get($a.'/index.json'), true);
        $ib = json_decode(File::get($b.'/index.json'), true);

        $rows = [];
        $same = 0;
        foreach ($ia as $url => $ra) {
            $rb = $ib[$url] ?? null;
            if (! $rb) {
                $rows[] = [$url, 'missing in after'];

                continue;
            }
            $problems = [];
            if ($ra['status'] !== $rb['status']) {
                $problems[] = "status {$ra['status']} → {$rb['status']}";
            }
            if (($ra['location'] ?? '') !== ($rb['location'] ?? '')) {
                $problems[] = "location {$ra['location']} → {$rb['location']}";
            }
            $ha = File::get($a.'/'.$ra['file']);
            $hb = File::exists($b.'/'.$rb['file']) ? File::get($b.'/'.$rb['file']) : '';
            if ($ra['status'] === 200) {
                $problems = array_merge($problems, self::compareHtml($ha, $hb, $url));
            }
            if ($problems) {
                $rows[] = [$url, implode('; ', $problems)];
            } else {
                $same++;
            }
        }
        foreach (array_diff(array_keys($ib), array_keys($ia)) as $url) {
            $rows[] = [$url, 'new in after (status '.$ib[$url]['status'].')'];
        }

        $md = '# '.$this->option('title')."\n\n";
        $md .= 'Compared '.count($ia).' URLs: **'.$same.' identical**, **'.count($rows)."** with differences.\n\n";
        $md .= "Ignored:\n\n".implode("\n", array_map(fn ($i) => '- '.$i, self::IGNORED))."\n\n";
        if ($rows) {
            $md .= "| URL | Difference |\n|---|---|\n";
            foreach ($rows as [$url, $p]) {
                $md .= '| `'.$url.'` | '.str_replace('|', '\\|', $p)." |\n";
            }
        }

        if ($report = $this->option('report')) {
            File::ensureDirectoryExists(dirname(base_path($report)));
            File::put(base_path($report), $md);
        }
        $this->line($md);

        return $rows ? self::FAILURE : self::SUCCESS;
    }

    public static function normalise(string $html): string
    {
        $html = preg_replace('/(name="_token"(?:\s+autocomplete="off")?\s+value=")[^"]*"/', '$1"', $html);
        $html = preg_replace('/(<meta name="csrf-token" content=")[^"]*"/', '$1"', $html);
        $html = preg_replace('/\?v=\d+/', '', $html);
        $html = preg_replace('#<lastmod>[^<]*</lastmod>#', '', $html);
        $html = str_replace("\r\n", "\n", $html);
        $html = preg_replace('/>\s+</', '><', $html);
        $html = preg_replace('/\s+/', ' ', $html);

        return trim($html);
    }

    /** @return list<string> */
    public static function compareHtml(string $a, string $b, string $url = ''): array
    {
        $na = self::normalise($a);
        $nb = self::normalise($b);
        if ($na === $nb) {
            return [];
        }

        $out = [];
        $pa = self::parts($a);
        $pb = self::parts($b);
        foreach ($pa as $key => $value) {
            if ($value !== $pb[$key]) {
                $out[] = $key.' differs'.self::firstDifference($value, $pb[$key]);
            }
        }
        if (! $out) {
            $out[] = 'markup differs'.self::firstDifference($na, $nb);
        }

        return $out;
    }

    private static function firstDifference($a, $b): string
    {
        $a = is_string($a) ? $a : json_encode($a, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $b = is_string($b) ? $b : json_encode($b, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $len = min(strlen($a), strlen($b));
        $i = 0;
        while ($i < $len && $a[$i] === $b[$i]) {
            $i++;
        }
        $from = max(0, $i - 40);

        return ' (at '.$i.': `'.str_replace('`', "'", substr($a, $from, 110)).'` vs `'.str_replace('`', "'", substr($b, $from, 110)).'`)';
    }

    /** The parts the brief asks to compare. */
    public static function parts(string $html): array
    {
        $html = self::normalise($html);
        preg_match('#<title>(.*?)</title>#s', $html, $t);
        preg_match_all('#<meta (?:name|property)="([^"]+)" content="([^"]*)"#', $html, $m, PREG_SET_ORDER);
        preg_match('#<link rel="canonical" href="([^"]*)"#', $html, $c);
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $j);
        preg_match_all('#<h([1-6])[^>]*>(.*?)</h\1>#s', $html, $h, PREG_SET_ORDER);
        preg_match_all('#<a\s[^>]*href="([^"]*)"#', $html, $l);
        preg_match_all('#<img\s[^>]*src="([^"]*)"#', $html, $i);
        preg_match_all('#<(form|input|select|textarea|option)\b([^>]*)>#', $html, $f, PREG_SET_ORDER);

        return [
            'title' => $t[1] ?? '',
            'meta' => array_map(fn ($x) => $x[1].'='.$x[2], $m),
            'canonical' => $c[1] ?? '',
            'json-ld' => array_map(fn ($x) => json_decode(html_entity_decode($x), true) ?? $x, $j[1]),
            'headings' => array_map(fn ($x) => 'h'.$x[1].':'.trim(strip_tags($x[2])), $h),
            'links' => $l[1],
            'images' => $i[1],
            'forms' => array_map(fn ($x) => $x[1].' '.trim($x[2]), $f),
            'text' => trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('#<(script|style|template)\b.*?</\1>#s', '', $html)))),
        ];
    }
}
