{{-- Home "What kind of business do you run?" picker. Businesses: form_options "business_types". --}}
@php($businesses = \App\Cms\FormOptions::list('business_types'))
<section class="home-section">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">{{ $tag }}</span>
            <h2>{!! $heading !!}</h2>
            <p>{{ $text }}</p>
        </div>

        <div class="biz-picker" data-biz-picker data-reveal>
            <div class="biz-picker__tabs" role="tablist" aria-label="Business type">
                @foreach ($businesses as $i => $business)
                    <button type="button" class="biz-picker__tab" role="tab" id="biz-tab-{{ $i }}" aria-controls="biz-panel-{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" tabindex="{{ $i === 0 ? '0' : '-1' }}">
                        <span class="icon-tile icon-tile--{{ $business['tile'] }}"><i class="{{ $business['icon'] }}"></i></span>
                        {{ $business['label'] }}
                    </button>
                @endforeach
            </div>

            @foreach ($businesses as $i => $business)
                <div class="biz-picker__panel" role="tabpanel" id="biz-panel-{{ $i }}" aria-labelledby="biz-tab-{{ $i }}" @if ($i > 0) hidden @endif>
                    <div class="biz-picker__info">
                        <h3>POS for {{ $business['label'] === 'Something else' ? 'every other business' : $business['label'] }}</h3>
                        <p>{{ $business['text'] }}</p>
                        <ul class="check-list">
                            @foreach ($business['points'] as $point)
                                <li><i class="fas fa-check-circle"></i> {{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="biz-picker__actions">
                        <a href="#hero-demo" class="btn btn--primary" data-focus-form data-business="{{ $business['name'] }}">Get a demo for my business <i class="fas fa-arrow-right"></i></a>
                        <a href="{{ $base }}{{ $business['url'] }}" class="link-arrow">See all features <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
