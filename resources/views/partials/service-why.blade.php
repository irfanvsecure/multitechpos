{{--
    Persuasion block for service pages, placed after the second content section.
    $service - the page's product name (e.g. "Retail POS Software in UAE")
    Every text below can be set per page (component attrs); anything left out
    falls back to the default copy. Text is escaped, so layout cannot break.
--}}
@php
    $compareTag       = $compare_tag ?? 'Why Businesses Switch';
    $compareHeading   = $compare_heading ?? 'Stop Losing Time and Money to';
    $compareHighlight = $compare_highlight ?? (isset($compare_heading) ? '' : 'Manual Work');
    $compareText      = $compare_text ?? 'Here is what changes when your business switches to MultiTech POS.';
    $beforeLabel      = $before_label ?? 'Without the right POS';
    $afterLabel       = $after_label ?? 'With MultiTech POS';
    $beforeItems      = $before ?? [
        'Long queues and slow, error-prone billing',
        'Stock counts that never match the shelf',
        'VAT worked out by hand at month end',
        'No clear view of each branch or staff member',
        'Data spread across spreadsheets and notebooks',
    ];
    $afterItems       = $after ?? [
        'Fast billing with barcode scanning',
        'Real-time stock with low-stock alerts',
        'VAT-ready invoices and FTA reports, automatically',
        'Branch and staff reports in one dashboard',
        'Sales, stock, customers and accounts in one system',
    ];
    $afterButton      = $after_button ?? 'See it in a free demo';

    $stepsTag       = $steps_tag ?? 'How It Works';
    $stepsHeading   = $steps_heading ?? 'From First Call to';
    $stepsHighlight = $steps_highlight ?? (isset($steps_heading) ? '' : 'First Sale');
    $stepsText      = $steps_text ?? 'Our UAE team does the setup, so you can be selling in as little as '.setting('stat_setup').'.';
    $stepItems      = $steps ?? [
        ['title' => 'Free demo', 'text' => 'Call, WhatsApp or fill in the form. We learn how you work and show you the system set up for your business.'],
        ['title' => 'Setup & data move', 'text' => 'We configure your products, VAT and devices, and move your data from spreadsheets or your old POS.'],
        ['title' => 'Staff training', 'text' => 'Hands-on training for cashiers and managers, so your team is confident from day one.'],
        ['title' => 'Sell & grow', 'text' => 'Track sales and stock in real time and add branches or modules whenever you need them.'],
    ];
@endphp

{{-- Before / after --}}
<section class="svc svc-compare">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">{{ $compareTag }}</span>
            <h2>{{ $compareHeading }}@if ($compareHighlight !== '') <span class="text-gradient">{{ $compareHighlight }}</span>@endif</h2>
            @if ($compareText !== '')
            <p>{{ $compareText }}</p>
            @endif
        </div>

        <div class="svc-compare__grid">
            <div class="svc-compare__card svc-compare__card--before" data-reveal>
                <p class="svc-compare__label"><i class="fas fa-times-circle"></i> {{ $beforeLabel }}</p>
                <ul>
                    @foreach ($beforeItems as $item)
                    <li><i class="fas fa-times"></i> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <span class="svc-compare__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>

            <div class="svc-compare__card svc-compare__card--after" data-reveal data-tilt>
                <p class="svc-compare__label"><i class="fas fa-check-circle"></i> {{ $afterLabel }}</p>
                <ul>
                    @foreach ($afterItems as $item)
                    <li><i class="fas fa-check"></i> {{ $item }}</li>
                    @endforeach
                </ul>
                <a href="{{ $base }}/contact/" class="btn btn--primary" data-focus-form>{{ $afterButton }} <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- Getting started timeline --}}
<section class="svc svc-steps">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">{{ $stepsTag }}</span>
            <h2>{{ $stepsHeading }}@if ($stepsHighlight !== '') <span class="text-gradient">{{ $stepsHighlight }}</span>@endif</h2>
            @if ($stepsText !== '')
            <p>{{ $stepsText }}</p>
            @endif
        </div>

        <ol class="svc-steps__list">
            @foreach ($stepItems as $step)
            <li class="svc-steps__item" data-reveal>
                <span class="svc-steps__dot">{{ $loop->iteration }}</span>
                <p class="svc-steps__title">{{ $step['title'] ?? '' }}</p>
                <p>{{ $step['text'] ?? '' }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
