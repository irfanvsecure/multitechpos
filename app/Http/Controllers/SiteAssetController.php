<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class SiteAssetController extends Controller
{
    public function show(Request $request, string $path): Response
    {
        $relative = trim($path, '/');
        if (str_contains($relative, '..') || !preg_match('#^(wp-content|wp-includes)/#', $relative)) {
            abort(404);
        }

        $root = realpath(storage_path('site-assets'));
        if ($root === false) {
            abort(404);
        }

        $candidate = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, urldecode($relative));
        // Missing files may be pulled from the old WordPress site, but only when ASSET_FALLBACK=true.
        // It must stay off once this app *is* multitechpos.com, or it would call itself.
        if (!is_file($candidate) && !(config('site.asset_fallback') && $this->fetchFromOrigin($relative, $candidate))) {
            abort(404);
        }

        $full = realpath($candidate);
        if ($full === false || !str_starts_with($full, $root)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $mime = $this->mime($ext);
        $headers = [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400',
        ];

        if (in_array($ext, ['css', 'js', 'svg', 'map'], true)) {
            $body = file_get_contents($full);
            if ($body === false) {
                abort(404);
            }
            $base = rtrim($request->getBasePath(), '/');
            $body = str_replace('__MTP_BASE__', $base, $body);

            return response($body, 200, $headers);
        }

        return response()->file($full, $headers);
    }

    /**
     * Download an asset the mirror missed (e.g. lazy-loaded Elementor chunks) from the live site and cache it.
     */
    private function fetchFromOrigin(string $relative, string $candidate): bool
    {
        if (!preg_match('#\.[A-Za-z0-9]{2,5}$#', $relative)) {
            return false;
        }

        try {
            $response = Http::timeout(20)->withoutVerifying()->get('https://multitechpos.com/' . $relative);
        } catch (\Throwable) {
            return false;
        }

        $body = $response->body();
        if (!$response->successful() || $body === '' || preg_match('#^\s*<(!doctype|html)#i', $body)) {
            return false;
        }

        if (preg_match('#\.(css|js|svg)$#i', $relative)) {
            $body = preg_replace('#(?:https?:)?(?:\\\\?/){2}(?:www\.)?multitechpos\.com#i', '__MTP_BASE__', $body);
        }

        if (!is_dir(dirname($candidate))) {
            mkdir(dirname($candidate), 0777, true);
        }

        return file_put_contents($candidate, $body) !== false;
    }

    private function mime(string $ext): string
    {
        return match ($ext) {
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'otf' => 'font/otf',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'map', 'json' => 'application/json',
            default => 'application/octet-stream',
        };
    }
}
