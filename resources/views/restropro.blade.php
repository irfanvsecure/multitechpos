@extends('layouts.app')

@section('title', 'RestroPro Download | Restaurant POS Software | MultiTech POS')
@section('description', 'Download RestroPro, MultiTech\'s restaurant POS for UAE cafés and restaurants. Explore features like recipes, KOT, kitchen display and full accounting.')

@section('content')

@include('partials.page-hero', ['title' => 'Restropro', 'image' => '/images/2025/12/freepik-export-20240628023510yeGg-1.jpeg', 'parent' => ['name' => 'Downloads', 'url' => '/downloads/']])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="text-center"><a href="{{ $base }}/download/restropro/" class="btn btn--sm btn--lg">Download Restropro</a></div>
    <div class="row gap-10 m-stack-reverse items-center justify-center p-10">
      <div class="tabs" data-tabs>
        <div class="tabs__nav">
          <button type="button" class="tabs__tab is-active">Description</button>
          <button type="button" class="tabs__tab">Package Info</button>
        </div>
        <div class="tabs__pane is-active">
          <div class="stack py-10 mw-1366 mx-auto">
            <div class="slider" data-carousel style="height:530px">
              <div class="slider__track">
                <div class="slider__slide" style="background-image:url('{{ $base }}/images/2025/12/6-1440x860-1.png');background-size:contain"></div>
                <div class="slider__slide" style="background-image:url('{{ $base }}/images/2025/12/5-1-1440x860-1.png');background-size:contain"></div>
                <div class="slider__slide" style="background-image:url('{{ $base }}/images/2025/12/4-1-1440x860-1.png');background-size:contain"></div>
                <div class="slider__slide" style="background-image:url('{{ $base }}/images/2025/12/2-3-1440x860-1.png');background-size:contain"></div>
              </div>
              <button class="slider__prev" type="button" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
              <button class="slider__next" type="button" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
          <h2 class="title text-center">Covering<span class="text-gradient"> all features of Restaurants
</span> 

</h2>
          <div class="cols cols-3 py-10 mw-1366 mx-auto">
            <div class="stack p-10">
              <div class="text fs-16 c-grey">
                <ul>
                <li><strong>Recipe Management</strong></li>
                <li><strong>Kitchen Print (KOT)</strong></li>
                <li><strong>Kitchen Display Screen</strong></li>
                <li><strong>Multi Level Kitchens</strong></li>
                <li><strong>Multi Cuisine</strong></li>
                <li><strong>Waiter Order Taking App</strong></li></ul>
              </div>
            </div>
            <div class="stack p-10">
              <div class="text fs-16 c-grey">
                <ul>
                <li><strong>Category Management</strong></li>
                <li><strong>Biller</strong></li>
                <li><strong>Permissions</strong></li>
                <li><strong>Employee Management</strong></li>
                <li><strong>Attendance</strong></li>
                <li><strong>Dynamic Bills</strong></li></ul>
              </div>
            </div>
            <div class="stack p-10">
              <div class="text fs-16 c-grey">
                <ul>
                <li><strong>Full Accounting</strong></li>
                <li><strong>Profit / Loss</strong></li>
                <li><strong>Expiry Management</strong></li>
                <li><strong>Storage</strong></li>
                <li><strong>Backup System</strong></li>
                <li><strong>Dynamic Reports</strong></li></ul>
              </div>
            </div>
          </div>
        </div>
        <div class="tabs__pane">
          <div class="row gap-0 box-dark p-10 hide-desktop hide-mobile">
            <div class="stack">
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>Version</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>Download</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>File Size</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>File Count</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>Create Date</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white">
                <li>Last Updated</li>
              </ul>
            </div>
            <div class="stack justify-center">
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>2.0</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>1370</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>74.33 MB</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>1</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>May 19, 2019</li>
              </ul>
              <ul class="icon-list icon-list--inline icon-list--boxed c-white text-right">
                <li>April 16, 2025</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('partials.service-quote', ['service' => 'Restropro'])

@include('partials.cta-band')

@endsection
