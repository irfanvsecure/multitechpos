<?php

if (! function_exists('setting')) {
    /** A site setting (contact details, stats ...) from the settings table. */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Cms\Settings::get($key, $default);
    }
}
