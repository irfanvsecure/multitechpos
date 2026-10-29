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

        {{-- What to expect, so the call is not a surprise --}}
        <ol class="thanks-steps">
            <li class="is-done"><span><i class="fas fa-check"></i></span><strong>Request sent</strong><small>We have your details</small></li>
            <li class="is-next"><span>2</span><strong>We call you</strong><small>During working hours, Sat to Thu</small></li>
            <li><span>3</span><strong>Free demo</strong><small>Set up for your business</small></li>
        </ol>
        <p class="thanks-tip"><i class="fas fa-lightbulb"></i> Tip: save <strong>{{ config('site.phone_display') }}</strong> in your phone so you know it is us when we call.</p>

        <div class="thanks-more">
            <p class="thanks-more__title">While you wait</p>
            <div class="thanks-more__grid">
                <a href="{{ $base }}/features/"><i class="fas fa-th-large"></i><strong>See all features</strong><small>Billing, stock, VAT, reports</small></a>
                <a href="{{ $base }}/customers/"><i class="fas fa-store"></i><strong>Our customers</strong><small>Businesses using MultiTech POS</small></a>
                <a href="{{ $base }}/frequently-asked-questions-multitech-pos/"><i class="far fa-question-circle"></i><strong>Common questions</strong><small>Setup, hardware, support</small></a>
            </div>
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
