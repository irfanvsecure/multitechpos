<?php

namespace App\Cms;

/**
 * Cleans HTML sent through the MCP without re-serialising it: only the offending
 * tags and attributes are cut out, everything else stays byte-for-byte the same.
 * That keeps classes, inline styles, SVG, data-*, aria-*, Alpine x-/@/: attributes.
 *
 * Removed: <script>, <style>, <form> and form fields, <object>/<embed>/<applet>/<base>/<meta>/<link>/<frame>,
 * iframes not from YouTube / Google Maps, on* event attributes, javascript:/vbscript:/data: URLs,
 * srcdoc, CSS expression(), PHP tags.
 */
class Sanitizer
{
    /** Removed together with everything inside them. */
    private const DROP_WITH_CONTENT = ['script', 'style', 'form', 'textarea', 'select', 'object', 'applet', 'frameset', 'noembed', 'xmp'];

    /** Removed (tag only, no content or void). */
    private const DROP_TAG = ['input', 'embed', 'base', 'meta', 'link', 'frame', 'option', 'optgroup', 'keygen', 'param', 'html', 'head', 'body'];

    private const URL_ATTRS = ['href', 'src', 'action', 'formaction', 'xlink:href', 'data', 'poster', 'background', 'lowsrc', 'dynsrc', 'cite', 'longdesc', 'ping', 'srcset'];

    private const IFRAME_HOSTS = ['www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com', 'maps.google.com', 'www.google.com'];

    private const TAG = '/<!--.*?-->|<\?.*?(?:\?>|$)|<(\/?)([a-zA-Z][a-zA-Z0-9:\-]*)((?:\s+[^\s\/>"\'=]+(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s"\'>]+))?)*)\s*(\/?)>/s';

    private const ATTR = '/(\s+)([^\s\/>"\'=]+)(?:(\s*=\s*)("[^"]*"|\'[^\']*\'|[^\s"\'>]+))?/';

    /**
     * @return array{0: string, 1: list<string>} [clean html, what was removed]
     */
    public static function clean(?string $html): array
    {
        $html = (string) $html;
        $removed = [];
        $out = '';
        $pos = 0;
        $len = strlen($html);

        while ($pos < $len && preg_match(self::TAG, $html, $m, PREG_OFFSET_CAPTURE, $pos)) {
            $start = $m[0][1];
            $whole = $m[0][0];
            $out .= substr($html, $pos, $start - $pos);
            $pos = $start + strlen($whole);

            if (str_starts_with($whole, '<?')) {
                $removed[] = 'PHP tag';

                continue;
            }
            if (str_starts_with($whole, '<!--')) {
                $out .= $whole;

                continue;
            }

            $closing = $m[1][0] === '/';
            $name = strtolower($m[2][0]);
            $attrs = $m[3][0] ?? '';

            if (in_array($name, self::DROP_WITH_CONTENT, true)) {
                if (! $closing) {
                    $removed[] = "<$name> element";
                    if (! preg_match('#</'.preg_quote($name, '#').'\s*>#i', $html, $close, PREG_OFFSET_CAPTURE, $pos)) {
                        $pos = $len; // unclosed: drop the rest

                        continue;
                    }
                    $pos = $close[0][1] + strlen($close[0][0]);
                } else {
                    $removed[] = "stray </$name>";
                }

                continue;
            }
            if (in_array($name, self::DROP_TAG, true)) {
                if (! $closing) {
                    $removed[] = "<$name> tag";
                }

                continue;
            }
            if ($name === 'iframe' && ! $closing && ! self::allowedIframe($attrs)) {
                $removed[] = '<iframe> from a source that is not YouTube, Vimeo or Google Maps';
                if (preg_match('#</iframe\s*>#i', $html, $close, PREG_OFFSET_CAPTURE, $pos)) {
                    $pos = $close[0][1] + strlen($close[0][0]);
                }

                continue;
            }

            if ($closing || $attrs === '') {
                $out .= $whole;

                continue;
            }

            [$cleanAttrs, $attrRemoved] = self::cleanAttributes($attrs, $name);
            if (! $attrRemoved) {
                $out .= $whole; // untouched: keep it byte-identical
            } else {
                $removed = array_merge($removed, $attrRemoved);
                $out .= '<'.$m[2][0].$cleanAttrs.($m[4][0] === '/' ? ' /' : '').'>';
            }
        }
        $out .= substr($html, $pos);

        return [$out, array_values(array_unique($removed))];
    }

    /** @return array{0: string, 1: list<string>} */
    private static function cleanAttributes(string $attrs, string $tag): array
    {
        $removed = [];
        $clean = preg_replace_callback(self::ATTR, function ($a) use (&$removed, $tag) {
            $name = strtolower($a[2]);
            $raw = $a[4] ?? '';
            $value = html_entity_decode(trim($raw, '"\''), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if (preg_match('/^on[a-z]+$/', $name)) {
                $removed[] = "$name attribute on <$tag>";

                return '';
            }
            if (in_array($name, ['srcdoc', 'formaction'], true)) {
                $removed[] = "$name attribute on <$tag>";

                return '';
            }
            if (in_array($name, self::URL_ATTRS, true) && self::dangerousUrl($value, $name)) {
                $removed[] = "unsafe URL in $name on <$tag>";

                return '';
            }
            if ($name === 'style' && preg_match('/expression\s*\(|javascript:|vbscript:|-moz-binding|behavior\s*:/i', $value)) {
                $removed[] = "unsafe CSS in style on <$tag>";

                return '';
            }

            return $a[0];
        }, $attrs);

        return [$clean, $removed];
    }

    private static function dangerousUrl(string $value, string $attr): bool
    {
        $v = strtolower(preg_replace('/[\x00-\x20]+/', '', $value));
        if ($attr === 'srcset') {
            return (bool) preg_match('/(^|,)(javascript|vbscript|data):/', $v);
        }
        if (str_starts_with($v, 'javascript:') || str_starts_with($v, 'vbscript:') || str_starts_with($v, 'livescript:')) {
            return true;
        }
        if (str_starts_with($v, 'data:')) {
            return ! ($attr === 'src' && preg_match('#^data:image/(png|jpe?g|gif|webp);#', $v));
        }

        return false;
    }

    private static function allowedIframe(string $attrs): bool
    {
        if (! preg_match('/\ssrc\s*=\s*("([^"]*)"|\'([^\']*)\')/i', $attrs, $s)) {
            return false;
        }
        $src = html_entity_decode($s[2] !== '' ? $s[2] : ($s[3] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $host = strtolower((string) parse_url($src, PHP_URL_HOST));
        if (! str_starts_with($src, 'https://') || ! in_array($host, self::IFRAME_HOSTS, true)) {
            return false;
        }
        if ($host === 'www.google.com') {
            return str_starts_with((string) parse_url($src, PHP_URL_PATH), '/maps');
        }

        return true;
    }

    /** Count of <h1> elements in a piece of HTML. */
    public static function h1Count(?string $html): int
    {
        return preg_match_all('/<h1[\s>]/i', (string) $html);
    }
}
