@extends('layouts.app')

@section('title', 'Service Business POS Software in UAE | Easy Billing')
@section('description', 'Manage your UAE service business with all-in-one POS software. Track jobs, billing, invoices, staff & customer records with ease. Book a free demo today!')
@section('og_image', $base . '/images/2026/06/pexels-spoton-pos-2160258094-37594403-1.webp')
@section('og_type', 'article')

@section('content')

@include('partials.schema-faq', ['faqs' => [
    ['q' => 'What is the best POS system for service businesses in UAE?', 'a' => 'MultiTech POS is purpose-built for UAE service businesses, covering appointment scheduling, inventory tracking, loyalty programs, and VAT-compliant billing, all in one unified platform.'],
    ['q' => 'Can your POS manage appointments and bookings?', 'a' => 'Yes. MultiTech POS includes a built-in scheduling module to manage bookings, service queues, and completion tracking — ideal for both walk-in and pre-booked service models.'],
    ['q' => 'Is MultiTech POS suitable for both small and multi-branch service businesses?', 'a' => 'Yes. MultiTech POS scales effortlessly from a single outlet to a large multi-branch network, with centralized control over pricing, staff, and performance across all locations.'],
    ['q' => 'How does service business POS support customer loyalty and repeat visits?', 'a' => 'The system automatically tracks service history and loyalty points, letting you offer personalized rewards, like repeat visit discounts or exclusive member packages, to drive retention.'],
    ['q' => 'Is Your POS system UAE VAT compliant and secure?', 'a' => 'Yes. MultiTech POS generates FTA-compliant VAT invoices automatically and protects your data with encrypted transactions, role-based access controls, and automated backups.'],
    ['q' => 'What payment methods does MultiTech POS support?', 'a' => 'MultiTech POS accepts cash, credit and debit cards, mobile wallets, and UAE payment gateways, including split payments and contactless transactions for a fast checkout experience.'],
]])

@include('partials.page-hero', ['title' => 'Service Business POS Software in UAE', 'image' => '/images/2025/12/freepik-export-20240628023510yeGg.jpeg', 'parent' => ['name' => 'POS Software', 'url' => '/pos-software-uae/']])

@include('partials.clients-band')

<section class="section section--white">
  <div class="container">
    <div class="cols cols--60-40 items-center p-10">
      <div class="stack p-10">
        <h2 class="title">Complete  POS Softwarefor Service Businesses in UAE</h2>
        <p class="title fs-30 fw-600">Streamline Your Business Operations with a smart, unified POS system</p>
        <div class="text">
          Our Service Business POS software is built to support the everyday operations of growing businesses across the UAE, helping owners manage customers, payments, and staff with ease. It is designed for service-driven industries where speed, accuracy, and customer experience matter the most.
        </div>
        <div class="text">
          From <a href="{{ $base }}/salon-spa-pos-software-uae/">salon software</a> to <a href="{{ $base }}/car-wash-pos-software/">car wash management tools</a>, cafés, and small service-based business POS, MultiTech’s system fits naturally into different workflows without adding complexity. It keeps bookings, billing, and operations organized so teams can focus more on service and less on manual work.
        </div>
        <div class="text">
          With MultiTech’s all-in-one service point of sale system, you gain complete control over operations, real-time business insights, customer management tools, and performance analytics designed to boost productivity, enhance customer satisfaction, and increase revenue.
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack card card--round card--sh4 p-20">
        <p class="title text-center">Get Your Quote Today!</p>
        <form class="form" action="{{ $base }}/request-demo" method="post">
          @include('partials.form-extras')
          <div class="form__field">
            <input type="text" name="name" placeholder="Name" required>
          </div>
          <div class="form__field">
            <input type="email" name="email" placeholder="Email" required>
          </div>
          <div class="form__field">
            <input type="tel" name="phone" placeholder="Phone Number">
          </div>
          <div class="form__field">
            <input type="text" name="company" placeholder="Company Name">
          </div>
          <div class="form__field">
            <select name="service">
              <option value="">Interested Service</option>
              <option>POS Software</option>
              <option>POS Hardware</option>
              <option>Demo Request</option>
            </select>
          </div>
          <div class="form__field">
            <textarea name="message" rows="4" placeholder="Message"></textarea>
          </div>
          <button type="submit" class="btn btn--sm btn--block">Send</button>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 p-10">
        <h3 class="title">Scalable POS Solutionsfor Service Businesses of All Sizes</h3>
        <div class="text">
          <p>Our scalable POS solution is designed to grow alongside your business, whether you’re managing a single location or expanding across multiple branches. Flexible enough for startups and established companies alike, it provides the tools needed to streamline operations, maintain consistency, and improve customer service at every stage of growth. From a <a href="{{ $base }}/small-business-pos-uae/">small business POS setup</a> focused on simplicity and affordability to a more advanced <a href="{{ $base }}/multi-branch-business-pos-uae/">multi-branch POS</a> environment requiring centralized control and reporting, the platform adapts to your evolving needs without disrupting daily operations.</p>
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2026/06/pexels-spoton-pos-2160258094-37594403-1.webp" alt="Service Business POS Software in UAE" class="img img-frame" width="648" height="486" loading="lazy">
      </div>
    </div>
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 p-10">
        <h3 class="title">Enhance Customer Loyalty and Retention</h3>
        <div class="text">
          In the service industry, loyal customers are your most valuable asset. MultiTech service POS lets you track customer preferences, full client histories, and loyalty points with its advanced <a href="{{ $base }}/customers/">customer management module</a>, so you can offer rewards that actually matter to each client. Repeat car wash customers earn discounts, golf club members unlock exclusive packages, and delivery clients enjoy faster checkouts. The result: more repeat visits, stronger retention, and steadily growing revenue.
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2026/06/Customer_assisting_returning_cus_202606031606.webp" alt="Service Business POS Software in UAE" class="img img-frame" width="648" height="483" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <h2 class="title lh-1_3 text-center m-text-left">Key Features of <br>Service BusinessPoint Of Sale Software</h2>
    <div class="cols cols-3 cols--reverse">
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#AAB8C2" d="M17 34c0 1.104.896 2 2 2h14c1.104 0 2-.896 2-2V18c0-1.104-.896-2-2-2H19c-1.104 0-2 .896-2 2v16z"></path><path fill="#292F33" d="M33 16H23v2h12c0-1.104-.896-2-2-2z"></path><path fill="#3B88C3" d="M3 30h30v4H3z"></path><path fill="#CCD6DD" d="M3 16c-1.104 0-2 .896-2 2v16c0 1.104.896 2 2 2h20V16H3z"></path><path fill="#66757F" d="M3 16c-1.104 0-2 .896-2 2h22v-2H3z"></path><path fill="#55ACEE" d="M3 20h4v4H3zm14 0h4v4h-4zm-7 0h4v4h-4z"></path><path fill="#3B88C3" d="M29 20h4v4h-4zm-6 0h4v4h-4z"></path><path fill="#55ACEE" d="M3 30h18v6H3z"></path><path fill="#3B88C3" d="M7 30h10v6H7z"></path><path fill="#DD2E44" d="M1 26h22v4H1z"></path><path fill="#F4ABBA" d="M7 27h10v2H7z"></path><path fill="#FFF" d="M9 27h6v2H9z"></path><path fill="#A0041E" d="M23 26h12v4H23z"></path><path fill="#292F33" d="M5 14h2v2H5zm12 0h2v2h-2z"></path><path fill="#DD2E44" d="M21 12c0 1.104-.896 2-2 2H5c-1.104 0-2-.896-2-2V2c0-1.104.896-2 2-2h14c1.104 0 2 .896 2 2v10z"></path><path d="M10.561 10.151c.616 0 1.093.28 1.093.925 0 .644-.477.924-1.009.924H5.967c-.617 0-1.093-.28-1.093-.924 0-.294.182-.546.322-.714C6.359 8.975 7.62 7.714 8.685 6.173c.252-.364.49-.798.49-1.303 0-.574-.434-1.079-1.009-1.079-1.611 0-.84 2.269-2.185 2.269-.672 0-1.022-.476-1.022-1.022 0-1.765 1.569-3.18 3.292-3.18 1.723 0 3.109 1.135 3.109 2.914 0 1.947-2.171 3.88-3.362 5.379h2.563zm2.363-.35c-.687 0-.981-.462-.981-.826 0-.309.112-.477.196-.617l3.138-5.687c.308-.56.7-.813 1.429-.813.812 0 1.611.519 1.611 1.793v4.3h.238c.546 0 .98.364.98.925 0 .56-.434.924-.98.924h-.238v1.19c0 .743-.295 1.093-1.009 1.093s-1.008-.35-1.008-1.093V9.8h-3.376zM16.3 4.044h-.028l-1.891 3.908H16.3V4.044z" fill="#FFF"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Employee &amp; Role Management</h3>
          <p class="icon-box__text c-grey">Manage staff shifts, roles, and performance with visibility across multiple locations.</p>
        </div>
      </div>
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#FFAC33" d="M4 5C1.791 5 0 6.791 0 9v18c0 2.209 1.791 4 4 4h28c2.209 0 4-1.791 4-4V9s0-4-4-4H4z"></path><path fill="#292F33" d="M0 10h36v5H0z"></path><path fill="#F4F7F9" d="M4 19h28v6H4z"></path><path fill="#8899A6" d="M19 24c-1.703 0-2.341-1.21-2.469-1.801-.547.041-1.08.303-1.805.764C13.961 23.449 13.094 24 12 24c-1.197 0-1.924-.675-2-2-.003-.056.038-.188.021-.188-1.858 0-3.202 1.761-3.215 1.779-.195.267-.499.409-.806.409-.206 0-.413-.063-.591-.194-.445-.327-.542-.953-.215-1.398C5.271 22.303 7.11 20 10 20c1.937 0 2.048 1.375 2.078 1.888l.007.109c.486-.034.991-.354 1.57-.723.961-.61 2.153-1.371 3.75-.962.871.223 1.007 1.031 1.059 1.336.013.076.032.19.049.226.007 0 .146.091.577.13.82.075 1.721-.279 2.675-.653.988-.388 2.01-.788 3.111-.788 3.389 0 4.767 1.635 4.913 1.821.34.435.264 1.063-.171 1.403-.175.137-.381.207-.587.213-.307.009-.613-.123-.817-.384-.024-.027-.93-1.054-3.337-1.054-.723 0-1.528.315-2.381.649-1.009.396-2.434.789-3.496.789z"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Integrated Payment Solutions</h3>
          <p class="icon-box__text c-grey">Accept cash, cards, mobile wallets, and UAE-based payment gateways for fast and secure transactions.</p>
        </div>
      </div>
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#C1694F" d="M32 34c0 1.104-.896 2-2 2H6c-1.104 0-2-.896-2-2V7c0-1.104.896-2 2-2h24c1.104 0 2 .896 2 2v27z"></path><path fill="#FFF" d="M29 32c0 .553-.447 1-1 1H8c-.552 0-1-.447-1-1V9c0-.552.448-1 1-1h20c.553 0 1 .448 1 1v23z"></path><path fill="#CCD6DD" d="M25 3h-4c0-1.657-1.343-3-3-3s-3 1.343-3 3h-4c-1.104 0-2 .896-2 2v5h18V5c0-1.104-.896-2-2-2z"></path><circle fill="#292F33" cx="18" cy="3" r="2"></circle><path fill="#99AAB5" d="M20 14c0 .552-.447 1-1 1h-9c-.552 0-1-.448-1-1s.448-1 1-1h9c.553 0 1 .448 1 1zm7 4c0 .552-.447 1-1 1H10c-.552 0-1-.448-1-1s.448-1 1-1h16c.553 0 1 .448 1 1zm0 4c0 .553-.447 1-1 1H10c-.552 0-1-.447-1-1 0-.553.448-1 1-1h16c.553 0 1 .447 1 1zm0 4c0 .553-.447 1-1 1H10c-.552 0-1-.447-1-1 0-.553.448-1 1-1h16c.553 0 1 .447 1 1zm0 4c0 .553-.447 1-1 1h-9c-.552 0-1-.447-1-1 0-.553.448-1 1-1h9c.553 0 1 .447 1 1z"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Appointment &amp; Order Management</h3>
          <p class="icon-box__text c-grey">Manage bookings, schedules, and orders seamlessly for car washes, deliveries, and appointment-based services.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#55ACEE" d="M29 20.729v-1.963c1-1.03 2.914-2.89 3.391-5.273.142.079.055.13.213.13.758 0 1.256-.983 1.256-2.197 0-1.194-.656-2.161-1.399-2.191.143-.516.212-1.206.212-2.092 0-2.956-2.549-6.505-8.253-6.505-5.068 0-8.244 3.549-8.244 6.505 0 .858.051 1.562.142 2.107-.697.105-1.247 1.033-1.247 2.175 0 1.214.614 2.197 1.373 2.197.157 0-.069-.051.072-.13.477 2.384 2.484 4.243 3.484 5.274v1.847c-4 .492-7 2.628-7 4.765v.81c0 .812.823.812 1.634.812h18.73c.813 0 1.636 0 1.636-.812v-.81c0-2.001-3-3.997-6-4.649z"></path><path fill="#269" d="M17 28.729v-1.963c1-1.03 2.914-2.89 3.391-5.273.142.079.055.13.213.13.758 0 1.256-.983 1.256-2.197 0-1.194-.656-2.161-1.399-2.191.143-.516.212-1.206.212-2.092 0-2.956-2.549-6.505-8.253-6.505-5.069 0-8.244 3.549-8.244 6.505 0 .858.051 1.562.142 2.107-.697.105-1.247 1.033-1.247 2.175 0 1.214.614 2.197 1.373 2.197.157 0-.069-.051.072-.13C4.993 23.876 7 25.735 8 26.766v1.847c-4 .492-7 2.628-7 4.765v.811C1 35 1.823 35 2.634 35h18.73c.813 0 1.636 0 1.636-.812v-.811c0-2-3-3.996-6-4.648z"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Detailed Analytics</h3>
          <p class="icon-box__text c-grey">Gain insights on sales trends, peak hours, best-selling items, and customer behavior.</p>
        </div>
      </div>
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#CCD6DD" d="M31 2H5C3.343 2 2 3.343 2 5v26c0 1.657 1.343 3 3 3h26c1.657 0 3-1.343 3-3V5c0-1.657-1.343-3-3-3z"></path><path fill="#E1E8ED" d="M31 1H5C2.791 1 1 2.791 1 5v26c0 2.209 1.791 4 4 4h26c2.209 0 4-1.791 4-4V5c0-2.209-1.791-4-4-4zm0 2c1.103 0 2 .897 2 2v4h-6V3h4zm-4 16h6v6h-6v-6zm0-2v-6h6v6h-6zM25 3v6h-6V3h6zm-6 8h6v6h-6v-6zm0 8h6v6h-6v-6zM17 3v6h-6V3h6zm-6 8h6v6h-6v-6zm0 8h6v6h-6v-6zM3 5c0-1.103.897-2 2-2h4v6H3V5zm0 6h6v6H3v-6zm0 8h6v6H3v-6zm2 14c-1.103 0-2-.897-2-2v-4h6v6H5zm6 0v-6h6v6h-6zm8 0v-6h6v6h-6zm12 0h-4v-6h6v4c0 1.103-.897 2-2 2z"></path><path fill="#5C913B" d="M13 33H7V16c0-1.104.896-2 2-2h2c1.104 0 2 .896 2 2v17z"></path><path fill="#3B94D9" d="M29 33h-6V9c0-1.104.896-2 2-2h2c1.104 0 2 .896 2 2v24z"></path><path fill="#DD2E44" d="M21 33h-6V23c0-1.104.896-2 2-2h2c1.104 0 2 .896 2 2v10z"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Advanced Analytics &amp; Reporting</h3>
          <p class="icon-box__text c-grey">Gain actionable insights into revenue trends, popular services, peak hours, best-selling items and repeat customer behavior, all in real-time.</p>
        </div>
      </div>
      <div class="stack gap-10 card card--flat p-25">
        <div class="icon-box">
          <span class="icon-tile icon-tile--114 icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#3B88C3" d="M18 0C8.059 0 0 8.059 0 18s8.059 18 18 18 18-8.059 18-18S27.941 0 18 0zM2.05 19h3.983c.092 2.506.522 4.871 1.229 7H4.158c-1.207-2.083-1.95-4.459-2.108-7zM19 8V2.081c2.747.436 5.162 2.655 6.799 5.919H19zm7.651 2c.754 2.083 1.219 4.46 1.317 7H19v-7h7.651zM17 2.081V8h-6.799C11.837 4.736 14.253 2.517 17 2.081zM17 10v7H8.032c.098-2.54.563-4.917 1.317-7H17zM6.034 17H2.05c.158-2.54.901-4.917 2.107-7h3.104c-.705 2.129-1.135 4.495-1.227 7zm1.998 2H17v7H9.349c-.754-2.083-1.219-4.459-1.317-7zM17 28v5.919c-2.747-.437-5.163-2.655-6.799-5.919H17zm2 5.919V28h6.8c-1.637 3.264-4.053 5.482-6.8 5.919zM19 26v-7h8.969c-.099 2.541-.563 4.917-1.317 7H19zm10.967-7h3.982c-.157 2.541-.9 4.917-2.107 7h-3.104c.706-2.129 1.136-4.494 1.229-7zm0-2c-.093-2.505-.523-4.871-1.229-7h3.104c1.207 2.083 1.95 4.46 2.107 7h-3.982zm.512-9h-2.503c-.717-1.604-1.606-3.015-2.619-4.199C27.346 4.833 29.089 6.267 30.479 8zM10.643 3.801C9.629 4.985 8.74 6.396 8.023 8H5.521c1.39-1.733 3.133-3.166 5.122-4.199zM5.521 28h2.503c.716 1.604 1.605 3.015 2.619 4.198C8.654 31.166 6.911 29.733 5.521 28zm19.836 4.198c1.014-1.184 1.902-2.594 2.619-4.198h2.503c-1.39 1.733-3.133 3.166-5.122 4.198z"></path></svg></span>
          <h3 class="icon-box__title fs-20 lh-1_4">Multi-Language Support</h3>
          <p class="icon-box__text c-grey">Arabic and English interfaces allow seamless operations for diverse customers.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 p-10">
        <h2 class="title">Data-Driven Operations &amp; Service Optimization POS</h2>
        <div class="text">
          Our service POS system empowers businesses to make data driven decisions while streamlining daily operations. By providing detailed analytics on revenue per operation, peak hours, repeat customer behavior and solution specific metrics, owners can optimize staffing, pricing and promotions effectively.
        </div>
        <div class="text">
          <p>The system automates <a href="{{ $base }}/appointment-booking-pos-uae/">appointment booking</a>, inventory tracking and workflow management, ensuring every process is efficient and transparent. Real time updates allow managers to monitor staff performance, track operational trends and reduce errors. With MultiTech, businesses can maximize profitability, enhance customer satisfaction and scale efficiently.</p>
        </div>
        <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/images/2026/06/multi-ethnic-business-team-sitting-table-office-center-speaking-about-project-meeting-broadroom-1.webp" alt="Service Business POS Software in UAE" class="img img-frame" width="648" height="432" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--dark grid-lines">
  <div class="container">
    <h2 class="title c-white text-center m-text-left">POS Solutions for <br>Every Service Business</h2>
    <div class="cols cols-3">
      <div class="stack gap-10 card-dark">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#F4900C" d="M35.676 18.625c.057.794.011 5.907.006 6.093-.004.139-.013.279-.026.422-.618 6.445-5.937 10.342-12.187 9.375-6.062-.938-8.938-5.5-11.938-8.5S1.656 22.827.641 19.312c-.233-.807-.324-1.562-.294-2.267.011-.256-.141-5.031.035-6.169.338-2.189 35.008 3.795 35.294 7.749z"></path><path fill="#FFCC4D" d="M4.718 4.856c3.5-2.125 8.964-2.286 12.5 1.25 3 3 4.243 3.843 9.243 4.843s9.632 3.532 9.194 8.094c-.618 6.445-5.937 10.342-12.187 9.375-6.062-.938-8.938-5.5-11.938-8.5S1.655 16.73.64 13.215c-1.109-3.842.996-6.487 4.078-8.359z"></path><g fill="#FFAC33"><circle cx="12.513" cy="14.106" r="1.5"></circle><circle cx="29.013" cy="25.794" r="1"></circle><circle cx="17.013" cy="24.106" r="1"></circle><circle cx="24.513" cy="24.794" r="1.5"></circle><circle cx="31.825" cy="21.606" r="1.5"></circle><circle cx="29.013" cy="18.419" r="1"></circle><circle cx="25.013" cy="20.356" r="1"></circle><circle cx="19.513" cy="21.356" r="1"></circle><path d="M14.013 20.606c0-.828-.672-1.5-1.5-1.5-.496 0-.933.244-1.206.616.074.067.155.127.224.197.598.598 1.192 1.258 1.806 1.939.406-.268.676-.728.676-1.252z"></path><circle cx="9.575" cy="16.419" r="1"></circle><circle cx="16.013" cy="17.419" r="1"></circle><circle cx="24.513" cy="13.606" r="1.5"></circle><circle cx="21.013" cy="17.419" r="1.5"></circle><circle cx="12.013" cy="10.106" r="1"></circle><circle cx="20.513" cy="11.106" r="1"></circle><circle cx="14.513" cy="7.856" r="1"></circle><circle cx="7.575" cy="12.606" r="1"></circle><circle cx="6.013" cy="6.856" r="1"></circle><circle cx="28.575" cy="14.106" r="1"></circle><circle cx="33.325" cy="17.419" r="1"></circle><circle cx="3.263" cy="10.606" r="1.5"></circle><circle cx="10.575" cy="5.856" r="1.5"></circle><circle cx="16.513" cy="11.106" r="1.5"></circle><path d="M19.513 26.106c-.408 0-.778.164-1.048.429.77.486 1.61.908 2.531 1.243.007-.057.017-.113.017-.172 0-.828-.672-1.5-1.5-1.5zm-14.75-10.5c0-.552-.448-1-1-1-.542 0-.978.433-.994.971.491.288 1.034.549 1.615.795.225-.184.379-.452.379-.766z"></path></g></svg></span>
          <p class="icon-box__title fs-20 lh-1_4 c-white mb-5">Car Wash POS</p>
          <p class="icon-box__text c-grey">Track wash packages, appointments, and customer preferences. MultiTech POS helps increase repeat visits, loyalty, and operational efficiency for car wash businesses of all sizes.</p>
        </div>
      </div>
      <div class="stack gap-10 card-dark">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#99AAB5" d="M30 33c-2.417 0-5-1.313-5-5 0-2.071 1.118-3.478 2.199-4.838.423-.532.851-1.07 1.239-1.666C26.883 20.077 25.172 19 24 19v-2c1.621 0 3.603 1.133 5.382 2.67.378-.995.618-2.177.618-3.67h2c0 2.169-.448 3.816-1.077 5.149 1.445 1.542 2.591 3.23 3.025 4.534.688 2.066.483 4.228-.535 5.642C32.635 32.405 31.422 33 30 33zm-.13-10.052c-.368.53-.747 1.008-1.105 1.458C27.779 25.646 27 26.627 27 28c0 2.479 1.632 3 3 3 .784 0 1.387-.284 1.791-.845.64-.888.741-2.395.261-3.839-.327-.978-1.15-2.202-2.182-3.368z"></path><path fill="#FFAC33" d="M21.252 10H9c-2.209 0-3.883 1.791-3.74 4l1.17 18c.144 2.209 1.775 4 3.645 4h10.102c1.869 0 3.501-1.791 3.645-4l1.171-18c.142-2.209-1.532-4-3.741-4z"></path><path fill="#AAB8C2" d="M26 33H4c-.552 0-1 .447-1 1v2h24v-2c0-.553-.447-1-1-1z"></path><path fill="#FFAC33" d="M31 10c-.553 0-1-.448-1-1V5.764l2.105-4.211c.248-.494.848-.695 1.342-.447.494.247.694.848.447 1.342L32 6.236V9c0 .552-.447 1-1 1z"></path><path fill="#BE1931" d="M33.5 16h-.777c.172-.295.277-.634.277-1v-4c0-.738-.404-1.376-1-1.723V9c0-.552-.447-1-1-1s-1 .448-1 1v.277c-.596.347-1 .984-1 1.723v4c0 .366.105.705.277 1H28.5c-.276 0-.5.224-.5.5s.224.5.5.5h5c.276 0 .5-.224.5-.5s-.224-.5-.5-.5z"></path><path fill="#99AAB5" d="M25 14c0 .552.447 1 1 1h5c.553 0 1-.448 1-1s-.447-1-1-1h-5c-.553 0-1 .448-1 1z"></path><path fill="#E1E8ED" d="M27 14c0 2.209-1.791 4-4 4H7c-2.209 0-4-1.791-4-4V4c0-2.209 1.791-4 4-4h16c2.209 0 4 1.791 4 4v10z"></path><path fill="#66757F" d="M25 14c0 1.104-.896 2-2 2H7c-1.104 0-2-.896-2-2V4c0-1.104.896-2 2-2h16c1.104 0 2 .896 2 2v10z"></path><path fill="#AAB8C2" d="M13 5h9v3h-9z"></path></svg></span>
          <p class="icon-box__title fs-20 lh-1_4 c-white mb-5">Gas Station POS</p>
          <p class="icon-box__text c-grey">Manage fuel sales, convenience store inventory, and loyalty programs in one unified platform. Ensure VAT compliance, real-time inventory tracking, and operational efficiency.</p>
        </div>
      </div>
      <div class="stack gap-10 card-dark">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#DD2E44" d="M36 27c0 2.209-1.791 4-4 4H4c-2.209 0-4-1.791-4-4v-3c0-2.209 1.791-4 4-4h28c2.209 0 4 1.791 4 4v3z"></path><path fill="#FFCC4D" d="M19 13l-.979-1H7.146C4 12 3 14 3 14l-3 5.959V25h19V13z"></path><path fill="#55ACEE" d="M9 20H2l2-4s1-2 3-2h2v6z"></path><circle fill="#292F33" cx="9" cy="31" r="4"></circle><circle fill="#CCD6DD" cx="9" cy="31" r="2"></circle><circle fill="#292F33" cx="27" cy="31" r="4"></circle><circle fill="#CCD6DD" cx="27" cy="31" r="2"></circle><path fill="#CCD6DD" d="M32 8H17c-2.209 0-4 1.791-4 4v13h23V12c0-2.209-1.791-4-4-4z"></path></svg></span>
          <p class="icon-box__title fs-20 lh-1_4 c-white mb-5">Delivery Service POS</p>
          <p class="icon-box__text c-grey">Coordinate orders, manage dispatch, and track deliveries efficiently. MultiTech POS supports high-volume delivery operations with real-time updates, reporting, and automated workflow management.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-2">
      <div class="stack gap-10 card-dark">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#77B255" d="M4 36h28c2 0 3.731-2 3.731-4 0-4.355-4.634-11-17.731-11C4.508 21 .193 27.459.193 32 .193 34 2 36 4 36z"></path><path fill="#5C913B" d="M11.292 29.5s.958.667 1.958.667c2.208-4.875 13-7.5 14-7.5.15 0 .33-.018.512-.047-.716-.28-1.502-.521-2.33-.737-4.626.063-13.031 3.917-14.14 7.617z"></path><ellipse fill="#292F33" cx="12.5" cy="30.5" rx="6.5" ry="2.5"></ellipse><path fill="#FFAC33" d="M10 4v26c0 .553.448.844 1 .844s1-.291 1-.844V4h-2z"></path><path fill="#DD2E44" d="M10 3C10 .8 11.695-.395 13.767.345l9.466 3.381c2.071.74 2.071 1.951 0 2.69l-9.466 3.381C11.695 10.538 10 9.343 10 7.143V3z"></path></svg></span>
          <p class="icon-box__title fs-20 lh-1_4 c-white mb-5">Golf Club POS</p>
          <p class="icon-box__text c-grey">Handle tee time bookings, membership management, and pro shop sales. MultiTech POS ensures smooth daily operations, accurate revenue tracking, and personalized member experiences.</p>
        </div>
      </div>
      <div class="stack gap-10 card-dark">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--plain"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#77B255" d="M20.917 22.502c-2.706-.331-3.895-1.852-6.273-4.889 3.039-2.376 4.559-3.565 7.266-3.235 2.71.332 5.25 2.016 6.273 4.889-1.683 2.543-4.557 3.563-7.266 3.235zm-5.959 8.814c-2.549-.187-3.733-1.553-6.098-4.288 2.735-2.364 4.102-3.547 6.652-3.364 2.551.185 5.009 1.644 6.098 4.287-1.459 2.458-4.1 3.548-6.652 3.365zm-6.22-15.707c1.338 1.631 1.191 3.117.898 6.088-2.97-.294-4.456-.44-5.795-2.071-1.339-1.634-1.861-3.935-.898-6.088 2.301-.524 4.456.439 5.795 2.071zm21.116-5.448c-2.435 1.02-4.16.314-7.613-1.097 1.411-3.453 2.118-5.18 4.549-6.203 2.434-1.021 5.378-.826 7.612 1.096-.194 2.944-2.117 5.181-4.548 6.204zM17.103 6.608c.874 2.869-.124 4.742-2.119 8.488-3.745-1.996-5.619-2.994-6.494-5.864-.876-2.872-.315-6.18 2.118-8.49 3.308.561 5.619 2.993 6.495 5.866z"></path><path fill="#A6D388" d="M8.49 9.232c.862 2.828 2.702 3.843 6.338 5.781v-.005c-.07-2.521-2.733-10.876-4.267-14.214C8.172 3.102 7.62 6.381 8.49 9.232zm-5.592 4.429c-.89 2.118-.371 4.362.943 5.965 1.34 1.632 2.826 1.777 5.795 2.071-.997-1.937-4.911-6.388-6.738-8.036z"></path><path fill="#5C913B" d="M21.91 14.378c-2.563-.312-4.077.75-6.808 2.879 1.746.105 8.786.745 13.06 2.037.006-.01.015-.017.021-.027-1.023-2.873-3.563-4.557-6.273-4.889zm-.304 13.565c-1.091-2.637-3.545-4.094-6.094-4.279-2.5-.179-3.87.961-6.498 3.232 2.767-.305 7.905-.87 12.592 1.047z"></path><path fill="#A6D388" d="M22.421 9.137c3.327 1.359 5.043 2.024 7.432 1.024 2.419-1.018 4.332-3.239 4.542-6.16-3.922.761-10.391 4.15-11.974 5.136z"></path><path fill="#A06253" d="M4.751 35.061c-.584-.091-1.363-.831-1.273-1.416.546-3.562 2.858-12.168 18.298-24.755.458-.375.976-.659 1.364-.212.391.447-.052.95-.498 1.339C9.354 21.587 7.128 30.751 6.619 34.082c-.091.585-1.283 1.067-1.868.979z"></path></svg></span>
          <p class="icon-box__title fs-20 lh-1_4 c-white mb-5">Specialty &amp; Hemp Service POS</p>
          <p class="icon-box__text c-grey">Ensure regulatory compliance while managing inventory, sales, and customer records. The POS adapts to specialty service needs, including weight-based items, price variations, and subscription models.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <h2 class="title text-center">Why Choose MultiTech for Your Service Business in UAE</h2>
    <div class="text fw-500 text-center mw-837 mx-auto">
      We’re committed to providing the best POS solutions and support for your business growth
    </div>
    <div class="cols cols-3">
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="far fa-flag"></i></span>
          <p class="icon-box__title">UAE Focused Design</p>
          <p class="icon-box__text">Built specifically for UAE compliance, including VAT reporting, Arabic/English interfaces, and local payment gateway integrations out of the box.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--green"><i class="fas fa-cloud"></i></span>
          <p class="icon-box__title">Flexible Deployment Options</p>
          <p class="icon-box__text">Run MultiTech POS in the cloud, on-premise, or in a hybrid setup, whichever fits your locations and IT infrastructure.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-shield-alt"></i></span>
          <p class="icon-box__title">High Reliability &amp; Security</p>
          <p class="icon-box__text">Stay operational even during internet outages with offline mode. All data is encrypted and backed up automatically.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-2">
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-58 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-headset"></i></span>
          <p class="icon-box__title">24/7 Local Support</p>
          <p class="icon-box__text">Our UAE-based support team is available around the clock via phone, WhatsApp and email, so you always have an expert on hand when you need one.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-sliders-h"></i></span>
          <p class="icon-box__title">Customizable Modules</p>
          <p class="icon-box__text">Every service business is different. Pick and configure only the modules you need, appointments, loyalty, inventory, delivery, and more.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="tag-wrap"><span class="tag">Help Center</span></div>
    <h2 class="title text-center">FAQs About <br> Service Business POS Software</h2>
    <div class="text fw-500 text-center mw-997 mx-auto">
      <p>Our POS solutions are trusted by businesses across diverse industries in the UAE and Middle East.</p>
    </div>
    <div class="stack p-10">
      <div class="faq mw-1144 mx-auto">
        <details open>
          <summary>What is the best POS system for service businesses in UAE?</summary>
          <div class="faq__body">
            MultiTech POS is purpose-built for UAE service businesses, covering appointment scheduling, inventory tracking, loyalty programs, and VAT-compliant billing, all in one unified platform.
          </div>
        </details>
        <details>
          <summary>Can your POS manage appointments and bookings?</summary>
          <div class="faq__body">
            Yes. MultiTech POS includes a built-in scheduling module to manage bookings, service queues, and completion tracking — ideal for both walk-in and pre-booked service models.
          </div>
        </details>
        <details>
          <summary>Is MultiTech POS suitable for both small and multi-branch service businesses?</summary>
          <div class="faq__body">
            Yes. MultiTech POS scales effortlessly from a single outlet to a large multi-branch network, with centralized control over pricing, staff, and performance across all locations.
          </div>
        </details>
        <details>
          <summary>How does service business POS support customer loyalty and repeat visits?</summary>
          <div class="faq__body">
            The system automatically tracks service history and loyalty points, letting you offer personalized rewards, like repeat visit discounts or exclusive member packages, to drive retention.
          </div>
        </details>
        <details>
          <summary>Is Your POS system UAE VAT compliant and secure?</summary>
          <div class="faq__body">
            Yes. MultiTech POS generates FTA-compliant VAT invoices automatically and protects your data with encrypted transactions, role-based access controls, and automated backups.
          </div>
        </details>
        <details>
          <summary>What payment methods does MultiTech POS support?</summary>
          <div class="faq__body">
            MultiTech POS accepts cash, credit and debit cards, mobile wallets, and UAE payment gateways, including split payments and contactless transactions for a fast checkout experience.
          </div>
        </details>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container items-center justify-center">
    <div class="stack card card--sh4 p-40 mw-1097 mx-auto">
      <p class="title text-center">Still Have  Questions?</p>
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
      <div class="text-center"><a href="{{ $base }}/contact" class="btn btn--sm">Request a Free Demo <i class="fas fa-arrow-right"></i></a></div>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
