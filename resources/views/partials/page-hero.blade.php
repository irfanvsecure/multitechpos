{{--
    Page banner used by the inner pages.
    $title  - page heading (h1)
    $image  - background image path (optional)
    $parent - ['name' => ..., 'url' => ...] for the breadcrumb (optional)
    $text   - short intro under the heading (optional)
    $actions - false hides the demo / WhatsApp buttons (optional)
--}}
@include('partials.schema-breadcrumb', ['items' => array_values(array_filter([
    ['name' => 'Home', 'url' => '/'],
    $parent ?? null,
    ['name' => $title, 'url' => $current],
]))])
<section class="page-hero" @isset($image) style="background-image:url('{{ $base }}{{ $image }}')" @endisset>
    <div class="wrap page-hero__inner">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ $base }}/">Home</a>
            @isset($parent)
                <i class="fas fa-chevron-right"></i>
                <a href="{{ $base }}{{ $parent['url'] }}">{{ $parent['name'] }}</a>
            @endisset
            <i class="fas fa-chevron-right"></i>
            <span>{!! strip_tags($title) !!}</span>
        </nav>
        <h1>{!! $title !!}</h1>
        @isset($text)
            <p class="page-hero__text">{{ $text }}</p>
        @endisset
        @if ($actions ?? true)
            <div class="page-hero__actions">
                <a href="{{ $base }}/contact/" class="btn btn--primary btn--lg-pad">Book a Free Demo <i class="fas fa-arrow-right"></i></a>
                <a href="http://wa.me/+971588979925" class="btn btn--ghost btn--lg-pad" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
        @endif
        <ul class="page-hero__trust">
            <li><i class="fas fa-check-circle"></i> 500+ UAE businesses</li>
            <li><i class="fas fa-check-circle"></i> VAT &amp; FTA compliant</li>
            <li><i class="fas fa-check-circle"></i> 24/7 local support</li>
        </ul>
    </div>
</section>
