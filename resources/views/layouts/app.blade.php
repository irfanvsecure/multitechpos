@php
    // Absolute URLs for canonical / social tags
    $siteUrl = request()->getSchemeAndHttpHost();
    $pageUrl = $siteUrl.$base.$current;
    // inline @section values arrive already escaped; decode so {{ }} escapes them only once
    $section = fn ($name, $default = '') => trim(html_entity_decode($__env->yieldContent($name, $default), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $pageTitle = $section('title', config('site.name'));
    $pageDescription = $section('description');
    $ogImage = $section('og_image') ?: $base.config('site.logo');
    if (str_starts_with($ogImage, '/')) {
        $ogImage = $siteUrl.$ogImage;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    @if ($pageDescription)
        <meta name="description" content="{{ $pageDescription }}">
    @endif
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <link rel="canonical" href="{{ $pageUrl }}">
    @if (config('site.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">
    @endif

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    @if ($pageDescription)
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if ($pageDescription)
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="{{ $base }}/images/2025/12/logo4-150x80.png" sizes="32x32">
    <link rel="icon" href="{{ $base }}/images/2025/12/logo4.png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ $base }}/images/2025/12/logo4.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ $base }}/css/style.css?v=11">

    @include('partials.schema-site', ['siteUrl' => $siteUrl.$base])
    @stack('schema')
    @include('partials.analytics')
    @stack('head')
</head>
<body class="@yield('body_class')">
    @if (config('site.gtm_id'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('site.gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @include('partials.header')

    <main id="content">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.whatsapp')

    <script src="{{ $base }}/js/main.js?v=3" defer></script>
    @stack('scripts')
</body>
</html>
