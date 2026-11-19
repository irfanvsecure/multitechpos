<?php

namespace App\Cms;

use App\Models\ClientLogo;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

/** Cached reads of shared content used by the partials. */
class Content
{
    public static function clientLogos()
    {
        return self::cached('client_logos', fn () => ClientLogo::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get());
    }

    public static function testimonials(): array
    {
        return self::cached('testimonials', fn () => Testimonial::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn ($t) => ['quote' => $t->quote, 'name' => $t->name, 'business' => $t->business, 'photo' => $t->photo])->all(), []);
    }

    /** Footer icons for the social_links setting. */
    public static function socialLinks(): array
    {
        $known = [
            'facebook.com' => ['Facebook', 'fab fa-facebook-f'], 'linkedin.com' => ['LinkedIn', 'fab fa-linkedin-in'],
            'instagram.com' => ['Instagram', 'fab fa-instagram'], 'youtube.com' => ['YouTube', 'fab fa-youtube'],
            'tiktok.com' => ['TikTok', 'fab fa-tiktok'], 'x.com' => ['X', 'fab fa-x-twitter'], 'twitter.com' => ['X', 'fab fa-x-twitter'],
        ];
        $out = [];
        foreach ((array) Settings::get('social_links', []) as $url) {
            $host = preg_replace('/^www\./', '', (string) parse_url($url, PHP_URL_HOST));
            [$label, $icon] = $known[$host] ?? [ucfirst(explode('.', $host)[0] ?? 'Link'), 'fas fa-link'];
            $out[] = ['url' => $url, 'label' => $label, 'icon' => $icon];
        }

        return $out;
    }

    private static function cached(string $key, \Closure $fn, $fallback = null)
    {
        try {
            return Cache::remember(Cms::cacheKey($key), 3600, $fn);
        } catch (\Throwable) {
            return $fallback ?? collect();
        }
    }
}
