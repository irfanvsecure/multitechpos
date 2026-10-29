@extends('layouts.app')

@section('title', 'POS Software for Every Business Size in UAE | MultiTech POS')
@section('description', 'Scalable POS software for small businesses, multi-branch chains and enterprises in the UAE. Start with the essentials and add modules as you grow.')
@section('og_image', $base . '/images/2026/01/magnifying-glass-with-graphics-flat-style_23-2147784204.jpg')
@section('og_type', 'article')

@section('content')

@include('partials.page-hero', ['title' => 'POS Solutions by Business Size', 'image' => '/images/2025/12/freepik-export-20240628023510yeGg.jpeg', 'parent' => ['name' => 'POS Software', 'url' => '/pos-software-uae/']])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 p-10">
        <h2 class="title">The Right POS Solution <span class="text-gradient">for Every Stage of Growth</span></h2>
        <div class="text">
          Whether you’re launching a new business, managing multiple locations, or operating a large enterprise, MultiTech POS provides scalable solutions designed to match your business requirements. Our flexible POS software grows with your organization, helping you streamline operations, improve efficiency, and gain complete control over your business.
        </div>
        <a href="tel:+971588979925" class="btn btn--sm">Talk To Expert <i class="fas fa-phone-alt"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2026/01/magnifying-glass-with-graphics-flat-style_23-2147784204.webp" alt="" class="img img-frame ar-324-163" width="648" height="326" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">Small <span class="text-gradient">Business POS</span></h2>
        <h3 class="title fs-28 lh-1_71">Simple, Affordable <span class="text-gradient">&amp; Powerful</span></h3>
        <div class="text">
          Designed specifically for startups and growing businesses, our Small Business POS solution helps you manage sales, inventory, customers, and reporting from a single platform.
        </div>
        <h3 class="title fs-28 lh-1_71">Key <span class="text-gradient">Features</span></h3>
        <div class="text">
          <ul>
          <li>Fast and easy billing</li>
          <li>Inventory management</li>
          <li>Barcode scanning</li>
          <li>Customer management</li>
          <li>Multiple payment methods</li>
          <li>Real-time sales reports</li>
          <li>Low stock alerts</li>
          <li>Mobile reporting access</li></ul>
        </div>
        <div class="text">
          The system provides inventory tracking, sales management, and reporting tools that help small businesses operate efficiently while preparing for future growth.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/small-business-pos-uae/" class="btn btn--sm">View More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2025/12/1.webp" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

@include('partials.service-why', ['service' => 'POS Solutions by Business Size'])

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">Multi-Branch <span class="text-gradient">Business POS</span></h2>
        <h3 class="title fs-28 lh-1_71">Manage Multiple Locations from <span class="text-gradient">One Centralized System</span></h3>
        <div class="text">
          As your business expands, managing multiple locations becomes more complex. MultiTech’s Multi-Branch POS software centralizes operations, allowing you to monitor sales, inventory, employees, and performance across all branches from a single dashboard.
        </div>
        <h3 class="title fs-28 lh-1_71">Key <span class="text-gradient">Features</span></h3>
        <div class="text">
          <ul>
          <li>Centralized branch management</li>
          <li>Real-time inventory synchronization</li>
          <li>Multi-location reporting</li>
          <li>Branch performance monitoring</li>
          <li>Employee management</li>
          <li>Customer loyalty management</li>
          <li>Inter-branch stock transfers</li>
          <li>Cloud-based access</li></ul>
        </div>
        <div class="text">
          With consolidated reporting and centralized control, business owners can manage multiple locations efficiently while maintaining consistency across all branches.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/multi-branch-business-pos-uae/" class="btn btn--sm">View More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2025/12/home_software_slide2.webp" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">Enterprise POS <span class="text-gradient">Restaurant POS</span></h2>
        <h3 class="title fs-28 lh-1_71">Advanced POS Software <span class="text-gradient">for Large Organizations</span></h3>
        <div class="text">
          Enterprise businesses require powerful technology capable of handling high transaction volumes, multiple departments, complex inventory structures, and large teams. MultiTech Enterprise POS delivers a scalable platform built for growing organizations and corporate operations.
        </div>
        <h3 class="title fs-28 lh-1_71">Key <span class="text-gradient">Features</span></h3>
        <div class="text">
          <ul>
          <li>Centralized enterprise dashboard</li>
          <li>Real-time inventory synchronization</li>
          <li>Multi-warehouse management</li>
          <li>Advanced reporting &amp; analytics</li>
          <li>Employee role management</li>
          <li>Customer relationship management</li>
          <li>Enterprise-grade security</li>
          <li>Multi-location operations management</li></ul>
        </div>
        <div class="text">
          Our Enterprise POS solution enables organizations to maintain complete control over operations while supporting continued growth and expansion.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/enterprise-pos-uae/" class="btn btn--sm">View More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2025/12/2.webp" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--dark grid-lines">
  <div class="container">
    <div class="cols cols--50-50">
      <div class="stack gap-10 items-center justify-center p-10">
        <h3 class="title c-white text-center mw-475 mx-auto"> Try out <span class="text-gradient">free version</span></h3>
        <h3 class="title fs-30 fw-400 c-white text-center">Our free version covers all basic sales functions.</h3>
        <div class="text mw-212 mx-auto">
          Support is not Included
        </div>
      </div>
      <div class="stack gap-10 items-center justify-center">
        <div class="text mw-337 mx-auto">
          Fully compatible with Windows 7,8,10
        </div>
        <a href="tel:+971588979925" class="btn btn--sm">Talk To Expert <i class="fas fa-arrow-down"></i></a>
      </div>
    </div>
  </div>
</section>

@include('partials.service-quote', ['service' => 'POS Solutions by Business Size'])

@include('partials.cta-band')

@endsection
