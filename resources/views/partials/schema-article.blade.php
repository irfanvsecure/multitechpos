{{-- Article structured data for blog posts. $headline, $image (path), $published (Y-m-d), $author --}}
@section('page_kind', 'post')
@php
    $root = request()->getSchemeAndHttpHost().$base;
    $article = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => html_entity_decode($headline, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        'image' => $image ? (str_starts_with($image, 'http') ? $image : $root.$image) : null,
        'datePublished' => $published ?: null,
        'author' => ['@type' => 'Person', 'name' => $author, 'url' => $root.'/author/multitech_admin/'],
        'publisher' => ['@id' => $root.'/#organization'],
        'mainEntityOfPage' => $root.$current,
    ]);
@endphp
@push('schema')
<script type="application/ld+json">{!! json_encode($article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
