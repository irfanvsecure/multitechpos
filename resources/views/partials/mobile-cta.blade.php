{{-- Sticky Call / WhatsApp / Demo bar (phones only) --}}
<nav class="mobile-cta" aria-label="Contact MultiTech POS">
    <a href="tel:{{ config('site.phone') }}" class="mobile-cta__item">
        <i class="fas fa-phone-alt"></i><span>Call</span>
    </a>
    <a href="{{ config('site.whatsapp') }}" class="mobile-cta__item mobile-cta__item--whatsapp" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
    </a>
    <a href="{{ $base }}/contact/" class="mobile-cta__item mobile-cta__item--demo" data-focus-form>
        <i class="fas fa-calendar-check"></i><span>Free Demo</span>
    </a>
</nav>
