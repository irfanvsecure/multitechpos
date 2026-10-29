<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('seo')
    <link rel="stylesheet" href="{{ $base }}/wp-content/cache/speedycache/multitechpos.com/fonts/03ee56d594bf8057930f4b0fd00ebb4f/03ee56d594bf8057930f4b0fd00ebb4f.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $base }}/css/mtp-header.css?v=10">
    <link rel="stylesheet" href="{{ $base }}/css/site.css?v=10">
    <link rel="stylesheet" href="{{ $base }}/css/site-bg.css?v=10">
</head>
<body>
    <a class="skip-link" href="#content">Skip to content</a>
    @include('partials.site-header')
    <main id="content">
        @yield('content')
    </main>
    @include('partials.site-footer')
    <a class="whatsapp-float" href="https://wa.me/971588979925?text=Hi%20MultiTech%2C%20I%27d%20like%20a%20free%20POS%20demo" target="_blank" rel="noopener" aria-label="WhatsApp">
        <span>Need Help?</span>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ $base }}/js/mtp-header.js?v=10" defer></script>
</body>
</html>
