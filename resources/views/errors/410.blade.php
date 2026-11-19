@extends('layouts.app')

@section('no_cro', '1')

@section('title', 'Page Removed | MultiTech POS')
@section('robots', 'noindex, follow')

@section('content')
<section class="message-page">
    <div class="wrap message-page__inner">
        <span class="message-page__code">410</span>
        <h1>This page has been removed</h1>
        <p>This page is no longer available. Tell us what you were looking for and we will point you to it, or try one of these:</p>
        <ul class="message-page__links">
            <li><a href="{{ $base }}/pos-software-uae/"><i class="fas fa-cash-register"></i> POS Software</a></li>
            <li><a href="{{ $base }}/more-pos-industries/"><i class="fas fa-store"></i> Industries</a></li>
            <li><a href="{{ $base }}/business-erps/"><i class="fas fa-layer-group"></i> Business ERPs</a></li>
            <li><a href="{{ $base }}/our-blogs/"><i class="far fa-newspaper"></i> Blog</a></li>
        </ul>
        <div class="btn-row btn-row--center">
            <a href="{{ $base }}/" class="btn btn--primary">Go to Home Page</a>
            <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Ask Us on WhatsApp</a>
        </div>
    </div>
</section>
@endsection
