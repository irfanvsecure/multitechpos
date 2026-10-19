@extends('layouts.app')

@section('title', 'Articles by multitech_admin | MultiTech POS Blog')
@section('description', 'POS, VAT, e-invoicing and retail guides for UAE businesses, written by the MultiTech POS team.')
@section('og_image', 'https://secure.gravatar.com/avatar/79d482aefa5528f2737ec969bc8e0b3815ea767c9f2f1deb9903d81834858b41?s=500&d=mm&r=g')
@section('og_type', 'profile')
@section('body_class', 'page-post')

@section('content')
<div class="archive">
    <header class="archive__header">
        <h1>Author name: multitech_admin</h1>
        <img src="https://secure.gravatar.com/avatar/79d482aefa5528f2737ec969bc8e0b3815ea767c9f2f1deb9903d81834858b41?s=120&#038;d=mm&#038;r=g" alt="" width="80" height="80" class="archive__avatar">
    </header>
    <div class="archive__grid">
    <article class="archive-card">
        <a href="{{ $base }}/gold-jewelry-pricing-uae/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/gold-jewelry-pricing-uae-1024x819.jpg" alt="Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">Jewelry POS</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/gold-jewelry-pricing-uae/">Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT</a></h2>
            <div class="archive-card__meta">multitech_admin / October 14, 2026</div>
            <p class="archive-card__excerpt">Gold jewelry pricing in the UAE explained: daily gold rate, making charges, 5% VAT and reverse charge rules, plus a POS setup checklist for jewelry shops.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/pharmacy-expiry-date-management/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/pharmacy-expiry-date-management-1024x683.jpg" alt="Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">Pharmacy POS</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/pharmacy-expiry-date-management/">Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs</a></h2>
            <div class="archive-card__meta">multitech_admin / October 14, 2026</div>
            <p class="archive-card__excerpt">Pharmacy expiry date management with FEFO: what UAE rules say about expired medicines, near-expiry windows and a monthly routine that cuts write-offs.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/uae-e-invoicing-for-distributors/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/uae-e-invoicing-for-distributors-1024x683.jpg" alt="UAE E-Invoicing 2027: What Distributors and Van Sales Teams Must Do Now" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">VAT &amp; E-Invoicing</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/uae-e-invoicing-for-distributors/">UAE E-Invoicing 2027: What Distributors and Van Sales Teams Must Do Now</a></h2>
            <div class="archive-card__meta">multitech_admin / October 14, 2026</div>
            <p class="archive-card__excerpt">UAE e-invoicing for distributors: ASP deadlines, 2027 go-live dates, van returns as credit notes and the buyer gap period, plus a 90-day action plan.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/restaurant-menu-engineering-uae/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/restaurant-menu-engineering-uae.jpg" alt="Menu Engineering for UAE Restaurants: Using POS Data to Price and Place Every Dish" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">Restaurant POS, Uncategorized</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/restaurant-menu-engineering-uae/">Menu Engineering for UAE Restaurants: Using POS Data to Price and Place Every Dish</a></h2>
            <div class="archive-card__meta">multitech_admin / September 26, 2026</div>
            <p class="archive-card__excerpt">A practical guide to restaurant menu engineering in the UAE: contribution margin versus food cost, the VAT adjustment most operators miss, a worked example, and how POS data makes it routine.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/what-makes-a-tax-invoice-valid/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/uae-tax-invoice-requirements-receipt-1024x683.jpg" alt="UAE Tax Invoice Requirements: What Your POS Receipt Must Show" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">VAT &amp; E-Invoicing</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/what-makes-a-tax-invoice-valid/">UAE Tax Invoice Requirements: What Your POS Receipt Must Show</a></h2>
            <div class="archive-card__meta">multitech_admin / September 22, 2026</div>
            <p class="archive-card__excerpt">Most UAE POS receipts are simplified tax invoices, and many are missing a mandatory field. The exact particulars Article 59 requires, when a full invoice is needed, and what a defective one costs.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/uae-e-invoicing-what-it-means-for-pos-1024x683.jpg" alt="UAE E-Invoicing: What the 2027 Mandate Means for Your POS System" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">VAT &amp; E-Invoicing</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/">UAE E-Invoicing: What the 2027 Mandate Means for Your POS System</a></h2>
            <div class="archive-card__meta">multitech_admin / September 22, 2026</div>
            <p class="archive-card__excerpt">The UAE Electronic Invoicing System goes live in phases from 2027. What Ministerial Decisions 243 and 244 mean for UAE retailers, and why B2C exclusion does not mean you are out of scope.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/your-pos-data-is-not-web-ready/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/product-data-ready-for-woocommerce-1024x682.jpg" alt="Getting Your Product Data Ready for WooCommerce" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">WooCommerce Integration</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/your-pos-data-is-not-web-ready/">Getting Your Product Data Ready for WooCommerce</a></h2>
            <div class="archive-card__meta">multitech_admin / September 12, 2026</div>
            <p class="archive-card__excerpt">Your POS catalogue was built for scanning, not for selling. What has to change about names, images, attributes and identifiers before your products go on WooCommerce.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/why-your-daily-totals-do-not-match/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/reconciling-payments-uae-retail-1024x684.jpg" alt="Reconciling Online and In-Store Payments: Making the Daily Close Work" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">WooCommerce Integration</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/why-your-daily-totals-do-not-match/">Reconciling Online and In-Store Payments: Making the Daily Close Work</a></h2>
            <div class="archive-card__meta">multitech_admin / September 10, 2026</div>
            <p class="archive-card__excerpt">Online and in-store payments settle differently, which is why the daily close stops matching. How UAE retailers reconcile gateways, cash and card across both channels.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/one-customer-two-records/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/single-customer-view-uae-retail-1024x683.jpg" alt="The Single Customer View: Connecting Loyalty Across Store and WooCommerce" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">WooCommerce Integration</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/one-customer-two-records/">The Single Customer View: Connecting Loyalty Across Store and WooCommerce</a></h2>
            <div class="archive-card__meta">multitech_admin / September 4, 2026</div>
            <p class="archive-card__excerpt">Most UAE retailers hold the same shopper as two unrelated records. What it costs, how to merge them properly, and what UAE data protection law expects when you do.</p>
        </div>
    </article>
    <article class="archive-card">
        <a href="{{ $base }}/when-online-orders-come-back-to-the-store/" class="archive-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/omnichannel-returns-uae-683x1024.jpg" alt="Returns and Refunds Across Channels: Handling Online Orders at the Counter" loading="lazy"></a>
        <div class="archive-card__body">
            <span class="archive-card__cat">WooCommerce Integration</span>
            <h2 class="archive-card__title"><a href="{{ $base }}/when-online-orders-come-back-to-the-store/">Returns and Refunds Across Channels: Handling Online Orders at the Counter</a></h2>
            <div class="archive-card__meta">multitech_admin / September 3, 2026</div>
            <p class="archive-card__excerpt">Online returns run higher than in-store returns, and most of them come back to a counter. How UAE retailers should handle cross-channel returns, refunds and restocking.</p>
        </div>
    </article>
    </div>
    <nav class="pagination"><span class="current">1</span> <a href="{{ $base }}/author/multitech_admin/page/2/">2</a> <span>&hellip;</span> <a href="{{ $base }}/author/multitech_admin/page/4/">4</a> <a href="{{ $base }}/author/multitech_admin/page/2/">Next </a></nav>
</div>
@endsection
