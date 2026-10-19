{{-- BreadcrumbList structured data. $items = [['name' => 'Home', 'url' => '/'], ...] (paths relative to the site) --}}
@php
    $root = request()->getSchemeAndHttpHost().$base;
    $breadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($items)->values()->map(fn ($item, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => html_entity_decode(strip_tags($item['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'item' => $root.$item['url'],
        ])->all(),
    ];
@endphp
@push('schema')
<script type="application/ld+json">{!! json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
