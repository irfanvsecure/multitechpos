@extends('layouts.app')

@section('title', 'Best Medical Spa POS Software in UAE | MultiTech POS')
@section('description', 'Manage bookings, billing & treatment records with MultiTech POS – the smart medspa POS built for UAE clinics. Book a free demo today!')
@section('og_image', $base . '/wp-content/uploads/2026/06/medical-spa-pos-1.webp')
@section('og_type', 'article')

@section('content')

@include('partials.schema-faq', ['faqs' => [
    ['q' => 'What is Medical Spa POS Software?', 'a' => 'It is a complete clinic management and billing system designed specifically for aesthetic and medical spa businesses.'],
    ['q' => 'Is the system VAT compliant in UAE?', 'a' => 'Yes, the software generates VAT-compliant invoices and reports as per UAE FTA regulations.'],
    ['q' => 'Can I manage multiple branches?', 'a' => 'Yes, our system supports multi-branch management with centralized reporting.'],
    ['q' => 'Does it handle patient records securely?', 'a' => 'Yes, patient data is securely stored with encrypted cloud protection.'],
    ['q' => 'Can it calculate staff commissions automatically?', 'a' => 'Yes, commission rules can be set for treatments and product sales.'],
]])

@include('partials.page-hero', ['title' => 'Medical Spa POS Software In UAE', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg-1.jpeg', 'parent' => ['name' => 'Industries', 'url' => '/more-pos-industries/']])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">Medical Spa POS Software in UAE</h2>
        <div class="text">
          Our Medical Spa POS Software in UAE is an all-in-one management solution designed specifically for medical spas, aesthetic clinics, dermatology centers, cosmetic practices, laser clinics, and wellness centers across Dubai, Abu Dhabi, Sharjah, and all Emirates.
        </div>
        <div class="text">
          From treatment billing and appointment scheduling to patient records and VAT-compliant invoicing, our system simplifies daily operations while enhancing client experience and clinic profitability.
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Book Your Free Demo Today <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/06/medical-spa-pos-1.webp" alt="Medical Spa POS Software in UAE" class="img r-30 ar-162-125" width="648" height="500" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="title text-center m-text-left">Powerful Features of <br>Our Medical Spa POS Software</h2>
    <div class="text-center"><a href="tel:+971588979925" class="btn btn--sm"><i class="fas fa-phone-alt"></i> Speak With Us</a></div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <h2 class="title text-center">Explore All Features</h2>
    <div class="cols cols-3">
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/barcode-1.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Smart Appointment &amp; Scheduling System</p>
          <p class="icon-box__text">Manage consultations, treatment sessions, follow-ups, and recurring bookings with an intelligent calendar system.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/cash-register-1.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Patient Records &amp; Treatment History</p>
          <p class="icon-box__text">Maintain detailed digital patient profiles including</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/dollar-1.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Multi-Property &amp; Multi-Outlet Support</p>
          <p class="icon-box__text">Manage multiple properties, outlets, departments and billing points from a single dashboard — ideal for hotel chains and large resorts.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/shopping-basket-1.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Inventory &amp; Resource Control</p>
          <p class="icon-box__text">Track stock for restaurants, minibar supplies, housekeeping, spa, and retail outlets in real time — reduce waste and optimize replenishment.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/network-2.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Real-Time Reports &amp; Analytics</p>
          <p class="icon-box__text">Get instant, VAT-ready reports covering revenue, occupancy, sales by outlet and performance insights to make smarter business decisions.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/company-1.webp" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Guest Profiles, Loyalty &amp; Promotions</p>
          <p class="icon-box__text">Build detailed guest profiles, loyalty programs and customized offers to boost repeat stays and personalized guest experiences.</p>
        </div>
      </div>
    </div>
    <div class="text-center"><a href="tel:+971588979925" class="btn btn--sm"><i class="fas fa-phone-alt"></i> Speak With Us</a></div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 cols--reverse m-reverse p-10">
      <div class="stack justify-center p-10">
        <h3 class="title">Benefits for Hoteliers in UAE</h3>
        <div class="text">
          Switching to our Hotel POS Software gives you:
        </div>
        <ul class="icon-list">
          <li><i class="fas fa-check"></i> Fast, accurate billing across rooms &amp; services</li>
          <li><i class="fas fa-check"></i> Centralized management for multiple outlets &amp; properties</li>
          <li><i class="fas fa-check"></i> Full support for UAE VAT billing &amp; reporting</li>
          <li><i class="fas fa-check"></i> Better guest engagement with CRM and loyalty tools</li>
          <li><i class="fas fa-check"></i> Real-time analytics and dashboards</li>
          <li><i class="fas fa-check"></i> Enhanced operational control for front desk, F&amp;B &amp; service counters</li>
          <li><i class="fas fa-check"></i> Scalable cloud-ready platform for growing hotel groups</li>
        </ul>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Get a Free Demo <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/06/medical-spa-pos-2.webp" alt="Medical Spa POS Software in UAE" class="img r-30" width="643" height="480" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="tag-wrap"><span class="tag">Industries</span></div>
    <h2 class="title lh-1_3 text-center">Why Choose Our Medical Spa POS Software in UAE?</h2>
    <div class="cols cols-3">
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-file-invoice"></i></span>
          <p class="icon-box__title">Built for UAE Market</p>
          <p class="icon-box__text">Fully compliant with local VAT regulations and billing standards.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-check"></i></span>
          <p class="icon-box__title lh-1_2">Secure &amp; Confidential</p>
          <p class="icon-box__text">End-to-end data encryption with secure cloud backup.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-47 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--green"><i class="far fa-money-bill-alt"></i></span>
          <p class="icon-box__title">Easy to Use</p>
          <p class="icon-box__text">Minimal training required for front desk &amp; clinical staff.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--60 icon-tile--pink"><i class="fas fa-headphones"></i></span>
          <p class="icon-box__title lh-1_2">Customizable for Aesthetic Clinics</p>
          <p class="icon-box__text c-grey">Designed specifically for Botox, fillers, laser, dermatology, slimming treatments, and cosmetic procedures.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-question"></i></span>
          <p class="icon-box__title">Dedicated Local Support</p>
          <p class="icon-box__text">UAE-based support team for installation, training, and ongoing assistance.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-toggle-off"></i></span>
          <p class="icon-box__title">Reliable Support</p>
          <p class="icon-box__text">Our team is ready to assist with technical help and advice.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h3 class="title">Designed for All Types of  Medical &amp; Aesthetic Centers</h3>
        <div class="text">
          Our solution is ideal for:
        </div>
        <ul class="icon-list">
          <li><i class="fas fa-dot-circle"></i> Medical Spas</li>
          <li><i class="fas fa-dot-circle"></i> Aesthetic Clinics</li>
          <li><i class="fas fa-dot-circle"></i> Dermatology Clinics</li>
          <li><i class="fas fa-dot-circle"></i> Cosmetic Surgery Centers</li>
          <li><i class="fas fa-dot-circle"></i> Laser &amp; Hair Removal Clinics</li>
          <li><i class="fas fa-dot-circle"></i> Slimming &amp; Wellness Clinics</li>
          <li><i class="fas fa-dot-circle"></i> Anti-Aging Centers</li>
        </ul>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Get a Free Demo <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/06/medical-spa-pos-3.webp" alt="Medical Spa POS Software in UAE" class="img r-30" width="648" height="484" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="tag-wrap"><span class="tag">Help Center</span></div>
    <h2 class="title text-center">Frequently Asked Questions (FAQs)in UAE</h2>
    <div class="stack p-10">
      <div class="faq mw-1144 mx-auto">
        <details open>
          <summary>What is Medical Spa POS Software?</summary>
          <div class="faq__body">
            It is a complete clinic management and billing system designed specifically for aesthetic and medical spa businesses.
          </div>
        </details>
        <details>
          <summary>Is the system VAT compliant in UAE?</summary>
          <div class="faq__body">
            Yes, the software generates VAT-compliant invoices and reports as per UAE FTA regulations.
          </div>
        </details>
        <details>
          <summary>Can I manage multiple branches?</summary>
          <div class="faq__body">
            Yes, our system supports multi-branch management with centralized reporting.
          </div>
        </details>
        <details>
          <summary>Does it handle patient records securely?</summary>
          <div class="faq__body">
            Yes, patient data is securely stored with encrypted cloud protection.
          </div>
        </details>
        <details>
          <summary>Can it calculate staff commissions automatically?</summary>
          <div class="faq__body">
            Yes, commission rules can be set for treatments and product sales.
          </div>
        </details>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container items-center justify-center">
    <div class="stack card card--sh4 p-40 mw-1097 mx-auto">
      <h2 class="title text-center">Still Have  Questions?</h2>
      <div class="text fw-500 text-center mw-741 mx-auto">
        Our team is ready to help you find the perfect POS solution for your business. Get in touch with us through your preferred channel.
      </div>
      <div class="cols cols-3 p-10">
        <div class="stack gap-10 card card--sh4">
          <div class="icon-box text-center p-32">
            <span class="icon-tile icon-tile--44 icon-tile--whatsapp icon-tile--round"><i class="far fa-comment"></i></span>
            <p class="icon-box__title"><a href="tel:+971588979925">Whatsapp</a></p>
            <p class="icon-box__text">Quick Response</p>
          </div>
        </div>
        <div class="stack gap-10 card card--sh4">
          <div class="icon-box text-center p-32">
            <span class="icon-tile icon-tile--44 icon-tile--indigo icon-tile--round"><i class="fas fa-phone-alt"></i></span>
            <p class="icon-box__title"><a href="tel:+971588979925">Call Us</a></p>
            <p class="icon-box__text">+971588979925</p>
          </div>
        </div>
        <div class="stack gap-10 card card--sh4">
          <div class="icon-box text-center p-32">
            <span class="icon-tile icon-tile--44 icon-tile--indigo icon-tile--round"><i class="far fa-envelope"></i></span>
            <p class="icon-box__title"><a href="mailto:support@multitechpos.com">Email</a></p>
            <p class="icon-box__text">support@multitechpos.com</p>
          </div>
        </div>
      </div>
      <div class="text-center"><a href="{{ $base }}/contact/" class="btn btn--sm">Request a Free Demo <i class="fas fa-arrow-right"></i></a></div>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
