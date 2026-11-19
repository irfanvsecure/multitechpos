{{--
    Home page hero. Texts come from the section's attrs, business types from form_options
    "business_types", stats from settings. A/B test of the H1 + button when AB_TEST=true.
--}}
@php
    $variant = 'a';
    if (config('site.ab_test')) {
        $variant = in_array(request()->cookie('mtp_ab'), ['a', 'b'], true) ? request()->cookie('mtp_ab') : (random_int(0, 1) ? 'b' : 'a');
        \Illuminate\Support\Facades\Cookie::queue('mtp_ab', $variant, 60 * 24 * 30);
    }
    $rating = config('site.google_rating');
    $businesses = \App\Cms\FormOptions::list('business_types');
    $stat = function (string $key) {
        $value = (string) \App\Cms\Settings::get($key);
        return preg_match('/^(\d+)(\D*)$/', $value, $m) ? ['value' => $value, 'count' => $m[1], 'suffix' => $m[2]] : ['value' => $value, 'count' => null, 'suffix' => null];
    };
    $stats = [
        [$stat('stat_businesses'), 'Businesses served'],
        [$stat('stat_years'), 'Years of experience'],
        [$stat('stat_industries'), 'Industries supported'],
        [$stat('stat_support'), 'Local support'],
    ];
@endphp
@if (config('site.ab_test'))
    @push('head')
        <script>window.dataLayer = window.dataLayer || []; dataLayer.push({ event: 'experiment_view', experiment: 'home_hero', variant: '{{ $variant }}' });</script>
    @endpush
@endif
<section class="home-hero">
    {{-- Moving colour blobs behind the hero --}}
    <div class="hero-blobs" aria-hidden="true">
        <span class="hero-blob hero-blob--blue"></span>
        <span class="hero-blob hero-blob--violet"></span>
        <span class="hero-blob hero-blob--teal"></span>
        <span class="hero-blob hero-blob--pink"></span>
        <span class="hero-grid"></span>
    </div>

    <div class="wrap home-hero__grid">
        <div class="home-hero__content">
            <span class="pill" data-reveal><span class="pill__dot"></span> {{ $pill }}</span>
            @if ($variant === 'b')
                <h1 data-reveal>{!! $title_b !!}</h1>
            @else
                <h1 data-reveal>{!! $title !!}</h1>
            @endif
            <p class="lead" data-reveal>{{ $lead }}</p>

            <ul class="check-grid" data-reveal>
                @foreach ($checks as $check)
                <li><i class="fas fa-check"></i> {{ $check }}</li>
                @endforeach
            </ul>

            <div class="btn-row" data-reveal>
                <a href="#hero-demo" class="btn btn--primary btn--lg-pad" data-focus-form>{{ $button_demo }} <i class="fas fa-arrow-right"></i></a>
                <a href="{{ config('site.whatsapp') }}" class="btn btn--light btn--lg-pad" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ $button_whatsapp }}</a>
            </div>
            <ul class="hero-trust" data-reveal>
                @foreach ($trust as [$icon, $text])
                <li><i class="{{ $icon }}"></i> {{ $text }}</li>
                @endforeach
            </ul>

            @if ($rating)
                <a href="{{ $rating['url'] ?? '#' }}" class="rating-badge" target="_blank" rel="noopener" data-reveal>
                    <i class="fab fa-google"></i>
                    <span class="rating-badge__stars" style="--score: {{ $rating['score'] }}" aria-hidden="true"></span>
                    <strong>{{ number_format($rating['score'], 1) }}</strong>
                    <span>from {{ $rating['count'] }} Google reviews</span>
                </a>
            @endif
        </div>

        {{-- Short demo form: name, phone and business type only --}}
        <div class="home-hero__media" data-reveal>
            <div class="hero-form" id="hero-demo" data-tilt>
                <p class="hero-form__eyebrow"><i class="fas fa-bolt"></i> {{ $form_eyebrow }}</p>
                <h2 class="hero-form__title">{{ $form_title }}</h2>
                <p class="hero-form__text">{{ $form_text }}</p>

                <form class="hero-form__form" action="{{ $base }}/request-demo" method="post">
                    @include('partials.form-extras')
                    <input type="hidden" name="message" value="Free demo request from the home page{{ config('site.ab_test') ? ' (version '.strtoupper($variant).')' : '' }}">
                    <label class="hero-form__field">
                        <span>Your name</span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed Khan" autocomplete="name" required>
                    </label>
                    <label class="hero-form__field">
                        <span>Phone / WhatsApp</span>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+971 5X XXX XXXX" autocomplete="tel" inputmode="tel" required>
                    </label>
                    <label class="hero-form__field">
                        <span>Business type</span>
                        <select name="service">
                            @foreach ($businesses as $business)
                                <option @selected(old('service') === $business['name'])>{{ $business['name'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn btn--primary btn--block hero-form__submit">{{ $variant === 'b' ? $button_b : $button }} <i class="fas fa-arrow-right"></i></button>
                </form>
                <p class="hero-form__note"><i class="fas fa-lock"></i> {!! $form_note !!}</p>
            </div>

            @foreach ($floats as $i => [$icon, $strong, $small])
            <div class="float-card float-card--{{ $i === 0 ? 'top' : 'bottom' }}">
                <span class="float-card__icon{{ $i === 0 ? '' : ' float-card__icon--green' }}"><i class="{{ $icon }}"></i></span>
                <span><strong>{{ $strong }}</strong><small>{{ $small }}</small></span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="wrap">
        <ul class="stat-bar" data-reveal>
            @foreach ($stats as [$s, $label])
            <li><strong{!! $s['count'] !== null ? ' data-count="'.e($s['count']).'" data-suffix="'.e($s['suffix']).'"' : '' !!}>{{ $s['value'] }}</strong><span>{{ $label }}</span></li>
            @endforeach
        </ul>
    </div>
</section>
