@extends('layouts.app')

@section('title', 'Blog | POS, VAT & Retail Guides for UAE | MultiTech POS')
@section('description', 'Practical guides on POS software, VAT and e-invoicing, inventory and retail operations for businesses in the UAE, from the MultiTech POS team.')
@section('og_type', 'article')

@section('content')

@include('partials.page-hero', ['title' => 'Our Blogs', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg.jpeg'])

@include('partials.clients-band')

<section class="section section--p10">
  <div class="container">
    <div class="post-grid">
      <article class="post-card">
        <a href="{{ $base }}/gold-jewelry-pricing-uae/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/gold-jewelry-pricing-uae-300x240.webp" alt="Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/gold-jewelry-pricing-uae/">Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT</a></h3>
        <p class="post-card__excerpt">Gold jewelry pricing in the UAE explained: daily gold rate, making charges, 5% VAT and reverse charge rules, plus a POS setup checklist for jewelry shops.</p>
        <a href="{{ $base }}/gold-jewelry-pricing-uae/" class="post-card__more">Read More &raquo;</a>
      </article>
      <article class="post-card">
        <a href="{{ $base }}/pharmacy-expiry-date-management/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/pharmacy-expiry-date-management-300x200.webp" alt="Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/pharmacy-expiry-date-management/">Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs</a></h3>
        <p class="post-card__excerpt">Pharmacy expiry date management with FEFO: what UAE rules say about expired medicines, near-expiry windows and a monthly routine that cuts write-offs.</p>
        <a href="{{ $base }}/pharmacy-expiry-date-management/" class="post-card__more">Read More &raquo;</a>
      </article>
      <article class="post-card">
        <a href="{{ $base }}/uae-e-invoicing-for-distributors/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/10/uae-e-invoicing-for-distributors-300x200.webp" alt="UAE E-Invoicing 2027: What Distributors and Van Sales Teams Must Do Now" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/uae-e-invoicing-for-distributors/">UAE E-Invoicing 2027: What Distributors and Van Sales Teams Must Do Now</a></h3>
        <p class="post-card__excerpt">UAE e-invoicing for distributors: ASP deadlines, 2027 go-live dates, van returns as credit notes and the buyer gap period, plus a 90-day action plan.</p>
        <a href="{{ $base }}/uae-e-invoicing-for-distributors/" class="post-card__more">Read More &raquo;</a>
      </article>
      <article class="post-card">
        <a href="{{ $base }}/restaurant-menu-engineering-uae/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/restaurant-menu-engineering-uae-300x200.webp" alt="Menu Engineering for UAE Restaurants: Using POS Data to Price and Place Every Dish" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/restaurant-menu-engineering-uae/">Menu Engineering for UAE Restaurants: Using POS Data to Price and Place Every Dish</a></h3>
        <p class="post-card__excerpt">A practical guide to restaurant menu engineering in the UAE: contribution margin versus food cost, the VAT adjustment most operators miss, a worked example, and how POS data makes it routine.</p>
        <a href="{{ $base }}/restaurant-menu-engineering-uae/" class="post-card__more">Read More &raquo;</a>
      </article>
      <article class="post-card">
        <a href="{{ $base }}/what-makes-a-tax-invoice-valid/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/uae-tax-invoice-requirements-receipt-300x200.webp" alt="UAE Tax Invoice Requirements: What Your POS Receipt Must Show" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/what-makes-a-tax-invoice-valid/">UAE Tax Invoice Requirements: What Your POS Receipt Must Show</a></h3>
        <p class="post-card__excerpt">Most UAE POS receipts are simplified tax invoices, and many are missing a mandatory field. The exact particulars Article 59 requires, when a full invoice is needed, and what a defective one costs.</p>
        <a href="{{ $base }}/what-makes-a-tax-invoice-valid/" class="post-card__more">Read More &raquo;</a>
      </article>
      <article class="post-card">
        <a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/" class="post-card__img"><img src="{{ $base }}/wp-content/uploads/2026/09/uae-e-invoicing-what-it-means-for-pos-300x200.webp" alt="UAE E-Invoicing: What the 2027 Mandate Means for Your POS System" loading="lazy"></a>
        <h3 class="post-card__title"><a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/">UAE E-Invoicing: What the 2027 Mandate Means for Your POS System</a></h3>
        <p class="post-card__excerpt">The UAE Electronic Invoicing System goes live in phases from 2027. What Ministerial Decisions 243 and 244 mean for UAE retailers, and why B2C exclusion does not mean you are out of scope.</p>
        <a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/" class="post-card__more">Read More &raquo;</a>
      </article>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
