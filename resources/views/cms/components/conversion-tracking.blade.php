{{-- Thank-you page: reports the "generate_lead" conversion to GTM / GA (nothing visible). --}}
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
