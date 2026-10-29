{{--
    Persuasion block for service pages, placed after the second content section.
    $service - the page's product name (e.g. "Retail POS Software in UAE")
--}}

{{-- Before / after --}}
<section class="svc svc-compare">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">Why Businesses Switch</span>
            <h2>Stop Losing Time and Money to <span class="text-gradient">Manual Work</span></h2>
            <p>Here is what changes when your business switches to MultiTech POS.</p>
        </div>

        <div class="svc-compare__grid">
            <div class="svc-compare__card svc-compare__card--before" data-reveal>
                <p class="svc-compare__label"><i class="fas fa-times-circle"></i> Without the right POS</p>
                <ul>
                    <li><i class="fas fa-times"></i> Long queues and slow, error-prone billing</li>
                    <li><i class="fas fa-times"></i> Stock counts that never match the shelf</li>
                    <li><i class="fas fa-times"></i> VAT worked out by hand at month end</li>
                    <li><i class="fas fa-times"></i> No clear view of each branch or staff member</li>
                    <li><i class="fas fa-times"></i> Data spread across spreadsheets and notebooks</li>
                </ul>
            </div>

            <span class="svc-compare__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>

            <div class="svc-compare__card svc-compare__card--after" data-reveal data-tilt>
                <p class="svc-compare__label"><i class="fas fa-check-circle"></i> With MultiTech POS</p>
                <ul>
                    <li><i class="fas fa-check"></i> Fast billing with barcode scanning</li>
                    <li><i class="fas fa-check"></i> Real-time stock with low-stock alerts</li>
                    <li><i class="fas fa-check"></i> VAT-ready invoices and FTA reports, automatically</li>
                    <li><i class="fas fa-check"></i> Branch and staff reports in one dashboard</li>
                    <li><i class="fas fa-check"></i> Sales, stock, customers and accounts in one system</li>
                </ul>
                <a href="{{ $base }}/contact/" class="btn btn--primary" data-focus-form>See it in a free demo <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- Getting started timeline --}}
<section class="svc svc-steps">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">How It Works</span>
            <h2>From First Call to <span class="text-gradient">First Sale</span></h2>
            <p>Our UAE team does the setup, so you can be selling in as little as 48 hours.</p>
        </div>

        <ol class="svc-steps__list">
            <li class="svc-steps__item" data-reveal>
                <span class="svc-steps__dot">1</span>
                <h3>Free demo</h3>
                <p>Call, WhatsApp or fill in the form. We learn how you work and show you the system set up for your business.</p>
            </li>
            <li class="svc-steps__item" data-reveal>
                <span class="svc-steps__dot">2</span>
                <h3>Setup &amp; data move</h3>
                <p>We configure your products, VAT and devices, and move your data from spreadsheets or your old POS.</p>
            </li>
            <li class="svc-steps__item" data-reveal>
                <span class="svc-steps__dot">3</span>
                <h3>Staff training</h3>
                <p>Hands-on training for cashiers and managers, so your team is confident from day one.</p>
            </li>
            <li class="svc-steps__item" data-reveal>
                <span class="svc-steps__dot">4</span>
                <h3>Sell &amp; grow</h3>
                <p>Track sales and stock in real time and add branches or modules whenever you need them.</p>
            </li>
        </ol>
    </div>
</section>
