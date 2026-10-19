@extends('layouts.app')

@section('title', 'POS Features | Reports, Inventory & More | MultiTech POS')
@section('description', 'Explore MultiTech POS features: mobile reporting, inventory, multi-location sync, appointments, table management, WooCommerce and paperless invoicing.')

@section('content')

@include('partials.page-hero', ['title' => 'Our POS Features', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg.jpeg'])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 p-10">
        <h2 class="title">Measure Everything  <span class="text-gradient">With A Few Clicks</span></h2>
        <div class="text">
          Online mobile reporting enable you to keep track of inventory items, sales and employee performance from anywhere, anytime. With lot of flexibility and runtime designer, now we will enable you to customize your reports quickly without the intervention of any code modifications.
        </div>
        <div class="text">
          Get an instant overview of your business across all of your locations. Access real-time product, sales and employee performance reports from anywhere, at anytime. Through customizable reports, access your key information quickly without the need to re-run individual reports.
        </div>
        <a href="tel:+971588979925" class="btn btn--sm">Talk To Expert <i class="fas fa-phone-alt"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/01/magnifying-glass-with-graphics-flat-style_23-2147784204.jpg" alt="" class="img img-frame" width="648" height="648" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <p class="title text-center">Explore All  <span class="text-gradient">Features
</span> 
</p>
    <div class="text fw-500 text-center mw-836 mx-auto">
      We’re committed to providing the best POS solutions and support for your business growth
    </div>
    <div class="cols cols-3">
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/barcode.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <h3 class="icon-box__title">Inventory Management</h3>
          <p class="icon-box__text">With our inventory movement, you can keep track of your products, from purchase, return, damage to sa</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/cash-register.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Register Management</p>
          <p class="icon-box__text">Keep track of your payments (Cash, Credit Card, Cheque etc.) as each cashier have separate register accounts.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/dollar.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Easy to Setup</p>
          <p class="icon-box__text">With provided documentation, now it is very easy to setup. Although our support is always there to assist.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/shopping-basket.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Purchase Management</p>
          <p class="icon-box__text">Purchase orders and bill are fully managed with their associated suppliers as well as their payments.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/network.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Employee Management</p>
          <p class="icon-box__text">Each user have their login details with their access levels (Owner, Sale, Purchase) to keep proceeding under control.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/company.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Multi Company / Multi Location</p>
          <p class="icon-box__text">With this, you can manage multi companies and multi locations.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/network-1.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Customers &amp; Suppliers</p>
          <p class="icon-box__text">Add customers and suppliers, manage credits and collect essential information like email and phone numbers.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/web-design.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Mobile Reporting</p>
          <p class="icon-box__text">Now you always connected with your business from anywhere and through any device connected with internet.</p>
        </div>
      </div>
      <div class="stack justify-center card p-20">
        <img src="{{ $base }}/wp-content/uploads/2025/12/bill.png" alt="" class="img" width="80" height="80" loading="lazy">
        <div class="icon-box text-center">
          <p class="icon-box__title">Customisable Receipts</p>
          <p class="icon-box__text">With lot of flexibility, we allows end user to customize their receipt as per their needs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h3 class="title">MultitechPos <span class="text-gradient">RetailPro</span></h3>
        <div class="text">
          Set up your multitechpos Retail Point of Sale system with ease. The effortless flow makes multitechpos transactions flawless and allows you to focus on your business and customers.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/contact/" class="btn btn--sm">Download RetailPro <i class="fas fa-arrow-down"></i></a>
          <a href="https://www.youtube.com/" class="btn btn--sm btn--sm-outline" target="_blank" rel="noopener">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2025/12/1.png" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h3 class="title">MultiTechPos <span class="text-gradient">SalonPro</span></h3>
        <div class="text">
          <p>Get an immediate summary of your business across all of your locations and strategies.</p>
        </div>
        <div class="text">
          <p>Specially designed for salons. Scheduling, Packages, Stylist everything related to salon is covered.</p>
        </div>
        <div class="text">
          Nothing beats, when you get your business related application.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/salonpro/" class="btn btn--sm">Download SalonPro <i class="fas fa-arrow-down"></i></a>
          <a href="https://www.youtube.com/" class="btn btn--sm btn--sm-outline" target="_blank" rel="noopener">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2025/12/home_software_slide2.png" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h3 class="title">MultiTechPos <span class="text-gradient">RestroPro
</span> </h3>
        <div class="text">
          Another business specific solution… multitechpos RestroPro.
        </div>
        <div class="text">
          Tablet booking for waiters and Android application for online order booking makes multitechpos Restro unique among all other applications around.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/restropro/" class="btn btn--sm">Download Restro <i class="fas fa-arrow-down"></i></a>
          <a href="tel:+971588979925" class="btn btn--sm btn--sm-outline">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2025/12/2.png" alt="" class="img r-30" width="476" height="461" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--dark grid-lines">
  <div class="container">
    <div class="cols cols--50-50">
      <div class="stack gap-10 items-center justify-center p-10">
        <h3 class="title c-white text-center mw-487 mx-auto"> Try out <span class="text-gradient">free version
</span>
 
</h3>
        <h3 class="title fs-30 fw-400 c-white text-center">Our free version covers all basic sales functions.</h3>
        <div class="text mw-212 mx-auto">
          <p>Support is not Included</p>
        </div>
      </div>
      <div class="stack gap-10 items-center justify-center">
        <div class="text mw-336 mx-auto">
          <p>Fully compatible with Windows 7,8,10</p>
        </div>
        <a href="tel:+971588979925" class="btn btn--sm">Talk To Expert <i class="fas fa-arrow-down"></i></a>
      </div>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
