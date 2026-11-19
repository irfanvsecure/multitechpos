<footer class="site-footer">
    <div class="wrap footer-main">
        <div class="footer-brand">
            <a href="{{ $base }}/" class="footer-logo">
                <img src="{{ $base }}/images/2025/12/multitechpos-logo-e1781947344295.png" alt="MultiTech POS" width="170" height="79">
            </a>
            <p>Smart, VAT-compliant POS and ERP solutions for retail, restaurants, salons, pharmacies and growing businesses across the UAE.</p>
            <ul class="footer-badges">
                <li><i class="fas fa-check-circle"></i> UAE VAT &amp; FTA compliant</li>
                <li><i class="fas fa-check-circle"></i> {{ setting('stat_years') }} years experience</li>
                <li><i class="fas fa-check-circle"></i> {{ setting('stat_support') }} local support</li>
            </ul>
            <div class="footer-social">
                @foreach (\App\Cms\Content::socialLinks() as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}"><i class="{{ $social['icon'] }}"></i></a>
                @endforeach
                <a href="{{ config('site.whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h3>POS Software</h3>
            <ul class="footer-links">
                <li><a href="{{ $base }}/retail-pos-software-uae/">Retail POS</a></li>
                <li><a href="{{ $base }}/food-restaurant-pos-software-uae/">Restaurant POS</a></li>
                <li><a href="{{ $base }}/salon-spa-pos-software-uae/">Salon &amp; Spa POS</a></li>
                <li><a href="{{ $base }}/service-business-pos-software-uae/">Service Business POS</a></li>
                <li><a href="{{ $base }}/custom-pos-software-uae/">Custom POS</a></li>
                <li><a href="{{ $base }}/business-erps/">Business ERPs</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Industries</h3>
            <ul class="footer-links">
                <li><a href="{{ $base }}/grocery-pos-system-in-uae/">Grocery &amp; Supermarket</a></li>
                <li><a href="{{ $base }}/pharmacy-pos-system-in-the-uae/">Pharmacy POS</a></li>
                <li><a href="{{ $base }}/jewelry-pos-system-in-uae/">Jewelry POS</a></li>
                <li><a href="{{ $base }}/perfume-shop-pos-software-in-uae/">Perfume Shop POS</a></li>
                <li><a href="{{ $base }}/bakery-pos-software/">Bakery POS</a></li>
                <li><a href="{{ $base }}/van-sales-management-system-in-uae/">Van Sales</a></li>
                <li><a href="{{ $base }}/more-pos-industries/" class="footer-more">All industries <i class="fas fa-arrow-right"></i></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Company</h3>
            <ul class="footer-links">
                <li><a href="{{ $base }}/about-us/">About Us</a></li>
                <li><a href="{{ $base }}/features/">POS Features</a></li>
                <li><a href="{{ $base }}/downloads/">Downloads</a></li>
                <li><a href="{{ $base }}/our-blogs/">Blog</a></li>
                <li><a href="{{ $base }}/frequently-asked-questions-multitech-pos/">FAQ</a></li>
                <li><a href="{{ $base }}/contact/">Contact</a></li>
            </ul>
        </div>

        <div class="footer-col footer-contact">
            <h3>Get in Touch</h3>
            <ul class="footer-links">
                <li><i class="fas fa-map-marker-alt"></i><span>{{ setting('address') }}</span></li>
                <li><i class="fas fa-phone-alt"></i><a href="tel:{{ config('site.phone') }}">{{ config('site.phone_display') }}</a></li>
                <li><i class="far fa-envelope"></i><a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></li>
                <li><i class="far fa-clock"></i><span>Sat – Thu: 9:00 AM – 6:00 PM<br>Friday: Closed</span></li>
            </ul>
            <a href="{{ $base }}/contact/" class="btn btn--primary footer-cta">Book a Free Demo</a>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="wrap footer-bottom__inner">
            <p>&copy; {{ date('Y') }} MultiTech POS. All rights reserved by <a href="http://mait.ae" target="_blank" rel="noopener">MAIT</a>.</p>
            <ul class="footer-legal">
                <li><a href="{{ $base }}/privacy-policy/">Privacy Policy</a></li>
                <li><a href="{{ $base }}/terms-of-service/">Terms &amp; Conditions</a></li>
                <li><a href="{{ $base }}/support-policy/">Support Policy</a></li>
                <li><a href="{{ $base }}/security-policy/">Security Policy</a></li>
                <li><a href="{{ $base }}/cookie-policy/">Cookie Policy</a></li>
            </ul>
        </div>
    </div>
</footer>
