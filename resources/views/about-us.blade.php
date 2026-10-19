@extends('layouts.app')

@section('title', 'About Us | UAE POS & ERP Software Company | MultiTech POS')
@section('description', 'MultiTech POS is a UAE-based provider of VAT-compliant POS and ERP software with 15+ years of experience serving retail, restaurant, salon and service businesses.')
@section('og_image', $base . '/wp-content/uploads/2026/02/Hotel-POS-Software-3.jpg')
@section('og_type', 'article')

@section('content')

@include('partials.page-hero', ['title' => 'About Us', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg.jpeg'])

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack p-10">
        <h2 class="title">About <span class="text-gradient">MultiTech POS</span></h2>
        <div class="text">
          Welcome to MultiTech POS, a leading provider of smart, reliable, and fully UAE-compliant Point of Sale (POS) solutions designed to empower businesses across the United Arab Emirates. With over 15+ years of experience supporting retailers, restaurants, supermarkets, service providers, and multi-branch enterprises, we are committed to delivering cutting-edge technology that helps businesses streamline operations, increase efficiency, and accelerate growth.
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/02/Hotel-POS-Software-3.webp" alt="" class="img img-frame ar-324-185" width="648" height="370" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack p-10">
        <h2 class="title">Our <span class="text-gradient">Mission</span></h2>
        <div class="text">
          At MultiTech POS, our mission is simple
        </div>
        <ul class="icon-list">
          <li><i class="fas fa-check"></i> Simplify business operations through intuitive and adaptable technology.</li>
          <li><i class="fas fa-check"></i> Provide dependable support with 24/7 service from UAE-based experts.</li>
          <li><i class="fas fa-check"></i> Empower growth by enabling real-time insights, smart reporting, and scalable solutions tailored to your business.</li>
        </ul>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/02/2150273194.webp" alt="" class="img img-frame" width="648" height="431" loading="lazy">
      </div>
    </div>
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack p-10">
        <h2 class="title">Who <span class="text-gradient">We Are</span></h2>
        <div class="text">
          Founded with a vision to transform retail and service industries, MultiTech POS has become one of the most trusted POS providers in the UAE. Our portfolio includes powerful POS software and hardware solutions that serve businesses of all sizes — from small boutique shops to large enterprise operations. Whether you’re based in Dubai, Abu Dhabi, Sharjah, or any of the Emirates, our systems are tailored to meet local market needs, including UAE VAT compliance, Arabic/English language support, and secure FTA-ready billing.
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/01/Best-Appointment-Booking-POS-In-UAE.webp" alt="" class="img img-frame" width="648" height="432" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="tag-wrap"><span class="tag">Industries</span></div>
    <h3 class="title lh-1_3 text-center">Industries <span class="text-gradient">We Serve</span></h3>
    <div class="text fw-500 text-center mw-997 mx-auto">
      Our POS solutions are trusted by businesses across diverse industries in the UAE and Middle East.
    </div>
    <div class="cols cols-4 m-cols-2">
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--blue"><i class="fas fa-shopping-cart"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/retail-pos-software-uae/">Retail Shops</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--orange"><i class="fas fa-utensils"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/food-restaurant-pos-software-uae/">Restaurant</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--purple"><i class="fas fa-shopping-basket"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/grocery-pos-system-in-uae/">Supermarkets</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--green"><i class="far fa-hospital"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/pharmacy-pos-system-in-the-uae/">Pharmacies</a></p>
        </div>
      </div>
    </div>
    <div class="cols cols-4 m-cols-2">
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--pink"><i class="fas fa-tshirt"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/fashion-pos-system-in-uae/">Fashion Boutiques</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--amber"><i class="fas fa-coffee"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/chocolate-shop-pos-system-in-the-uae/">Cafes &amp; Bakeries</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--sky"><i class="fas fa-spa"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/salon-spa-pos-software-uae/">Salons &amp; Spas</a></p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--slate"><i class="fas fa-gas-pump"></i></span>
          <p class="icon-box__title fs-20 lh-1_4"><a href="{{ $base }}/service-business-pos-software-uae/">Fuel Stations</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--light section--p32">
  <div class="container">
    <div class="stack p-10">
      <div class="stack p-10">
        <div class="logo-slider" data-slider style="--show:5;--show-tablet:5;--show-mobile:1">
          <div class="logo-slider__track">
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/theivy-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="theivy" loading="lazy"></div>
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/bazaar-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="bazaar" loading="lazy"></div>
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/brain-games-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="brain-games" loading="lazy"></div>
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/candadian-pizza-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="candadian-pizza" loading="lazy"></div>
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/emd-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="emd" loading="lazy"></div>
            <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/maras-turka-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="maras-turka" loading="lazy"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container items-center justify-center">
    <div class="stack card card--sh4 p-40 mw-1097 mx-auto">
      <h3 class="title text-center">Still Have <span class="text-gradient"> Questions?</span></h3>
      <div class="text fw-500 text-center mw-741 mx-auto">
        <p>Our team is ready to help you find the perfect POS solution for your business. Get in touch with us through your preferred channel.</p>
      </div>
      <div class="cols cols-3 p-10">
        <div class="stack gap-10 card card--sh4">
          <div class="icon-box text-center p-32">
            <span class="icon-tile icon-tile--44 icon-tile--whatsapp icon-tile--round"><i class="far fa-comment"></i></span>
            <p class="icon-box__title"><a href="http://wa.me/+971588979925" target="_blank" rel="noopener">Whatsapp</a></p>
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
