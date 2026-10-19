{{-- FAQPage structured data. $faqs = [['q' => 'Question?', 'a' => 'Answer'], ...] --}}
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => html_entity_decode(strip_tags($faq['q']), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => trim(html_entity_decode(strip_tags($faq['a']), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            ],
        ])->all(),
    ];
@endphp
@push('schema')
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
