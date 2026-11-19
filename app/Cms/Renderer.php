<?php

namespace App\Cms;

use App\Models\Page;
use App\Models\PageSection;

/**
 * Turns stored sections into HTML. Must be called from inside a Blade view that is
 * rendering (cms/page), so components share its @push / @section state like @include did.
 */
class Renderer
{
    public static function page(Page $page, string $base, string $current): string
    {
        $html = '';
        foreach ($page->sections as $section) {
            if ($section->is_visible) {
                $html .= self::section($section, $page, $base, $current)."\n";
            }
        }

        return $html;
    }

    public static function section(PageSection $section, ?Page $page, string $base, string $current): string
    {
        if ($section->type === 'component') {
            return self::component((string) $section->component, $section->attrs ?? [], $page, $base, $current);
        }

        $embeds = $section->embeds ?? [];

        return Placeholders::render($section->content, $base, function (int $n) use ($embeds, $page, $base, $current) {
            $embed = $embeds[$n - 1] ?? null;

            return $embed ? self::component($embed['component'], $embed['attrs'] ?? [], $page, $base, $current) : '';
        });
    }

    public static function component(string $name, array $attrs, ?Page $page, string $base, string $current): string
    {
        if (! Components::exists($name)) {
            return '';
        }

        return view(Components::view($name), self::resolve($attrs, $base, $current) + [
            'cmsPage' => $page, 'base' => $base, 'current' => $current,
        ])->render();
    }

    /** "$current" in attrs means this page's path; placeholders in strings are filled (not escaped: the partial escapes). */
    private static function resolve(array $attrs, string $base, string $current): array
    {
        array_walk_recursive($attrs, function (&$value) use ($base, $current) {
            if ($value === '$current') {
                $value = $current;
            } elseif (is_string($value) && str_contains($value, '{{')) {
                $value = Placeholders::render($value, $base, null, false);
            }
        });

        return $attrs;
    }

    /** Number of H1 elements the page renders (html sections + components). */
    public static function h1Count(iterable $sections): int
    {
        $count = 0;
        foreach ($sections as $s) {
            if (! $s->is_visible) {
                continue;
            }
            if ($s->type === 'component') {
                $count += Components::h1((string) $s->component);
            } else {
                $count += Sanitizer::h1Count($s->content);
                foreach ($s->embeds ?? [] as $e) {
                    $count += Components::h1($e['component'] ?? '');
                }
            }
        }

        return $count;
    }
}
