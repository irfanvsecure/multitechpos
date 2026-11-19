<?php

return [
    // Pre-launch switch. While true, EVERY page outputs noindex and robots.txt disallows all,
    // whatever a page's own robots setting says. Only changeable in .env (never through the MCP).
    // NOTE: multitechpos.com is already live on this app; set SITE_NOINDEX=false there.
    'noindex' => filter_var(env('SITE_NOINDEX', true), FILTER_VALIDATE_BOOL),

    'mcp' => [
        // 64-character random secret. Empty = the MCP endpoint is switched off.
        'secret' => env('MCP_SECRET'),
        // Requests per minute per IP
        'rate_limit' => (int) env('MCP_RATE_LIMIT', 120),
        // Refuse plain http (except on local / testing)
        'https_only' => filter_var(env('MCP_HTTPS_ONLY', true), FILTER_VALIDATE_BOOL),
    ],

    // Optional: purge Cloudflare from clear_cache. Token needs only Zone > Cache Purge.
    'cloudflare' => [
        'token' => env('CLOUDFLARE_API_TOKEN'),
        'zone' => env('CLOUDFLARE_ZONE_ID'),
    ],

    // File types the MCP may never upload or host (installers, archives, scripts).
    'blocked_extensions' => ['exe', 'msi', 'apk', 'aab', 'ipa', 'zip', 'rar', '7z', 'tar', 'gz', 'tgz', 'bz2', 'xz',
        'dmg', 'pkg', 'deb', 'rpm', 'bat', 'cmd', 'com', 'scr', 'ps1', 'sh', 'jar', 'js', 'php', 'phtml', 'phar',
        'html', 'htm', 'svg', 'iso', 'bin', 'dll', 'appx', 'msix', 'vbs'],
];
