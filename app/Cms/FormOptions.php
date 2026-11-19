<?php

namespace App\Cms;

use App\Models\FormOption;
use Illuminate\Support\Facades\Cache;

/**
 * Option lists of the code forms (field labels stay in code):
 *   business_types   hero demo form, Setup Builder step 1, home business picker (meta: label, icon, tile, url, text, points)
 *   builder_branches / builder_counters   Setup Builder step 2
 *   quote_service    "Interested Service" select of the quote forms
 */
class FormOptions
{
    public const FORMS = ['business_types', 'builder_branches', 'builder_counters', 'quote_service'];

    /** @return list<array> each: ['name' => value, 'label' => ..., + meta] */
    public static function list(string $form): array
    {
        try {
            return Cache::remember(Cms::cacheKey('form_options:'.$form), 3600, fn () => FormOption::query()
                ->where('form', $form)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
                ->map(fn ($o) => ['name' => $o->value, 'label' => $o->label ?? $o->value] + ($o->meta ?? []))
                ->all());
        } catch (\Throwable) {
            return [];
        }
    }

    /** Just the values. */
    public static function values(string $form): array
    {
        return array_column(self::list($form), 'name');
    }
}
