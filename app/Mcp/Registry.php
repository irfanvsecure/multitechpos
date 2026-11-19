<?php

namespace App\Mcp;

/** All tool sets. Each returns a list of Tool. */
class Registry
{
    public const SETS = [
        Tools\SiteTools::class,
        Tools\PageTools::class,
        Tools\HeadingTools::class,
        Tools\LinkTools::class,
        Tools\SeoTools::class,
        Tools\SchemaTools::class,
        Tools\RedirectTools::class,
        Tools\BlogTools::class,
        Tools\ImageTools::class,
        Tools\BusinessTools::class,
        Tools\LeadTools::class,
        Tools\TrashTools::class,
    ];

    /** @return list<Tool> */
    public static function tools(): array
    {
        $all = [];
        foreach (self::SETS as $set) {
            foreach ((new $set)->tools() as $tool) {
                $all[] = $tool;
            }
        }

        return $all;
    }
}
