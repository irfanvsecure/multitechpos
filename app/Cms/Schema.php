<?php

namespace App\Cms;

/** JSON-LD helpers. */
class Schema
{
    public static function encode(mixed $data): string
    {
        // same flags as the existing schema partials; "</" is escaped so a value can never close the script tag
        return str_replace('</', '<\/', json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
