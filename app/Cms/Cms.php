<?php

namespace App\Cms;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cache versioning: every content write bumps one number, which makes every
 * cached page, setting and sitemap stale at once (works with any cache store).
 */
class Cms
{
    public static function version(): int
    {
        try {
            return (int) Cache::get('cms:version', 1);
        } catch (\Throwable) {
            return 1;
        }
    }

    public static function cacheKey(string $name): string
    {
        return 'cms:'.self::version().':'.$name;
    }

    /** Clear the content caches (and Cloudflare when configured). Returns what was done. */
    public static function flush(array $urls = []): array
    {
        try {
            Cache::forever('cms:version', self::version() + 1);
        } catch (\Throwable) {
        }
        Settings::forget();
        Settings::applyToConfig();

        return ['site_cache' => 'cleared', 'cloudflare' => self::purgeCloudflare($urls)];
    }

    public static function cloudflareConfigured(): bool
    {
        return filled(config('cms.cloudflare.token')) && filled(config('cms.cloudflare.zone'));
    }

    /** Purge Cloudflare: the given URLs, or everything. Skipped silently when not configured. */
    public static function purgeCloudflare(array $urls = []): string
    {
        if (! self::cloudflareConfigured()) {
            return 'not configured';
        }

        try {
            $body = $urls ? ['files' => array_slice(array_values(array_unique($urls)), 0, 30)] : ['purge_everything' => true];
            $res = Http::withToken(config('cms.cloudflare.token'))->timeout(10)
                ->post('https://api.cloudflare.com/client/v4/zones/'.config('cms.cloudflare.zone').'/purge_cache', $body);

            return $res->successful() ? ($urls ? 'purged '.count($body['files']).' URLs' : 'purged everything') : 'failed: HTTP '.$res->status();
        } catch (\Throwable $e) {
            Log::warning('Cloudflare purge failed', ['error' => $e->getMessage()]);

            return 'failed: '.$e->getMessage();
        }
    }

    /** SITE_NOINDEX: while true every page says noindex and robots.txt disallows all. */
    public static function noindex(): bool
    {
        return (bool) config('cms.noindex');
    }
}
