<?php

namespace App\Cms;

use App\Models\Download;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Cache;

/**
 * Placeholders in stored HTML, replaced when a page is shown. Never run as code.
 *
 *   {{base}} {{phone}} {{phone_raw}} {{email}} {{whatsapp}} {{wa_link}} {{address}} {{hours}} {{service_areas}}
 *   {{plan:slug}} / {{plan:slug.field}}         pricing_plans (name by default)
 *   {{download:slug}} / {{download:slug.field}} downloads (download_url by default)
 *   {{setting:key}}                             any setting (stats, legal name ...)
 *   {{embed:N}}                                 the N-th component embedded in an html section (1-based)
 */
class Placeholders
{
    public const SIMPLE = [
        'base' => null,
        'phone' => 'phone_display',
        'phone_raw' => 'phone',
        'email' => 'email',
        'whatsapp' => 'whatsapp_number',
        'wa_link' => 'wa_link',
        'address' => 'address',
        'hours' => 'hours',
        'service_areas' => 'service_areas',
    ];

    public const PLAN_FIELDS = ['name', 'audience', 'description', 'price', 'price_period', 'badge', 'cta_label'];

    public const DOWNLOAD_FIELDS = ['download_url', 'name', 'description', 'platform', 'version', 'file_size'];

    private const PATTERN = '/\{\{(.*?)\}\}/s';

    /** Names of every allowed placeholder form, for the MCP instructions and errors. */
    public static function help(): string
    {
        return '{{base}}, {{phone}}, {{phone_raw}}, {{email}}, {{whatsapp}}, {{wa_link}}, {{address}}, {{hours}}, '
            .'{{service_areas}}, {{plan:slug}}, {{plan:slug.field}}, {{download:slug}}, {{download:slug.field}}, '
            .'{{setting:key}}, {{embed:N}}';
    }

    /**
     * Problems with the placeholders in $text (empty = fine).
     *
     * @return list<string>
     */
    public static function validate(?string $text, ?int $embedCount = null): array
    {
        $errors = [];
        if ($text === null || $text === '') {
            return $errors;
        }
        if (str_contains($text, '{!!') || str_contains($text, '!!}')) {
            $errors[] = 'Raw Blade output tags {!! !!} are not allowed.';
        }
        preg_match_all(self::PATTERN, $text, $m);
        foreach ($m[1] as $inner) {
            $error = self::check(trim($inner), $embedCount);
            if ($error) {
                $errors[] = $error;
            }
        }

        return array_values(array_unique($errors));
    }

    private static function check(string $inner, ?int $embedCount): ?string
    {
        if (array_key_exists($inner, self::SIMPLE)) {
            return null;
        }
        if (! preg_match('/^(plan|download|setting|embed):([a-z0-9_\-]+)(?:\.([a-z_]+))?$/', $inner, $p)) {
            return "Unknown placeholder {{{$inner}}}. Allowed: ".self::help();
        }
        [, $kind, $name] = $p;
        $field = $p[3] ?? null;

        return match ($kind) {
            'setting' => array_key_exists($name, Settings::all()) && $field === null ? null : "Unknown setting in {{{$inner}}}.",
            'plan' => ! self::plans()->has($name) ? "No pricing plan with slug '$name'."
                : ($field && ! in_array($field, self::PLAN_FIELDS, true) ? "Unknown plan field '$field'. Allowed: ".implode(', ', self::PLAN_FIELDS) : null),
            'download' => ! self::downloads()->has($name) ? "No download with slug '$name'."
                : ($field && ! in_array($field, self::DOWNLOAD_FIELDS, true) ? "Unknown download field '$field'. Allowed: ".implode(', ', self::DOWNLOAD_FIELDS) : null),
            'embed' => ! ctype_digit($name) || (int) $name < 1 || ($embedCount !== null && (int) $name > $embedCount)
                ? "{{{$inner}}} has no matching embedded component (this section has ".(int) $embedCount.').' : null,
        };
    }

    /**
     * Replace placeholders. HTML context: values are HTML-escaped. $embed renders {{embed:N}}.
     */
    public static function render(?string $text, string $base = '', ?callable $embed = null, bool $escape = true): string
    {
        if ($text === null || ! str_contains($text, '{{')) {
            return (string) $text;
        }
        $settings = Settings::all();

        return preg_replace_callback(self::PATTERN, function ($m) use ($settings, $base, $embed, $escape) {
            $inner = trim($m[1]);
            $out = function ($value) use ($escape) {
                $value = is_array($value) ? implode(', ', $value) : (string) $value;

                return $escape ? e($value) : $value;
            };

            if ($inner === 'base') {
                return $base;
            }
            if (array_key_exists($inner, self::SIMPLE)) {
                return $out($settings[self::SIMPLE[$inner]] ?? '');
            }
            if (! preg_match('/^(plan|download|setting|embed):([a-z0-9_\-]+)(?:\.([a-z_]+))?$/', $inner, $p)) {
                return ''; // unknown placeholders never reach the page
            }
            [, $kind, $name] = $p;
            $field = $p[3] ?? null;

            return match ($kind) {
                'setting' => $out($settings[$name] ?? ''),
                'plan' => $out(self::plans()->get($name)?->{in_array($field, self::PLAN_FIELDS, true) ? $field : 'name'} ?? ''),
                'download' => $out(self::downloads()->get($name)?->{in_array($field, self::DOWNLOAD_FIELDS, true) ? $field : 'download_url'} ?? ''),
                'embed' => $embed ? $embed((int) $name) : '',
            };
        }, $text);
    }

    /** Plain-text context (FAQ answers, schema): no escaping, base left empty. */
    public static function renderText(?string $text): string
    {
        return self::render($text, '', null, false);
    }

    private static function plans()
    {
        return Cache::remember(Cms::cacheKey('plans'), 3600, fn () => PricingPlan::query()->get()->keyBy('slug'));
    }

    private static function downloads()
    {
        return Cache::remember(Cms::cacheKey('downloads'), 3600, fn () => Download::query()->get()->keyBy('slug'));
    }
}
