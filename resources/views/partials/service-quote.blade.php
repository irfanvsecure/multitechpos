{{--
    Closing block for service pages (what is included + testimonials), placed before the demo banner.
    $service - the page's product name (e.g. "Retail POS Software in UAE")
--}}

{{-- What every setup includes --}}
<section class="svc svc-included">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">No Hidden Extras</span>
            <h2>Everything You Need to <span class="text-gradient">Get Started</span></h2>
            <p>Every setup comes with a local team that gets you running and stays with you.</p>
        </div>

        <div class="svc-included__grid">
            @foreach ([
                ['fas fa-cogs', 'blue', 'Setup & configuration', 'Products, prices, VAT, receipts and devices configured for your business.'],
                ['fas fa-file-import', 'violet', 'Data migration', 'We move your products, stock and customers from spreadsheets or your old POS.'],
                ['fas fa-user-graduate', 'green', 'Staff training', 'Hands-on training for cashiers, managers and admin staff.'],
                ['fas fa-file-invoice', 'amber', 'VAT & FTA ready', '5% VAT on every invoice and reports ready for your VAT return.'],
                ['fas fa-cash-register', 'rose', 'Hardware advice', 'Use your own scanners and printers, or get a recommended bundle.'],
                ['fas fa-headset', 'cyan', 'Local support', 'Help by phone, email and remote session from our UAE team.'],
            ] as [$icon, $tile, $name, $text])
                <div class="svc-included__item" data-reveal data-tilt>
                    <span class="icon-tile icon-tile--md icon-tile--{{ $tile }}"><i class="{{ $icon }}"></i></span>
                    <div>
                        <h3>{{ $name }}</h3>
                        <p>{{ $text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.testimonials')

