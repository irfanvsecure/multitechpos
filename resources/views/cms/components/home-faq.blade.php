{{-- FAQ accordion (this page's faqs) + contact box. --}}
@php($faqs = \App\Cms\Faqs::forPage($cmsPage))
<section class="home-section">
    <div class="wrap faq-layout">
        <div class="faq-layout__aside">
            <span class="tag">{{ $tag }}</span>
            <h2>{!! $heading !!}</h2>
            <p>{{ $text }}</p>

            <div class="contact-box">
                <p class="contact-box__title">Still have questions?</p>
                <p>Our team is ready to help you find the perfect POS solution for your business.</p>
                <a href="{{ config('site.whatsapp') }}" class="contact-line" target="_blank" rel="noopener"><span class="icon-circle icon-circle--green"><i class="fab fa-whatsapp"></i></span><span><strong>WhatsApp</strong>Quick response</span></a>
                <a href="tel:{{ config('site.phone') }}" class="contact-line"><span class="icon-circle"><i class="fas fa-phone-alt"></i></span><span><strong>Call Us</strong>{{ config('site.phone_display') }}</span></a>
                <a href="mailto:{{ config('site.email') }}" class="contact-line"><span class="icon-circle"><i class="far fa-envelope"></i></span><span><strong>Email</strong>{{ config('site.email') }}</span></a>
            </div>
        </div>

        <div class="faq" data-reveal>
            @foreach ($faqs as $i => $faq)
                <details @if ($i === 0) open @endif>
                    <summary>{{ $faq['q'] }}</summary>
                    <p>{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
