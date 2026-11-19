{{-- FAQPage JSON-LD from this page's faqs (nothing when the page has none). --}}
@php($faqs = \App\Cms\Faqs::forPage($cmsPage))
@if (count($faqs))
    @include('partials.schema-faq', ['faqs' => $faqs])
@endif
