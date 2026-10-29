<?php

// Site-wide settings for the MultiTech POS website.
return [
    'name' => 'MultiTech POS',
    'legal_name' => 'MultiTech POS',
    'phone' => '+971588979925',
    'phone_display' => '+971 58 897 9925',
    'email' => 'support@multitechpos.com',
    'whatsapp' => 'https://wa.me/971588979925?text=Hi%20MultiTech%2C%20I%27d%20like%20a%20free%20POS%20demo',
    'address' => [
        'street' => '16 Al Isayfir 2 St - Musaffah - M33',
        'city' => 'Abu Dhabi',
        'country' => 'AE',
    ],
    'logo' => '/images/2025/12/logo4.png',
    'social' => [
        'https://www.facebook.com/multitechposale/',
        'https://www.linkedin.com/company/multitechpos/',
    ],

    // Where "Book a Free Demo" / quote form requests are emailed.
    'lead_email' => env('LEAD_EMAIL', 'support@multitechpos.com'),

    // Tracking (leave empty to disable). Example: GTM-XXXXXXX / G-XXXXXXXXXX
    'gtm_id' => env('GTM_ID'),
    'ga_id' => env('GA_MEASUREMENT_ID'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    // Home page A/B test of the hero headline + form button (AB_TEST=true in .env to switch on).
    // Each visitor keeps the same version for 30 days; the version is sent to analytics as "experiment_view".
    'ab_test' => env('AB_TEST', false),

    // Social proof on the home page. Only real reviews: sections stay hidden while these are empty.
    // Google rating, e.g. ['score' => 4.9, 'count' => 120, 'url' => 'https://g.page/r/...']
    'google_rating' => null,

    // Customer testimonials, e.g.
    // ['quote' => 'Billing is twice as fast...', 'name' => 'Ahmed Khan', 'business' => 'Owner, Al Noor Pharmacy, Abu Dhabi', 'photo' => '/images/reviews/ahmed.webp']
    'testimonials' => [],
];
