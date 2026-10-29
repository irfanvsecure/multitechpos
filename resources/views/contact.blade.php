@extends('layouts.app')

@section('no_cro', '1')

@section('title', 'Contact Us | Book a Free POS Demo | MultiTech POS')
@section('description', 'Contact MultiTech POS in Abu Dhabi for a free POS demo and consultation. Call or WhatsApp +971 58 897 9925 or email support@multitechpos.com.')

@section('content')

@include('partials.page-hero', ['title' => 'Contact', 'image' => '/images/2026/02/Gold-Shop-POS-Software-2.jpg', 'actions' => false])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="tag-wrap"><span class="tag">Help Center</span></div>
    <h2 class="title text-center"><span class="text-gradient">Get in Touch

</span></h2>
    <div class="text fw-500 text-center mw-997 mx-auto">
      Ready to transform your business? Contact us for a free consultation and demo of our POS solutions.
    </div>
    <div class="cols cols--30-70 p-10">
      <div class="stack pt-20 pr-20 pl-20">
        <h2 class="title fs-36 m-text-center"> Contact <span class="text-gradient">Informatio
</span></h2>
        <div class="icon-box icon-box--left m-icon-top m-text-center">
          <span class="icon-tile icon-tile--24 icon-tile--plain ic-blue"><i class="fas fa-phone-alt"></i></span>
          <div class="icon-box__body">
            <h3 class="icon-box__title fs-21 lh-1_3 fw-600 c-black m-text-center mb-3"><a href="tel:+971588979925">Phone</a></h3>
            <p class="icon-box__text c-grey m-text-center">+971588979925</p>
          </div>
        </div>
        <div class="icon-box icon-box--left m-icon-top m-text-center">
          <span class="icon-tile icon-tile--24 icon-tile--plain ic-blue"><i class="far fa-envelope"></i></span>
          <div class="icon-box__body">
            <h3 class="icon-box__title fs-21 lh-1_3 fw-600 c-black m-text-center mb-3"><a href="mailto:info@multitechpos.com">Email</a></h3>
            <p class="icon-box__text c-grey m-text-center">info@multitechpos.com</p>
          </div>
        </div>
        <div class="icon-box icon-box--left m-icon-top m-text-center">
          <span class="icon-tile icon-tile--24 icon-tile--plain ic-blue"><i class="fas fa-map-marker-alt"></i></span>
          <div class="icon-box__body">
            <h3 class="icon-box__title fs-21 lh-1_3 fw-600 c-black m-text-center mb-3">Address</h3>
            <p class="icon-box__text c-grey m-text-center">16 Al Isayfir 2 St - Musaffah - M33 - Abu Dhabi</p>
          </div>
        </div>
        <div class="icon-box icon-box--left m-icon-top m-text-center">
          <span class="icon-tile icon-tile--24 icon-tile--plain ic-blue"><i class="far fa-clock"></i></span>
          <div class="icon-box__body">
            <h3 class="icon-box__title fs-21 lh-1_3 fw-600 c-black m-text-center mb-3">Working Hours</h3>
            <p class="icon-box__text c-grey m-text-center">Sat - Thu: 9:00 AM - 6:00 PM<br>

Friday: Closed</p>
          </div>
        </div>
      </div>
      <div class="stack card card--round pt-20 pr-20 pl-20">
        <h2 class="title text-center">Get Your<span class="text-gradient"> Quote Today!
</span></h2>
        <form class="form" action="{{ $base }}/request-demo" method="post">
          @include('partials.form-extras')
          <div class="form__field">
            <input type="text" name="name" placeholder="Name" required>
          </div>
          <div class="form__field">
            <input type="email" name="email" placeholder="Email (optional)">
          </div>
          <div class="form__field">
            <input type="tel" name="phone" placeholder="Phone / WhatsApp Number" required>
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

<section class="section section--p0 section--full">
  <div class="map"><iframe src="https://maps.google.com/maps?q=16%20Al%20Isayfir%202%20St%20-%20Musaffah%20-%20M33%20-%20Abu%20Dhabi&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near" title="Map" loading="lazy" style="height:300px"></iframe></div>
</section>

@endsection
