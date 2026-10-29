{{-- Slim demo strip. Rendered once in a <template>; main.js copies it into the middle of long pages and blog posts. --}}
<aside class="inline-cta">
    <span class="inline-cta__icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
    <div class="inline-cta__text">
        <p class="inline-cta__title">Want to see this working in your business?</p>
        <p>Get a free demo set up for your workflow. No credit card, no obligation.</p>
    </div>
    <div class="inline-cta__actions">
        <a href="{{ $base }}/contact/" class="btn btn--primary">Book a Free Demo <i class="fas fa-arrow-right"></i></a>
        <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp</a>
    </div>
</aside>
