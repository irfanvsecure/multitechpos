@php
    // Absolute URLs for canonical / social tags
    $siteUrl = request()->getSchemeAndHttpHost();
    $pageUrl = $siteUrl.$base.$current;
    // inline @section values arrive already escaped; decode so {{ }} escapes them only once
    $section = fn ($name, $default = '') => trim(html_entity_decode($__env->yieldContent($name, $default), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $pageTitle = $section('title', config('site.name'));
    $pageDescription = $section('description');
    // SEO overrides saved through the MCP (empty = the defaults below)
    $canonical = $section('canonical') ?: $pageUrl;
    if (str_starts_with($canonical, '/')) {
        $canonical = $siteUrl.$base.$canonical;
    }
    $ogTitle = $section('og_title') ?: $pageTitle;
    $ogDescription = $section('og_description') ?: $pageDescription;
    $twitterCard = $section('twitter_card') ?: 'summary_large_image';
    // SITE_NOINDEX (pre-launch) wins over every page's own robots value
    $robots = \App\Cms\Cms::noindex() ? 'noindex, nofollow' : $section('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
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
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonical }}">
    @if (config('site.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">
    @endif

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    @if ($ogDescription)
        <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    @if ($ogDescription)
        <meta name="twitter:description" content="{{ $ogDescription }}">
    @endif
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="icon" href="{{ $base }}/images/2025/12/logo4-150x80.png" sizes="32x32">
    <link rel="icon" href="{{ $base }}/images/2025/12/logo4.png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ $base }}/images/2025/12/logo4.png">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ $base }}/css/style.css?v=37">

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

    {{-- Interactive sections (calculator, quiz, builder ...) picked for this page.
         Pages set @section('page_kind'); @section('no_cro') leaves them out. --}}
    @unless ($__env->hasSection('no_cro'))
        @include('partials.cro.zone', ['kind' => $section('page_kind') ?: 'general', 'slug' => trim($current, '/') ?: 'home'])
        @include('partials.callback')
    @endunless

    @include('partials.footer')
    @include('partials.whatsapp')
    @include('partials.mobile-cta')
    <template id="inline-cta">@include('partials.inline-cta')</template>

    <script src="{{ $base }}/js/main.js?v=14" defer></script>
    @stack('scripts')
</body>
</html>
