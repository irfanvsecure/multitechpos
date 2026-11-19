<?php

namespace App\Cms;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Site settings: one place for contact details, stats and other site-wide values.
 * Values are cached and copied into config('site.*') on boot, so every partial that
 * reads config('site.phone') etc. shows the value stored here.
 */
class Settings
{
    /**
     * Every known setting: key => [default, group, label, type].
     * Defaults are the values the site showed before the move to the database.
     */
    public const DEFINITIONS = [
        'site_name' => ['MultiTech POS', 'company', 'Site / brand name', 'string'],
        'legal_name' => ['Micro Aegis International Technologies LLC SPC', 'company', 'Legal company name (copyright)', 'string'],
        'phone' => ['+971588979925', 'contact', 'Phone, raw (tel: links, schema)', 'string'],
        'phone_display' => ['+971 58 897 9925', 'contact', 'Phone as shown on the page', 'string'],
        'email' => ['support@multitechpos.com', 'contact', 'Public email', 'string'],
        'whatsapp_number' => ['971588979925', 'contact', 'WhatsApp number, digits only', 'string'],
        'wa_link' => ['https://wa.me/971588979925?text=Hi%20MultiTech%2C%20I%27d%20like%20a%20free%20POS%20demo', 'contact', 'WhatsApp chat link with the pre-filled message', 'string'],
        'address' => ['16 Al Isayfir 2 St - Musaffah - M33 - Abu Dhabi', 'contact', 'Address as shown on the page', 'string'],
        'address_street' => ['16 Al Isayfir 2 St - Musaffah - M33', 'contact', 'Street (schema)', 'string'],
        'address_city' => ['Abu Dhabi', 'contact', 'City (schema)', 'string'],
        'address_country' => ['AE', 'contact', 'Country code (schema)', 'string'],
        'hours' => ['Sat to Thu, 9 AM to 6 PM', 'contact', 'Office hours', 'string'],
        'service_areas' => ['Dubai, Abu Dhabi, Sharjah and the wider UAE', 'contact', 'Service areas', 'string'],
        'social_links' => ['["https://www.facebook.com/multitechposale/","https://www.linkedin.com/company/multitechpos/"]', 'contact', 'Social profile URLs (JSON list)', 'json'],
        'logo' => ['/images/2025/12/logo4.png', 'brand', 'Logo used in schema and as the default share image', 'string'],
        'stat_businesses' => ['500+', 'stats', 'Businesses served', 'string'],
        'stat_years' => ['15+', 'stats', 'Years of experience', 'string'],
        'stat_industries' => ['30+', 'stats', 'Industries supported', 'string'],
        'stat_support' => ['24/7', 'stats', 'Support availability', 'string'],
        'stat_setup' => ['48 hours', 'stats', 'Fastest setup time', 'string'],
        'robots_txt' => [self::ROBOTS, 'seo', 'robots.txt rules (the Sitemap line is added automatically; SITE_NOINDEX overrides everything)', 'text'],
    ];

    public const ROBOTS = "User-agent: *\nAllow: /\nDisallow: /request-demo\nDisallow: /thank-you/\nDisallow: /?s=\nDisallow: /public/\nDisallow: /index.php?item=\n\n# --- OpenAI (ChatGPT) ---\nUser-agent: OAI-SearchBot\nAllow: /\n\nUser-agent: ChatGPT-User\nAllow: /\n\nUser-agent: GPTBot\nAllow: /\n\n# --- Anthropic (Claude) ---\nUser-agent: ClaudeBot\nAllow: /\n\nUser-agent: Claude-User\nAllow: /\n\nUser-agent: Claude-SearchBot\nAllow: /\n\n# --- Perplexity ---\nUser-agent: PerplexityBot\nAllow: /\n\nUser-agent: Perplexity-User\nAllow: /\n\n# --- Google (Gemini / generative AI) ---\nUser-agent: Google-Extended\nAllow: /\n\n# --- Apple Intelligence ---\nUser-agent: Applebot-Extended\nAllow: /\n\n# --- Microsoft Copilot / Bing AI ---\nUser-agent: Bingbot\nAllow: /";

    private static ?array $memo = null;

    /** @return array<string, mixed> key => value (json settings decoded) */
    public static function all(): array
    {
        if (self::$memo !== null) {
            return self::$memo;
        }

        $values = array_map(fn ($d) => $d[3] === 'json' ? json_decode($d[0], true) : $d[0], self::DEFINITIONS);

        try {
            $stored = Cache::rememberForever(Cms::cacheKey('settings'), fn () => Setting::query()->get(['key', 'value', 'type'])
                ->mapWithKeys(fn ($s) => [$s->key => $s->type === 'json' ? json_decode((string) $s->value, true) : $s->value])
                ->all());
        } catch (\Throwable) {
            return $values; // table not migrated yet: defaults, not cached
        }

        return self::$memo = array_merge($values, $stored);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function forget(): void
    {
        self::$memo = null;
    }

    /** Copy settings into config('site.*') so existing partials read them. */
    public static function applyToConfig(): void
    {
        $s = self::all();
        config([
            'site.name' => $s['site_name'],
            'site.phone' => $s['phone'],
            'site.phone_display' => $s['phone_display'],
            'site.email' => $s['email'],
            'site.whatsapp' => $s['wa_link'],
            'site.address.street' => $s['address_street'],
            'site.address.city' => $s['address_city'],
            'site.address.country' => $s['address_country'],
            'site.logo' => $s['logo'],
            'site.social' => is_array($s['social_links']) ? $s['social_links'] : [],
        ]);
    }
}
