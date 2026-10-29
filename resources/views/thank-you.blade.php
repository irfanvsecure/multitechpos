@extends('layouts.app')

@section('no_cro', '1')

@section('title', 'Thank You | MultiTech POS')
@section('robots', 'noindex, nofollow')

@section('content')
<section class="message-page">
    <div class="wrap message-page__inner">
        <span class="message-page__icon"><i class="fas fa-check"></i></span>
        <h1>Thank you, we have received your request</h1>
        <p>Our UAE team will contact you shortly to arrange your free demo. Need an answer right now? Call or WhatsApp us.</p>
        <div class="btn-row btn-row--center">
            <a href="tel:{{ config('site.phone') }}" class="btn btn--primary"><i class="fas fa-phone-alt"></i> {{ config('site.phone_display') }}</a>
            <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
        </div>
        <a href="{{ $base }}/" class="link-arrow message-page__back">Back to the home page <i class="fas fa-arrow-right"></i></a>
    </div>
</section>
@endsection

@push('scripts')
    {{-- Conversion: a demo / quote form was sent --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        if (typeof window.gtag === 'function' && !{{ config('site.gtm_id') ? 'true' : 'false' }}) {
            gtag('event', 'generate_lead', { form_location: document.referrer });
        } else {
            dataLayer.push({ event: 'generate_lead', form_location: document.referrer });
        }
    </script>
@endpush
