@extends('layouts.app')

@section('title', 'TailorPro Download | Tailor Management Software')
@section('description', 'Download TailorPro, MultiTech\'s tailor shop management software for the UAE: measurements, orders, stitching status, delivery dates and billing.')

@section('content')

@include('partials.page-hero', ['title' => 'Tailorpro', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg-1.jpeg', 'parent' => ['name' => 'Downloads', 'url' => '/downloads/']])

@include('partials.clients-band')

<section class="section section--light">
  <div class="container">
    <div class="text-center"><a href="https://drive.google.com/file/d/12yzq1fhFi7yolb0DG6bJx0p6T-GFguiT/view" class="btn btn--sm btn--lg" target="_blank" rel="noopener">Download Tailorpro</a></div>
    <div class="row gap-10 m-stack-reverse items-center justify-center p-10">
      <div class="tabs" data-tabs>
        <div class="tabs__nav">
          <button type="button" class="tabs__tab is-active">Description</button>
          <button type="button" class="tabs__tab">Package Info</button>
        </div>
        <div class="tabs__pane is-active">
          <div class="stack box-white py-64 mw-1366 mx-auto">
            <div class="video"><iframe src="https://www.youtube.com/embed/xS2HDhkXc_4?rel=0" title="Video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
          </div>
          <h2 class="title text-center">Covering<span class="text-gradient"> all features of laundries
</span> 

</h2>
          <div class="cols cols-3 py-10 mw-1366 mx-auto">
            <div class="stack p-10">
              <div class="text fs-16 c-grey">
                <ul>
                <li><strong>Dynamic Services</strong></li>
                <li><strong>Measurements</strong></li>
                <li><strong>Service Wise Pricing</strong></li>
                <li><strong>Full Control On Deliveries</strong></li>
                <li><strong>Expense Management</strong></li>
                <li><strong>Whatsapp Messaging</strong></li></ul>
              </div>
            </div>
            <div class="stack p-10">
              <div class="text fs-16 c-grey">
                <ul>
                <li><strong>Inventory</strong></li>
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
                <li><strong>Customer Ledger</strong></li>
                <li><strong>Debit / Credit</strong></li>
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

@include('partials.cta-band')

@endsection
