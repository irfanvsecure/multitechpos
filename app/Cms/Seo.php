<?php

namespace App\Cms;

use Illuminate\Database\Eloquent\Model;

class Seo
{
    public const FIELDS = ['title', 'description', 'focus_keyword', 'canonical', 'robots', 'og_title', 'og_description', 'og_image', 'og_type', 'twitter_card', 'exclude_from_sitemap'];

    /** The values the layout needs for a page or post. */
    public static function for(Model $owner, string $fallbackTitle): array
    {
        $m = $owner->relationLoaded('seo') ? $owner->seo : $owner->seo()->first();
        $out = ['title' => $fallbackTitle];
        foreach (self::FIELDS as $field) {
            $out[$field] = $m?->{$field};
        }
        $out['title'] = filled($m?->title) ? $m->title : $fallbackTitle;

        return $out;
    }
}
