@extends('layouts.app')

@section('no_cro', '1')

@section('title', 'Page Not Found | MultiTech POS')
@section('robots', 'noindex, follow')

@section('content')
<section class="message-page">
    <div class="wrap message-page__inner">
        <span class="message-page__code">404</span>
        <h1>Sorry, this page could not be found</h1>
        <p>The page may have moved or the link may be out of date. These pages might help:</p>
        <ul class="message-page__links">
            <li><a href="{{ $base }}/pos-software-uae/"><i class="fas fa-cash-register"></i> POS Software</a></li>
            <li><a href="{{ $base }}/more-pos-industries/"><i class="fas fa-store"></i> Industries</a></li>
            <li><a href="{{ $base }}/business-erps/"><i class="fas fa-layer-group"></i> Business ERPs</a></li>
            <li><a href="{{ $base }}/our-blogs/"><i class="far fa-newspaper"></i> Blog</a></li>
        </ul>
        <div class="btn-row btn-row--center">
            <a href="{{ $base }}/" class="btn btn--primary">Go to Home Page</a>
            <a href="{{ $base }}/contact/" class="btn btn--light">Contact Us</a>
        </div>
    </div>
</section>
@endsection
