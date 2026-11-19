<?php

namespace App\Cms;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class Sitemap
{
    /** @return list<array{path: string, lastmod: string, priority: string}> sorted by URL */
    public static function entries(): array
    {
        return Cache::remember(Cms::cacheKey('sitemap'), 3600, function () {
            $urls = [];
            foreach (Page::query()->where('status', 'published')->with('seo')->get() as $page) {
                if (self::excluded($page->seo)) {
                    continue;
                }
                $urls[] = [
                    'path' => $page->path,
                    'lastmod' => $page->updated_at?->format('Y-m-d') ?? date('Y-m-d'),
                    'priority' => $page->slug === '' ? '1.0' : (str_contains((string) $page->body_class, 'page-post') ? '0.6' : '0.8'),
                ];
            }
            foreach (Post::query()->live()->with('seo')->get() as $post) {
                if (! self::excluded($post->seo)) {
                    $urls[] = ['path' => $post->path, 'lastmod' => ($post->updated_at ?? $post->published_at)->format('Y-m-d'), 'priority' => '0.6'];
                }
            }
            usort($urls, fn ($a, $b) => strcmp($a['path'], $b['path']));

            return $urls;
        });
    }

    /** Left out: "exclude from sitemap" ticked, or a noindex robots value. */
    public static function excluded($seo): bool
    {
        return $seo && ($seo->exclude_from_sitemap || str_contains(strtolower((string) $seo->robots), 'noindex'));
    }
}
