@extends('layouts.app')

@section('title', '#1 POS Software & ERP Systems Company In UAE - MultiTech')
@section('description', 'MultiTech POS Is A Leading POS Software & ERP Systems Company In UAE. Get Scalable, VAT-Compliant Solutions With Local Support. Book A Free Demo Today!')
@section('og_image', $base . '/images/2026/01/pos-software.jpg')
@section('page_kind', 'home')

@php
    // A/B test of the hero (config site.ab_test). "a" is the original version.
    $variant = 'a';
    if (config('site.ab_test')) {
        $variant = in_array(request()->cookie('mtp_ab'), ['a', 'b'], true) ? request()->cookie('mtp_ab') : (random_int(0, 1) ? 'b' : 'a');
        \Illuminate\Support\Facades\Cookie::queue('mtp_ab', $variant, 60 * 24 * 30);
    }

    $rating = config('site.google_rating');

    // Business picker: the names match the options of the hero form
    $businesses = [
        ['name' => 'Retail Shop', 'label' => 'Retail', 'icon' => 'fas fa-shopping-cart', 'tile' => 'blue', 'url' => '/retail-pos-software-uae/',
            'text' => 'Sell faster at the counter and always know what is on the shelf, in one shop or many.',
            'points' => ['Barcode scanning and fast billing', 'Customer records and loyalty', 'Stock across all your stores']],
        ['name' => 'Restaurant / Cafe', 'label' => 'Restaurant & Cafe', 'icon' => 'fas fa-utensils', 'tile' => 'orange', 'url' => '/food-restaurant-pos-software-uae/',
            'text' => 'Keep orders moving from table to kitchen, for dine-in, takeaway and delivery.',
            'points' => ['Table and floor management', 'Kitchen display or KOT printing', 'Recipe-based stock deduction']],
        ['name' => 'Supermarket / Grocery', 'label' => 'Supermarket', 'icon' => 'fas fa-shopping-basket', 'tile' => 'green', 'url' => '/grocery-pos-system-in-uae/',
            'text' => 'High-volume checkout that keeps queues short and prices right.',
            'points' => ['Multi-lane checkout', 'Weighing scale integration', 'Promotions and offers']],
        ['name' => 'Pharmacy', 'label' => 'Pharmacy', 'icon' => 'fas fa-pills', 'tile' => 'purple', 'url' => '/pharmacy-pos-system-in-the-uae/',
            'text' => 'Stay compliant and cut waste from expired stock.',
            'points' => ['Batch and expiry date alerts', 'Prescription-linked sales', 'Supplier-level stock tracking']],
        ['name' => 'Salon / Spa', 'label' => 'Salon & Spa', 'icon' => 'fas fa-spa', 'tile' => 'pink', 'url' => '/salon-spa-pos-software-uae/',
            'text' => 'Fill your diary and bill services and products in one place.',
            'points' => ['Appointment scheduling', 'Stylist assignment and commissions', 'Service-based billing']],
        ['name' => 'Other', 'label' => 'Something else', 'icon' => 'fas fa-store', 'tile' => 'slate', 'url' => '/more-pos-industries/',
            'text' => 'Gold shops, tailors, laundries, distributors and 30+ other industries.',
            'points' => ['Gold rates by weight and karat', 'Tailoring orders and measurements', 'Van sales with live stock sync']],
    ];

    $faqs = [
        ['q' => 'What is the best POS software for businesses in the UAE?', 'a' => 'MultiTech POS is a leading POS software in the UAE, offering VAT-compliant solutions for retail shops, restaurants, supermarkets, and pharmacies with fast billing, inventory control, and 24/7 local support.'],
        ['q' => 'How much does MultiTech POS cost?', 'a' => 'Pricing depends on the modules you need, the number of branches and users, and whether you choose a one-time license or a subscription. Share your business details and our team will send an accurate quote for your setup.'],
        ['q' => 'How long does it take to set up?', 'a' => 'Most businesses are set up and trained within a few days, and simple setups can be ready in as little as 48 hours. Our team handles product setup, VAT settings, devices and staff training.'],
        ['q' => 'Can I move my existing products and customers to MultiTech POS?', 'a' => 'Yes. Our onboarding team migrates data from spreadsheets or most existing POS systems during setup, so you keep your product, stock and customer history.'],
        ['q' => 'Do I need to buy new hardware?', 'a' => 'Not always. MultiTech POS works with standard barcode scanners, receipt printers, cash drawers and touchscreen terminals. We can recommend a hardware bundle or use equipment you already own if it meets the basic specifications.'],
        ['q' => 'Is MultiTech POS software VAT-compliant in the UAE?', 'a' => 'Yes, MultiTech POS is fully VAT-compliant with UAE FTA regulations. Every invoice automatically calculates 5% VAT, generates tax-ready receipts, and produces accurate reports for hassle-free VAT return filing.'],
        ['q' => 'Which industries does MultiTech POS software support?', 'a' => 'MultiTech POS serves retail stores, restaurants, supermarkets, pharmacies, salons, laundries, jewelry shops, and fuel stations across the UAE, with industry-specific features like table management, expiry tracking, and barcode scanning.'],
        ['q' => 'Does MultiTech POS include ERP and inventory management features?', 'a' => 'Yes, MultiTech offers complete ERP systems covering stock management, accounting, payroll, and employee management, giving UAE businesses real-time inventory control and reporting across single or multi-branch operations.'],
        ['q' => 'How can I get a free demo of MultiTech POS in the UAE?', 'a' => 'Fill in the short form at the top of this page, or call or WhatsApp +971 58 897 9925. No credit card required, our UAE-based experts recommend the right POS system for your business.'],
    ];
@endphp

@if (config('site.ab_test'))
    @push('head')
        <script>window.dataLayer = window.dataLayer || []; dataLayer.push({ event: 'experiment_view', experiment: 'home_hero', variant: '{{ $variant }}' });</script>
    @endpush
@endif

@section('content')

@include('partials.schema-faq', ['faqs' => $faqs])


{{-- Hero --}}
<section class="home-hero">
    {{-- Moving colour blobs behind the hero --}}
    <div class="hero-blobs" aria-hidden="true">
        <span class="hero-blob hero-blob--blue"></span>
        <span class="hero-blob hero-blob--violet"></span>
        <span class="hero-blob hero-blob--teal"></span>
        <span class="hero-blob hero-blob--pink"></span>
        <span class="hero-grid"></span>
    </div>

    <div class="wrap home-hero__grid">
        <div class="home-hero__content">
            <span class="pill" data-reveal><span class="pill__dot"></span> Trusted by 500+ businesses in the UAE</span>
            @if ($variant === 'b')
                <h1 data-reveal>Faster Billing, Accurate Stock &amp; <span class="text-gradient">VAT-Ready Invoices</span> for UAE Businesses</h1>
            @else
                <h1 data-reveal>All-in-One <span class="text-gradient">POS Software</span> for Retail, Restaurants &amp; Growing Businesses</h1>
            @endif
            <p class="lead" data-reveal>Complete point-of-sale systems designed for retail, restaurants, supermarkets, and pharmacies. Streamline operations, boost sales, and grow your business.</p>

            <ul class="check-grid" data-reveal>
                <li><i class="fas fa-check"></i> Fast billing</li>
                <li><i class="fas fa-check"></i> Inventory control</li>
                <li><i class="fas fa-check"></i> Real-time reports</li>
                <li><i class="fas fa-check"></i> VAT &amp; FTA ready</li>
            </ul>

            <div class="btn-row" data-reveal>
                <a href="#hero-demo" class="btn btn--primary btn--lg-pad" data-focus-form>Book a Free Demo <i class="fas fa-arrow-right"></i></a>
                <a href="{{ config('site.whatsapp') }}" class="btn btn--light btn--lg-pad" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
            <ul class="hero-trust" data-reveal>
                <li><i class="fas fa-shield-alt"></i> No credit card required</li>
                <li><i class="fas fa-handshake"></i> Free consultation</li>
                <li><i class="fas fa-map-marker-alt"></i> UAE-based support</li>
            </ul>

            @if ($rating)
                <a href="{{ $rating['url'] ?? '#' }}" class="rating-badge" target="_blank" rel="noopener" data-reveal>
                    <i class="fab fa-google"></i>
                    <span class="rating-badge__stars" style="--score: {{ $rating['score'] }}" aria-hidden="true"></span>
                    <strong>{{ number_format($rating['score'], 1) }}</strong>
                    <span>from {{ $rating['count'] }} Google reviews</span>
                </a>
            @endif
        </div>

        {{-- Short demo form: name, phone and business type only --}}
        <div class="home-hero__media" data-reveal>
            <div class="hero-form" id="hero-demo" data-tilt>
                <p class="hero-form__eyebrow"><i class="fas fa-bolt"></i> Free demo &amp; consultation</p>
                <h2 class="hero-form__title">Get the right POS for your business</h2>
                <p class="hero-form__text">Leave your number and our UAE team will call you back to set up your free demo.</p>

                <form class="hero-form__form" action="{{ $base }}/request-demo" method="post">
                    @include('partials.form-extras')
                    <input type="hidden" name="message" value="Free demo request from the home page{{ config('site.ab_test') ? ' (version '.strtoupper($variant).')' : '' }}">
                    <label class="hero-form__field">
                        <span>Your name</span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed Khan" autocomplete="name" required>
                    </label>
                    <label class="hero-form__field">
                        <span>Phone / WhatsApp</span>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+971 5X XXX XXXX" autocomplete="tel" inputmode="tel" required>
                    </label>
                    <label class="hero-form__field">
                        <span>Business type</span>
                        <select name="service">
                            @foreach ($businesses as $business)
                                <option @selected(old('service') === $business['name'])>{{ $business['name'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn btn--primary btn--block hero-form__submit">{{ $variant === 'b' ? 'Get a Free Demo & Quote' : 'Get My Free Demo' }} <i class="fas fa-arrow-right"></i></button>
                </form>
                <p class="hero-form__note"><i class="fas fa-lock"></i> 100% free &middot; No obligation &middot; We never share your number</p>
            </div>

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
        <ul class="stat-bar" data-reveal>
            <li><strong data-count="500" data-suffix="+">500+</strong><span>Businesses served</span></li>
            <li><strong data-count="15" data-suffix="+">15+</strong><span>Years of experience</span></li>
            <li><strong data-count="30" data-suffix="+">30+</strong><span>Industries supported</span></li>
            <li><strong>24/7</strong><span>Local support</span></li>
        </ul>
    </div>
</section>

{{-- Client logos --}}
@include('partials.clients-band')

{{-- Business picker: pick your business, see what you get, book a demo for it --}}
<section class="home-section">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">Find Your Setup</span>
            <h2>What Kind of <span class="text-gradient">Business</span> Do You Run?</h2>
            <p>Pick your business and see what MultiTech POS does for it. Then book a free demo set up for your exact workflow.</p>
        </div>

        <div class="biz-picker" data-biz-picker data-reveal>
            <div class="biz-picker__tabs" role="tablist" aria-label="Business type">
                @foreach ($businesses as $i => $business)
                    <button type="button" class="biz-picker__tab" role="tab" id="biz-tab-{{ $i }}" aria-controls="biz-panel-{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" tabindex="{{ $i === 0 ? '0' : '-1' }}">
                        <span class="icon-tile icon-tile--{{ $business['tile'] }}"><i class="{{ $business['icon'] }}"></i></span>
                        {{ $business['label'] }}
                    </button>
                @endforeach
            </div>

            @foreach ($businesses as $i => $business)
                <div class="biz-picker__panel" role="tabpanel" id="biz-panel-{{ $i }}" aria-labelledby="biz-tab-{{ $i }}" @if ($i > 0) hidden @endif>
                    <div class="biz-picker__info">
                        <h3>POS for {{ $business['label'] === 'Something else' ? 'every other business' : $business['label'] }}</h3>
                        <p>{{ $business['text'] }}</p>
                        <ul class="check-list">
                            @foreach ($business['points'] as $point)
                                <li><i class="fas fa-check-circle"></i> {{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="biz-picker__actions">
                        <a href="#hero-demo" class="btn btn--primary" data-focus-form data-business="{{ $business['name'] }}">Get a demo for my business <i class="fas fa-arrow-right"></i></a>
                        <a href="{{ $base }}{{ $business['url'] }}" class="link-arrow">See all features <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- POS solutions --}}
<section class="home-section home-section--light">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">Our POS Solutions</span>
            <h2>POS Solutions for <span class="text-gradient">Every Business</span></h2>
            <p>Industry-specific point-of-sale systems designed to meet the unique needs of your business. From retail shops to pharmacies, we have you covered.</p>
        </div>

        <div class="solution-grid">
            <article class="solution" data-reveal data-tilt>
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

            <article class="solution" data-reveal data-tilt>
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

            <article class="solution" data-reveal data-tilt>
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

            <article class="solution" data-reveal data-tilt>
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
        <div class="home-head" data-reveal>
            <span class="tag">How It Works</span>
            <h2>Up and Running in <span class="text-gradient">3 Simple Steps</span></h2>
            <p>From your first call to your first sale, our UAE team handles the setup so you can focus on your customers.</p>
        </div>

        <ol class="steps">
            <li class="step" data-reveal>
                <span class="step__num">01</span>
                <span class="icon-tile icon-tile--md icon-tile--blue"><i class="fas fa-headset"></i></span>
                <h3>Book a free demo</h3>
                <p>Call, WhatsApp or fill in the form. We learn how your business works and show you the right system.</p>
            </li>
            <li class="step" data-reveal>
                <span class="step__num">02</span>
                <span class="icon-tile icon-tile--md icon-tile--green"><i class="fas fa-cogs"></i></span>
                <h3>Setup &amp; training</h3>
                <p>Get set up in as little as 48 hours. We move your data, configure your products, VAT and devices, and train your staff.</p>
            </li>
            <li class="step" data-reveal>
                <span class="step__num">03</span>
                <span class="icon-tile icon-tile--md icon-tile--amber"><i class="fas fa-chart-line"></i></span>
                <h3>Sell &amp; grow</h3>
                <p>Run faster checkouts, track stock and sales in real time, and add modules as your business grows.</p>
            </li>
        </ol>
    </div>
</section>

{{-- Why choose us --}}
<section class="home-section home-section--dark grid-lines">
    <div class="wrap">
        <div class="home-head home-head--dark" data-reveal>
            <span class="tag tag--dark">Why Choose Us</span>
            <h2>Why Choose <span class="text-gradient">MultiTech POS?</span></h2>
            <p>We’re committed to providing the best POS solutions and support for your business growth.</p>
        </div>

        <div class="why-grid">
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--blue"><i class="fas fa-award"></i></span>
                <h3>15+ Years Experience</h3>
                <p>Trusted by 500+ businesses across the UAE with proven expertise in POS solutions.</p>
            </div>
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--green"><i class="fas fa-headset"></i></span>
                <h3>24/7 Support</h3>
                <p>Round-the-clock technical support to keep your business running smoothly.</p>
            </div>
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--amber"><i class="fas fa-sliders-h"></i></span>
                <h3>Custom Solutions</h3>
                <p>Tailored POS systems designed specifically for your business requirements.</p>
            </div>
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--violet"><i class="fas fa-bolt"></i></span>
                <h3>Quick Setup</h3>
                <p>Get up and running in as little as 48 hours, with setup handled by our local team.</p>
            </div>
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--rose"><i class="fas fa-cash-register"></i></span>
                <h3>Quality Hardware</h3>
                <p>Reliable POS terminals, printers and scanners that work seamlessly with our software.</p>
            </div>
            <div class="why" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--cyan"><i class="fas fa-user-graduate"></i></span>
                <h3>Training Included</h3>
                <p>Hands-on training for your staff, so your team is confident from day one.</p>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials (only shown once real reviews are added in config/site.php) --}}
@include('partials.testimonials')

{{-- Plans: what each size of business gets (prices are quoted per setup) --}}
<section class="home-section">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">Plans &amp; Pricing</span>
            <h2>Pay Only for <span class="text-gradient">What You Need</span></h2>
            <p>Choose a one-time license or a monthly subscription. Your price depends on the modules, branches and users you need, so we quote every setup individually.</p>
        </div>

        <div class="plans">
            <article class="plan" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--blue"><i class="fas fa-store"></i></span>
                <h3>Single Outlet</h3>
                <p class="plan__for">One shop, restaurant, salon or pharmacy</p>
                <ul class="check-list">
                    <li><i class="fas fa-check-circle"></i> Fast billing with VAT-ready invoices</li>
                    <li><i class="fas fa-check-circle"></i> Stock, supplier and purchase tracking</li>
                    <li><i class="fas fa-check-circle"></i> Sales and profit reports</li>
                    <li><i class="fas fa-check-circle"></i> Works with your hardware or ours</li>
                </ul>
                <a href="#hero-demo" class="btn btn--outline-blue btn--block" data-focus-form>Get a Quote</a>
            </article>

            <article class="plan plan--featured" data-reveal data-tilt>
                <span class="plan__badge">For growing businesses</span>
                <span class="icon-tile icon-tile--md icon-tile--violet"><i class="fas fa-code-branch"></i></span>
                <h3>Multi-Branch</h3>
                <p class="plan__for">Two or more outlets or counters</p>
                <ul class="check-list">
                    <li><i class="fas fa-check-circle"></i> Everything in Single Outlet</li>
                    <li><i class="fas fa-check-circle"></i> Live stock across branches and transfers</li>
                    <li><i class="fas fa-check-circle"></i> Branch and combined reports</li>
                    <li><i class="fas fa-check-circle"></i> Staff roles and permissions</li>
                </ul>
                <a href="#hero-demo" class="btn btn--primary btn--block" data-focus-form>Get a Quote <i class="fas fa-arrow-right"></i></a>
            </article>

            <article class="plan" data-reveal data-tilt>
                <span class="icon-tile icon-tile--md icon-tile--teal"><i class="fas fa-building"></i></span>
                <h3>Enterprise &amp; Custom</h3>
                <p class="plan__for">Full ERP or industry-specific needs</p>
                <ul class="check-list">
                    <li><i class="fas fa-check-circle"></i> Accounting, payroll and HR modules</li>
                    <li><i class="fas fa-check-circle"></i> Gold, tailoring and van sales modules</li>
                    <li><i class="fas fa-check-circle"></i> Custom setup and integrations</li>
                    <li><i class="fas fa-check-circle"></i> Dedicated onboarding team</li>
                </ul>
                <a href="#hero-demo" class="btn btn--outline-blue btn--block" data-focus-form>Get a Quote</a>
            </article>
        </div>

        <p class="plans__note" data-reveal><i class="fas fa-info-circle"></i> Every plan includes setup, data migration from your old system and staff training.</p>
    </div>
</section>

{{-- Built for the UAE --}}
<section class="home-section home-section--light">
    <div class="wrap feature-split">
        <div class="feature-split__media" data-reveal>
            <img src="{{ $base }}/images/2026/06/3d-rendering-point-sale-system-store-management_493806-1407.webp" alt="Retail POS Software in UAE" width="626" height="626" loading="lazy">
        </div>
        <div class="feature-split__content" data-reveal>
            <span class="tag">Built for the UAE</span>
            <h2>Transforming Business Operations <span class="text-gradient">Across the UAE</span></h2>
            <p>From Abu Dhabi’s bustling retail districts to Dubai’s fast-paced hospitality scene, businesses are replacing manual processes with intelligent point-of-sale technology, and MultiTech POS is leading that shift. Our integrated POS software and ERP systems connect billing, stock control, accounting, and staff performance into a single platform, giving owners complete visibility over every branch from one dashboard.</p>
            <p>Slow checkouts, stock mismatches, and manual VAT calculations cost you more than just time, they cost you customers. MultiTech POS adapts to your industry, whether you’re in food and beverage, fashion, pharmacy, or salon services, with no complicated setup and no steep learning curve. Automated reporting reduces costly errors and smart inventory alerts prevent stockouts.</p>
            <ul class="check-list">
                <li><i class="fas fa-check-circle"></i> Billing, stock, accounting &amp; staff in one platform</li>
                <li><i class="fas fa-check-circle"></i> Every branch visible from one dashboard</li>
                <li><i class="fas fa-check-circle"></i> Smart alerts that prevent stockouts</li>
            </ul>
            <div class="btn-row">
                <a href="#hero-demo" class="btn btn--primary" data-focus-form>Book Free Demo <i class="fas fa-arrow-right"></i></a>
                <a href="tel:{{ config('site.phone') }}" class="btn btn--light"><i class="fas fa-phone-alt"></i> {{ config('site.phone_display') }}</a>
            </div>
        </div>
    </div>
</section>

{{-- Grows with your business --}}
<section class="home-section">
    <div class="wrap feature-split feature-split--reverse">
        <div class="feature-split__media" data-reveal>
            <img src="{{ $base }}/images/2026/06/female-staff-using-credit-card-terminal-cash-counter-50kb.webp" alt="Retail POS Software in UAE" width="648" height="432" loading="lazy">
        </div>
        <div class="feature-split__content" data-reveal>
            <span class="tag">Scalable</span>
            <h2>A POS Solution That <span class="text-gradient">Grows with Your Business</span></h2>
            <p>Most businesses outgrow their first billing system within a few years, but with MultiTech POS, you never have to start over. Our scalable POS solution is built to expand alongside you, whether that means adding new counters, opening additional branches, or moving from retail into wholesale and distribution.</p>
            <p>Start with the essentials like fast checkout and barcode scanning, then activate advanced modules such as payroll, customer loyalty, and warehouse management as your needs evolve. Because every upgrade happens within the same point-of-sale platform, your historical sales data, product catalogue, and customer records stay intact. It’s a future-ready investment for UAE businesses that plan to grow, backed by a local team that grows with you.</p>
            <div class="btn-row">
                <a href="tel:{{ config('site.phone') }}" class="btn btn--primary"><i class="fas fa-phone-alt"></i> Speak With an Expert</a>
            </div>
        </div>
    </div>
</section>

{{-- Industries --}}
<section class="home-section home-section--light">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">Industries</span>
            <h2>Industries <span class="text-gradient">We Serve</span></h2>
            <p>Our POS solutions are trusted by businesses across diverse industries in the UAE and Middle East.</p>
        </div>

        <div class="industry-grid">
            <a href="{{ $base }}/retail-pos-software-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--blue"><i class="fas fa-shopping-cart"></i></span><span class="industry__name">Retail Shops</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/food-restaurant-pos-software-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--orange"><i class="fas fa-utensils"></i></span><span class="industry__name">Restaurants</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/grocery-pos-system-in-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--purple"><i class="fas fa-shopping-basket"></i></span><span class="industry__name">Supermarkets</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/pharmacy-pos-system-in-the-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--green"><i class="far fa-hospital"></i></span><span class="industry__name">Pharmacies</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/fashion-pos-system-in-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--pink"><i class="fas fa-tshirt"></i></span><span class="industry__name">Fashion Boutiques</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/bakery-pos-software/" class="industry" data-reveal><span class="icon-tile icon-tile--amber"><i class="fas fa-coffee"></i></span><span class="industry__name">Cafes &amp; Bakeries</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/salon-spa-pos-software-uae/" class="industry" data-reveal><span class="icon-tile icon-tile--sky"><i class="fas fa-spa"></i></span><span class="industry__name">Salons &amp; Spas</span><i class="fas fa-arrow-right industry__arrow"></i></a>
            <a href="{{ $base }}/gas-station-pos-software/" class="industry" data-reveal><span class="icon-tile icon-tile--slate"><i class="fas fa-gas-pump"></i></span><span class="industry__name">Fuel Stations</span><i class="fas fa-arrow-right industry__arrow"></i></a>
        </div>

        <div class="home-center">
            <a href="{{ $base }}/more-pos-industries/" class="btn btn--outline-blue">See All 30+ Industries <i class="fas fa-arrow-right"></i></a>
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

@endsection
