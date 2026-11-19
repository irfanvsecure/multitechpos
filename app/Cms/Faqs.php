<?php

namespace App\Cms;

use App\Models\Faq;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class Faqs
{
    /** @return list<array{q: string, a: string}> active FAQs of a page, placeholders filled in */
    public static function forPage(?Page $page): array
    {
        if (! $page) {
            return [];
        }

        return Cache::remember(Cms::cacheKey('faqs:'.$page->id), 3600, fn () => Faq::query()
            ->where('page_id', $page->id)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn ($f) => ['q' => Placeholders::renderText($f->question), 'a' => Placeholders::renderText($f->answer)])
            ->all());
    }
}
