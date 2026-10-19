{{-- Organization + WebSite structured data (every page). Expects $siteUrl. --}}
@php
    $site = config('site');
    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => $siteUrl.'/#organization',
                'name' => $site['name'],
                'url' => $siteUrl.'/',
                'logo' => $siteUrl.$site['logo'],
                'email' => $site['email'],
                'telephone' => $site['phone'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $site['address']['street'],
                    'addressLocality' => $site['address']['city'],
                    'addressCountry' => $site['address']['country'],
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => $site['phone'],
                    'email' => $site['email'],
                    'contactType' => 'sales',
                    'areaServed' => 'AE',
                    'availableLanguage' => ['English', 'Arabic'],
                ],
                'sameAs' => $site['social'],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl.'/#website',
                'url' => $siteUrl.'/',
                'name' => $site['name'],
                'publisher' => ['@id' => $siteUrl.'/#organization'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
