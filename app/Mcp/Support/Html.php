<?php

namespace App\Mcp\Support;

/**
 * HTML helpers that edit text nodes only and leave every tag/attribute untouched.
 */
class Html
{
    private const TOKEN = '/(<!--.*?-->|<[^>]+>)/s';

    public static function text(?string $html): string
    {
        $html = preg_replace('#<(script|style|template)\b.*?</\1>#is', ' ', (string) $html);
        $html = preg_replace('/<[^>]+>/', ' ', $html);

        return trim(preg_replace('/\s+/u', ' ', html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    public static function words(string $text): int
    {
        return $text === '' ? 0 : count(preg_split('/\s+/u', trim($text)));
    }

    /** @return list<array{level:int,text:string,html:string}> */
    public static function headings(string $html): array
    {
        preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#is', $html, $m, PREG_SET_ORDER);

        return array_map(fn ($x) => ['level' => (int) $x[1], 'text' => self::text($x[2]), 'html' => $x[2]], $m);
    }

    /** @return list<array{href:string,path:?string,anchor:string,internal:bool}> */
    public static function links(string $html): array
    {
        preg_match_all('#<a\b([^>]*)>(.*?)</a>#is', $html, $m, PREG_SET_ORDER);
        $out = [];
        foreach ($m as $x) {
            if (! preg_match('/\shref\s*=\s*"([^"]*)"/i', $x[1], $h)) {
                continue;
            }
            $href = html_entity_decode($h[1], ENT_QUOTES | ENT_HTML5);
            $path = self::internalPath($href);
            $out[] = ['href' => $href, 'path' => $path, 'anchor' => self::text($x[2]), 'internal' => $path !== null];
        }

        return $out;
    }

    /** "/contact/" for internal links ({{base}}/contact/, https://this-site/contact/, /contact/#x); null for external/tel/mailto/#. */
    public static function internalPath(string $href): ?string
    {
        $href = trim(str_replace(['{{base}}', '{{ $base }}'], '', $href));
        if ($href === '' || $href[0] === '#' || preg_match('#^(tel|mailto|sms|whatsapp|javascript|data):#i', $href) || str_contains($href, '{{')) {
            return null;
        }
        if (preg_match('#^(https?:)?//#i', $href)) {
            $host = strtolower((string) parse_url($href, PHP_URL_HOST));
            $own = array_filter([strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST)), strtolower(request()->getHost()), 'multitechpos.com', 'www.multitechpos.com']);
            if (! in_array($host, $own, true)) {
                return null;
            }
            $href = (string) (parse_url($href, PHP_URL_PATH) ?: '/');
        }
        $path = (string) parse_url($href, PHP_URL_PATH);

        return $path === '' ? '/' : $path;
    }

    public static function samePath(?string $a, ?string $b): bool
    {
        $n = fn ($p) => $p === null ? null : rtrim(strtolower($p), '/').'/';

        return $a !== null && $n($a) === $n($b);
    }

    /**
     * Replace text in text nodes only (never inside tags, attributes, scripts).
     *
     * @return array{0:string,1:int}
     */
    public static function replaceText(string $html, string $find, string $replace, bool $caseSensitive = true, int $limit = -1): array
    {
        $parts = preg_split(self::TOKEN, $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $count = 0;
        $skip = null;
        $pattern = '/'.preg_quote($find, '/').'/u'.($caseSensitive ? '' : 'i');
        foreach ($parts as $i => $part) {
            if ($i % 2 === 1) {
                if ($skip === null && preg_match('#^<(script|style)\b#i', $part, $t)) {
                    $skip = strtolower($t[1]);
                } elseif ($skip && preg_match('#^</'.$skip.'\b#i', $part)) {
                    $skip = null;
                }

                continue;
            }
            if ($skip || $part === '' || ($limit >= 0 && $count >= $limit)) {
                continue;
            }
            $left = $limit < 0 ? -1 : $limit - $count;
            $parts[$i] = preg_replace($pattern, str_replace(['\\', '$'], ['\\\\', '\\$'], $replace), $part, $left, $n);
            $count += $n;
        }

        return [implode('', $parts), $count];
    }

    /** Count of text-node matches (for dry runs). */
    public static function countText(string $html, string $find, bool $caseSensitive = true): int
    {
        return self::replaceText($html, $find, $find, $caseSensitive)[1];
    }

    /**
     * Wrap the first exact occurrence of $anchor (in text outside links) in <a href>.
     *
     * @return array{0:?string,1:string} [new html or null, reason: linked|not_found|already_linked]
     */
    public static function linkFirst(string $html, string $anchor, string $href, array $attrs = []): array
    {
        $parts = preg_split(self::TOKEN, $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $inLink = 0;
        $skip = null;
        $seenInLink = false;
        foreach ($parts as $i => $part) {
            if ($i % 2 === 1) {
                if (preg_match('#^<a\b#i', $part)) {
                    $inLink++;
                } elseif (preg_match('#^</a\s*>#i', $part)) {
                    $inLink = max(0, $inLink - 1);
                } elseif ($skip === null && preg_match('#^<(script|style|h1)\b#i', $part, $t)) {
                    $skip = strtolower($t[1]);
                } elseif ($skip && preg_match('#^</'.$skip.'\b#i', $part)) {
                    $skip = null;
                }

                continue;
            }
            $decoded = $part;
            $pos = mb_strpos($decoded, $anchor);
            if ($pos === false) {
                // the anchor may be written with entities (&amp;)
                $enc = htmlspecialchars($anchor, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
                $pos = mb_strpos($part, $enc);
                $anchorRaw = $enc;
            } else {
                $anchorRaw = $anchor;
            }
            if ($pos === false) {
                continue;
            }
            if ($inLink || $skip) {
                $seenInLink = $seenInLink || $inLink > 0;

                continue;
            }
            $extra = '';
            foreach ($attrs as $k => $v) {
                $extra .= ' '.$k.'="'.e($v).'"';
            }
            $parts[$i] = mb_substr($part, 0, $pos).'<a href="'.e($href).'"'.$extra.'>'.$anchorRaw.'</a>'.mb_substr($part, $pos + mb_strlen($anchorRaw));

            return [implode('', $parts), 'linked'];
        }

        return [null, $seenInLink ? 'already_linked' : 'not_found'];
    }

    /**
     * Remove links to $path (keeping their text). Optional anchor filter.
     *
     * @return array{0:string,1:int}
     */
    public static function unlink(string $html, string $path, ?string $anchor = null): array
    {
        $count = 0;
        $html = preg_replace_callback('#<a\b([^>]*)>(.*?)</a>#is', function ($m) use ($path, $anchor, &$count) {
            if (! preg_match('/\shref\s*=\s*"([^"]*)"/i', $m[1], $h)) {
                return $m[0];
            }
            $target = self::internalPath(html_entity_decode($h[1])) ?? html_entity_decode($h[1]);
            if (! (self::samePath($target, $path) || $target === $path) || ($anchor !== null && self::text($m[2]) !== $anchor)) {
                return $m[0];
            }
            $count++;

            return $m[2];
        }, $html);

        return [$html, $count];
    }

    /**
     * Point links from $from to $to (href only; anchor text kept). Optional anchor filter.
     *
     * @return array{0:string,1:int}
     */
    public static function relink(string $html, string $from, string $to, ?string $anchor = null): array
    {
        $count = 0;
        $html = preg_replace_callback('#<a\b([^>]*)>(.*?)</a>#is', function ($m) use ($from, $to, $anchor, &$count) {
            if (! preg_match('/(\shref\s*=\s*")([^"]*)(")/i', $m[1], $h, PREG_OFFSET_CAPTURE)) {
                return $m[0];
            }
            $raw = html_entity_decode($h[2][0]);
            $target = self::internalPath($raw) ?? $raw;
            $fromPath = self::internalPath($from) ?? $from;
            $match = self::samePath($target, $fromPath) || $raw === $from;
            if (! $match || ($anchor !== null && self::text($m[2]) !== $anchor)) {
                return $m[0];
            }
            $count++;
            $newHref = self::internalPath($to) !== null && ! preg_match('#^https?://#', $to) ? '{{base}}'.self::internalPath($to) : $to;
            $attrs = substr_replace($m[1], e($newHref), $h[2][1], strlen($h[2][0]));

            return '<a'.$attrs.'>'.$m[2].'</a>';
        }, $html);

        return [$html, $count];
    }
}
