<?php

// Site-wide settings for the MultiTech POS website.
return [
    'name' => 'MultiTech POS',
    'legal_name' => 'MultiTech POS',
    'phone' => '+971588979925',
    'phone_display' => '+971 58 897 9925',
    'email' => 'support@multitechpos.com',
    'whatsapp' => 'http://wa.me/+971588979925',
    'address' => [
        'street' => '16 Al Isayfir 2 St - Musaffah - M33',
        'city' => 'Abu Dhabi',
        'country' => 'AE',
    ],
    'logo' => '/wp-content/uploads/2025/12/logo4.png',
    'social' => [
        'https://www.facebook.com/multitechposale/',
        'https://www.linkedin.com/company/multitechpos/',
    ],

    // Fetch images missing from storage/site-assets from the old WordPress site (local development only).
    'asset_fallback' => (bool) env('ASSET_FALLBACK', false),

    // Where "Book a Free Demo" / quote form requests are emailed.
    'lead_email' => env('LEAD_EMAIL', 'support@multitechpos.com'),

    // Tracking (leave empty to disable). Example: GTM-XXXXXXX / G-XXXXXXXXXX
    'gtm_id' => env('GTM_ID'),
    'ga_id' => env('GA_MEASUREMENT_ID'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
];
