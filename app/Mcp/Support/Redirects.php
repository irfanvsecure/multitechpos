<?php

namespace App\Mcp\Support;

use App\Mcp\ToolError;
use App\Models\Redirect;

class Redirects
{
    public static function normalisePath(string $path): string
    {
        $path = trim($path);
        if (preg_match('#^https?://#i', $path)) {
            $q = parse_url($path, PHP_URL_QUERY);
            $path = (parse_url($path, PHP_URL_PATH) ?: '/').($q ? '?'.$q : '');
        }

        return '/'.ltrim($path, '/');
    }

    /** Create/replace a redirect. $to null with 410. */
    public static function save(string $from, ?string $to, int $status = 301, ?string $note = null): Redirect
    {
        $from = self::normalisePath($from);
        if (! in_array($status, [301, 302, 410], true)) {
            throw new ToolError('status_code must be 301, 302 or 410.');
        }
        if ($status !== 410) {
            if (! $to) {
                throw new ToolError('to_url is required for 301/302.');
            }
            if (! str_starts_with($to, '/') && ! preg_match('#^https://#i', $to)) {
                throw new ToolError('to_url must be a path (/page/) or an https:// URL.');
            }
            if (str_starts_with($to, '/') && rtrim(strtolower(strtok($to, '#')), '/') === rtrim(strtolower($from), '/')) {
                throw new ToolError('A redirect cannot point to itself.');
            }
            // no loops: the target must not redirect back
            if (str_starts_with($to, '/') && Redirect::query()->where('from_path', $to)->where('to_url', $from)->exists()) {
                throw new ToolError("$to already redirects to $from; that would be a loop.");
            }
        } else {
            $to = null;
        }

        $r = Redirect::withTrashed()->firstOrNew(['from_path' => $from]);
        $r->fill(['to_url' => $to, 'status_code' => $status, 'note' => $note]);
        $r->deleted_at = null;
        $r->save();

        return $r;
    }

    /** A page/post moved: 301 old → new, and existing redirects to the old URL now go straight to the new one. */
    public static function moved(string $from, string $to, string $note): string
    {
        $from = self::normalisePath($from);
        Redirect::query()->where('from_path', $to)->delete(); // the new URL is a real page now
        self::save($from, $to, 301, $note);
        $chains = Redirect::query()->where('to_url', $from)->update(['to_url' => $to]);

        return "301 added: $from → $to".($chains ? " ($chains older redirects now point straight to $to)" : '');
    }
}
