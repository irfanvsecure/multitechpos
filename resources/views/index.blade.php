@extends('layouts.app')

@section('title', '#1 POS Software & ERP Systems Company In UAE - MultiTech')
@section('description', 'MultiTech POS Is A Leading POS Software & ERP Systems Company In UAE. Get Scalable, VAT-Compliant Solutions With Local Support. Book A Free Demo Today!')
@section('og_image', $base . '/wp-content/uploads/2026/01/pos-software.jpg')

@section('content')

@include('partials.schema-faq', ['faqs' => [
    ['q' => 'What is the best POS software for businesses in the UAE?', 'a' => 'MultiTech POS is a leading POS software in the UAE, offering VAT-compliant solutions for retail shops, restaurants, supermarkets, and pharmacies with fast billing, inventory control, and 24/7 local support.'],
    ['q' => 'Is MultiTech POS software VAT-compliant in the UAE?', 'a' => 'Yes, MultiTech POS is fully VAT-compliant with UAE FTA regulations. Every invoice automatically calculates 5% VAT, generates tax-ready receipts, and produces accurate reports for hassle-free VAT return filing.'],
    ['q' => 'Which industries does MultiTech POS software support?', 'a' => 'MultiTech POS serves retail stores, restaurants, supermarkets, pharmacies, salons, laundries, jewelry shops, and fuel stations across the UAE, with industry-specific features like table management, expiry tracking, and barcode scanning.'],
    ['q' => 'Does MultiTech POS include ERP and inventory management features?', 'a' => 'Yes, MultiTech offers complete ERP systems covering stock management, accounting, payroll, and employee management, giving UAE businesses real-time inventory control and reporting across single or multi-branch operations.'],
    ['q' => 'How can I get a free demo of MultiTech POS in the UAE?', 'a' => 'Simply call or WhatsApp +971 58 897 9925 to book a free demo and consultation. No credit card required, our UAE-based experts recommend the right POS system for your business.'],
]])


{{-- Hero --}}
<section class="home-hero">
    <div class="wrap home-hero__grid">
        <div class="home-hero__content">
            <span class="pill"><span class="pill__dot"></span> Trusted by 500+ businesses in the UAE</span>
            <h1>All-in-One <span class="text-gradient">POS Software</span> for Retail, Restaurants &amp; Growing Businesses</h1>
            <p class="lead">Complete point-of-sale systems designed for retail, restaurants, supermarkets, and pharmacies. Streamline operations, boost sales, and grow your business.</p>

            <ul class="check-grid">
                <li><i class="fas fa-check"></i> Fast billing</li>
                <li><i class="fas fa-check"></i> Inventory control</li>
                <li><i class="fas fa-check"></i> Real-time reports</li>
                <li><i class="fas fa-check"></i> VAT &amp; FTA ready</li>
            </ul>

            <div class="btn-row">
                <a href="{{ $base }}/contact/" class="btn btn--primary btn--lg-pad">Book a Free Demo <i class="fas fa-arrow-right"></i></a>
                <a href="http://wa.me/+971588979925" class="btn btn--light btn--lg-pad" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
            <p class="hero-note"><i class="fas fa-shield-alt"></i> No credit card required &middot; Free consultation &middot; UAE-based support</p>
        </div>

        <div class="home-hero__media">
            <img src="{{ $base }}/wp-content/uploads/2026/01/pos-software.webp" alt="POS Software in UAE" width="580" height="388">
            <div class="float-card float-card--top">
                <span class="float-card__icon"><i class="fas fa-file-invoice"></i></span>
                <span><strong>VAT-ready invoices</strong><small>FTA compliant, every sale</small></span>
            </div>
            <div class="float-card float-card--bottom">
                <span class="float-card__icon float-card__icon--green"><i class="fas fa-chart-line"></i></span>
                <span><strong>Live sales reports</strong><small>All branches, one dashboard</small></span>
            </div>
        </div>
    </div>

    <div class="wrap">
        <ul class="stat-bar">
            <li><strong>500+</strong><span>Businesses served</span></li>
            <li><strong>15+</strong><span>Years of experience</span></li>
            <li><strong>30+</strong><span>Industries supported</span></li>
            <li><strong>24/7</strong><span>Local support</span></li>
        </ul>
    </div>
</section>

{{-- Client logos --}}
@include('partials.clients-band')

{{-- POS solutions --}}
<section class="home-section home-section--light">
    <div class="wrap">
        <div class="home-head">
            <span class="tag">Our POS Solutions</span>
            <h2>POS Solutions for <span class="text-gradient">Every Business</span></h2>
            <p>Industry-specific point-of-sale systems designed to meet the unique needs of your business. From retail shops to pharmacies, we have you covered.</p>
        </div>

        <div class="solution-grid">
            <article class="solution">
                <span class="icon-tile icon-tile--blue"><i class="fas fa-shopping-cart"></i></span>
                <h3><a href="{{ $base }}/retail-pos-software-uae/">Retail POS</a></h3>
                <p>Complete retail management with inventory tracking, customer loyalty, and multi-store support.</p>
                <ul class="pills">
                    <li><i class="far fa-check-circle"></i> Barcode Scanning</li>
                    <li><i class="far fa-check-circle"></i> Customer Management</li>
                    <li><i class="far fa-check-circle"></i> Multi-store Support</li>
                </ul>
                <a href="{{ $base }}/retail-pos-software-uae/" class="link-arrow">Explore Retail POS <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="solution">
                <span class="icon-tile icon-tile--orange"><i class="fas fa-utensils"></i></span>
                <h3><a href="{{ $base }}/food-restaurant-pos-software-uae/">Restaurant POS</a></h3>
                <p>Streamline your restaurant operations with table management, kitchen display, and order tracking.</p>
                <ul class="pills">
                    <li><i class="far fa-check-circle"></i> Table Management</li>
                    <li><i class="far fa-check-circle"></i> Kitchen Display</li>
                    <li><i class="far fa-check-circle"></i> Split Bills</li>
                </ul>
                <a href="{{ $base }}/food-restaurant-pos-software-uae/" class="link-arrow">Explore Restaurant POS <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="solution">
                <span class="icon-tile icon-tile--green"><i class="fas fa-shopping-basket"></i></span>
                <h3><a href="{{ $base }}/grocery-pos-system-in-uae/">Supermarket POS</a></h3>
                <p>High-volume checkout solutions with multi-lane support, scale integration, and promotions.</p>
                <ul class="pills">
                    <li><i class="far fa-check-circle"></i> Multi-lane Checkout</li>
                    <li><i class="far fa-check-circle"></i> Scale Integration</li>
                    <li><i class="far fa-check-circle"></i> Promotions</li>
                </ul>
                <a href="{{ $base }}/grocery-pos-system-in-uae/" class="link-arrow">Explore Supermarket POS <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="solution">
                <span class="icon-tile icon-tile--purple"><i class="fas fa-pills"></i></span>
                <h3><a href="{{ $base }}/pharmacy-pos-system-in-the-uae/">Pharmacy POS</a></h3>
                <p>Stay on top of expiry dates, batch numbers and insurance claims with a POS built for pharmacies.</p>
                <ul class="pills">
                    <li><i class="far fa-check-circle"></i> Expiry Tracking</li>
                    <li><i class="far fa-check-circle"></i> Batch Management</li>
                    <li><i class="far fa-check-circle"></i> Insurance Claims</li>
                </ul>
                <a href="{{ $base }}/pharmacy-pos-system-in-the-uae/" class="link-arrow">Explore Pharmacy POS <i class="fas fa-arrow-right"></i></a>
            </article>
        </div>

        <div class="home-center">
            <a href="{{ $base }}/pos-software-uae/" class="btn btn--outline-blue">View All POS Solutions <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="home-section">
    <div class="wrap">
        <div class="home-head">
            <span class="tag">How It Works</span>
            <h2>Up and Running in <span class="text-gradient">3 Simple Steps</span></h2>
            <p>From your first call to your first sale, our UAE team handles the setup so you can focus on your customers.</p>
        </div>

        <ol class="steps">
            <li class="step">
                <span class="step__num">01</span>
                <span class="icon-tile icon-tile--md icon-tile--blue"><i class="fas fa-headset"></i></span>
                <h3>Book a free demo</h3>
                <p>Call, WhatsApp or fill in the form. We learn how your business works and show you the right system.</p>
            </li>
            <li class="step">
                <span class="step__num">02</span>
                <span class="icon-tile icon-tile--md icon-tile--green"><i class="fas fa-cogs"></i></span>
                <h3>Setup &amp; training</h3>
                <p>Get set up in as little as 48 hours. We configure your products, VAT and devices, and train your staff.</p>
            </li>
            <li class="step">
                <span class="step__num">03</span>
                <span class="icon-tile icon-tile--md icon-tile--amber"><i class="fas fa-chart-line"></i></span>
                <h3>Sell &amp; grow</h3>
                <p>Run faster checkouts, track stock and sales in real time, and add modules as your business grows.</p>
            </li>
        </ol>
    </div>
</section>

{{-- Industries --}}
<section class="home-section home-section--light">
    <div class="wrap">
        <div class="home-head">
            <span class="tag">Industries</span>
            <h2>Industries <span class="text-gradient">We Serve</span></h2>
            <p>Our POS solutions are trusted by businesses across diverse industries in the UAE and Middle East.</p>
        </div>

        <div class="industry-grid">
            <a href="{{ $base }}/retail-pos-software-uae/" class="industry"><span class="icon-tile icon-tile--blue"><i class="fas fa-shopping-cart"></i></span><span class="industry__name">Retail Shops</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/food-restaurant-pos-software-uae/" class="industry"><span class="icon-tile icon-tile--orange"><i class="fas fa-utensils"></i></span><span class="industry__name">Restaurants</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/grocery-pos-system-in-uae/" class="industry"><span class="icon-tile icon-tile--purple"><i class="fas fa-shopping-basket"></i></span><span class="industry__name">Supermarkets</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/pharmacy-pos-system-in-the-uae/" class="industry"><span class="icon-tile icon-tile--green"><i class="far fa-hospital"></i></span><span class="industry__name">Pharmacies</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/fashion-pos-system-in-uae/" class="industry"><span class="icon-tile icon-tile--pink"><i class="fas fa-tshirt"></i></span><span class="industry__name">Fashion Boutiques</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/bakery-pos-software/" class="industry"><span class="icon-tile icon-tile--amber"><i class="fas fa-coffee"></i></span><span class="industry__name">Cafes &amp; Bakeries</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/salon-spa-pos-software-uae/" class="industry"><span class="icon-tile icon-tile--sky"><i class="fas fa-spa"></i></span><span class="industry__name">Salons &amp; Spas</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/gas-station-pos-software/" class="industry"><span class="icon-tile icon-tile--slate"><i class="fas fa-gas-pump"></i></span><span class="industry__name">Fuel Stations</span><i class="fas fa-arrow-right industry__arrow"></i></a>
        </div>

        <div class="home-center">
            <a href="{{ $base }}/more-pos-industries/" class="btn btn--outline-blue">See All 30+ Industries <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- Transforming business --}}
<section class="home-section">
    <div class="wrap feature-split">
        <div class="feature-split__media">
            <img src="{{ $base }}/wp-content/uploads/2026/06/3d-rendering-point-sale-system-store-management_493806-1407.webp" alt="Retail POS Software in UAE" width="626" height="626" loading="lazy">
        </div>
        <div class="feature-split__content">
            <span class="tag">Built for the UAE</span>
            <h2>Transforming Business Operations <span class="text-gradient">Across the UAE</span></h2>
            <p>From Abu Dhabi’s bustling retail districts to Dubai’s fast-paced hospitality scene, businesses are replacing manual processes with intelligent point-of-sale technology, and MultiTech POS is leading that shift. Our integrated POS software and ERP systems connect billing, stock control, accounting, and staff performance into a single platform, giving owners complete visibility over every branch from one dashboard.</p>
            <p>Whether you run a single boutique or a multi-location enterprise, our VAT-compliant solutions adapt to the way you work, not the other way around. Automated reporting reduces costly errors, smart inventory alerts prevent stockouts, and cloud-based access keeps you in control wherever business takes you. Discover how the right POS system in UAE turns daily operations into a genuine competitive advantage.</p>
            <ul class="check-list">
                <li><i class="fas fa-check-circle"></i> Billing, stock, accounting &amp; staff in one platform</li>
                <li><i class="fas fa-check-circle"></i> Every branch visible from one dashboard</li>
                <li><i class="fas fa-check-circle"></i> Smart alerts that prevent stockouts</li>
            </ul>
            <div class="btn-row">
                <a href="{{ $base }}/contact/" class="btn btn--primary">Book Free Demo <i class="fas fa-arrow-right"></i></a>
                <a href="tel:+971588979925" class="btn btn--light"><i class="fas fa-phone-alt"></i> +971 58 897 9925</a>
            </div>
        </div>
    </div>
</section>

{{-- Why choose us --}}
<section class="home-section home-section--dark grid-lines">
    <div class="wrap">
        <div class="home-head home-head--dark">
            <span class="tag tag--dark">Why Choose Us</span>
            <h2>Why Choose <span class="text-gradient">MultiTech POS?</span></h2>
            <p>We’re committed to providing the best POS solutions and support for your business growth.</p>
        </div>

        <div class="why-grid">
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--blue"><i class="fas fa-award"></i></span>
                <h3>15+ Years Experience</h3>
                <p>Trusted by hundreds of businesses across the UAE with proven expertise in POS solutions.</p>
            </div>
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--green"><i class="fas fa-headset"></i></span>
                <h3>24/7 Support</h3>
                <p>Round-the-clock technical support to keep your business running smoothly.</p>
            </div>
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--amber"><i class="fas fa-sliders-h"></i></span>
                <h3>Custom Solutions</h3>
                <p>Tailored POS systems designed specifically for your business requirements.</p>
            </div>
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--violet"><i class="fas fa-bolt"></i></span>
                <h3>Quick Setup</h3>
                <p>Get up and running in as little as 48 hours, with setup handled by our local team.</p>
            </div>
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--rose"><i class="fas fa-cash-register"></i></span>
                <h3>Quality Hardware</h3>
                <p>Reliable POS terminals, printers and scanners that work seamlessly with our software.</p>
            </div>
            <div class="why">
                <span class="icon-tile icon-tile--md icon-tile--cyan"><i class="fas fa-user-graduate"></i></span>
                <h3>Training Included</h3>
                <p>Hands-on training for your staff, so your team is confident from day one.</p>
            </div>
        </div>
    </div>
</section>

{{-- Grows with your business --}}
<section class="home-section">
    <div class="wrap feature-split feature-split--reverse">
        <div class="feature-split__media">
            <img src="{{ $base }}/wp-content/uploads/2026/06/female-staff-using-credit-card-terminal-cash-counter-50kb.webp" alt="Retail POS Software in UAE" width="648" height="432" loading="lazy">
        </div>
        <div class="feature-split__content">
            <span class="tag">Scalable</span>
            <h2>A POS Solution That <span class="text-gradient">Grows with Your Business</span></h2>
            <p>Most businesses outgrow their first billing system within a few years, but with MultiTech POS, you never have to start over. Our scalable POS solution is built to expand alongside you, whether that means adding new counters, opening additional branches, or moving from retail into wholesale and distribution.</p>
            <p>Start with the essentials like fast checkout and barcode scanning, then activate advanced modules such as payroll, customer loyalty, and warehouse management as your needs evolve. Because every upgrade happens within the same point-of-sale platform, your historical sales data, product catalogue, and customer records stay intact. It’s a future-ready investment for UAE businesses that plan to grow, backed by a local team that grows with you.</p>
            <div class="btn-row">
                <a href="tel:+971588979925" class="btn btn--primary"><i class="fas fa-phone-alt"></i> Speak With an Expert</a>
            </div>
        </div>
    </div>
</section>

{{-- Best point of sale --}}
<section class="home-section home-section--light">
    <div class="wrap feature-split">
        <div class="feature-split__media">
            <img src="{{ $base }}/wp-content/uploads/2026/01/pos-software.webp" alt="POS Software in UAE" width="580" height="388" loading="lazy">
        </div>
        <div class="feature-split__content">
            <span class="tag">Made for UAE Businesses</span>
            <h2>Get the Best <span class="text-gradient">Point of Sale</span> for Your Business</h2>
            <p>Whether you’re running a single retail shop in Dubai or managing multiple restaurant branches across the UAE, the right point-of-sale system can make all the difference. Slow checkouts, stock mismatches, and manual VAT calculations cost you more than just time, they cost you customers.</p>
            <p>MultiTech POS brings together fast billing, real-time inventory tracking, and VAT-compliant reporting, all built around how UAE businesses actually operate. Our POS software adapts to your industry, whether you’re in food and beverage, fashion, pharmacy, or salon services, with no complicated setup and no steep learning curve.</p>
            <p>With over 15 years of on-the-ground experience and a dedicated local support team, thousands of businesses across the UAE trust us to keep their operations running smoothly every single day. Book a free demo today and find out why.</p>
            <div class="btn-row">
                <a href="{{ $base }}/contact/" class="btn btn--primary">Book Free Demo <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- Call to action --}}
@include('partials.cta-band')

{{-- FAQ + contact --}}
<section class="home-section">
    <div class="wrap faq-layout">
        <div class="faq-layout__aside">
            <span class="tag">Help Center</span>
            <h2>FAQs about <span class="text-gradient">MultiTech POS</span> Software Solutions</h2>
            <p>Our POS solutions are trusted by businesses across diverse industries in Dubai, Abu Dhabi, Sharjah and the wider UAE.</p>

            <div class="contact-box">
                <p class="contact-box__title">Still have questions?</p>
                <p>Our team is ready to help you find the perfect POS solution for your business.</p>
                <a href="http://wa.me/+971588979925" class="contact-line" target="_blank" rel="noopener"><span class="icon-circle icon-circle--green"><i class="fab fa-whatsapp"></i></span><span><strong>WhatsApp</strong>Quick response</span></a>
                <a href="tel:+971588979925" class="contact-line"><span class="icon-circle"><i class="fas fa-phone-alt"></i></span><span><strong>Call Us</strong>+971 58 897 9925</span></a>
                <a href="mailto:support@multitechpos.com" class="contact-line"><span class="icon-circle"><i class="far fa-envelope"></i></span><span><strong>Email</strong>support@multitechpos.com</span></a>
            </div>
        </div>

        <div class="faq">
            <details open>
                <summary>What is the best POS software for businesses in the UAE?</summary>
                <p>MultiTech POS is a leading POS software in the UAE, offering VAT-compliant solutions for retail shops, restaurants, supermarkets, and pharmacies with fast billing, inventory control, and 24/7 local support.</p>
            </details>
            <details>
                <summary>Is MultiTech POS software VAT-compliant in the UAE?</summary>
                <p>Yes, MultiTech POS is fully VAT-compliant with UAE FTA regulations. Every invoice automatically calculates 5% VAT, generates tax-ready receipts, and produces accurate reports for hassle-free VAT return filing.</p>
            </details>
            <details>
                <summary>Which industries does MultiTech POS software support?</summary>
                <p>MultiTech POS serves retail stores, restaurants, supermarkets, pharmacies, salons, laundries, jewelry shops, and fuel stations across the UAE, with industry-specific features like table management, expiry tracking, and barcode scanning.</p>
            </details>
            <details>
                <summary>Does MultiTech POS include ERP and inventory management features?</summary>
                <p>Yes, MultiTech offers complete ERP systems covering stock management, accounting, payroll, and employee management, giving UAE businesses real-time inventory control and reporting across single or multi-branch operations.</p>
            </details>
            <details>
                <summary>How can I get a free demo of MultiTech POS in the UAE?</summary>
                <p>Simply call or WhatsApp +971 58 897 9925 to book a free demo and consultation. No credit card required, our UAE-based experts recommend the right POS system for your business.</p>
            </details>
        </div>
    </div>
</section>

@endsection
