{{-- Demo call-to-action banner. Optional variable: $ctaTitle --}}
<section class="home-cta grid-lines">
    <div class="wrap home-cta__inner">
        <div>
            <span class="tag tag--glass"><i class="fas fa-gift"></i> Limited Time Offer - Free Demo</span>
            <h2>{{ $ctaTitle ?? 'Ready to Transform Your Business?' }}</h2>
            <p>Get a free consultation and demo of our POS solutions. Our experts will help you find the perfect system for your business needs.</p>
            <ul class="cta__points">
                <li><i class="fas fa-check-circle"></i> No Credit Card Required</li>
                <li><i class="fas fa-check-circle"></i> Free Consultation</li>
                <li><i class="fas fa-check-circle"></i> UAE-Based Support</li>
            </ul>
        </div>
        <div class="home-cta__actions">
            <a href="{{ $base }}/contact/" class="btn btn--white btn--lg-pad">Request Free Demo <i class="fas fa-arrow-right"></i></a>
            <a href="http://wa.me/+971588979925" class="btn btn--ghost btn--lg-pad" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
        </div>
    </div>
</section>
