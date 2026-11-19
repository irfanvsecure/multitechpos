{{-- Plans from pricing_plans. The site shows no prices: a price only appears once one is saved on a plan. --}}
@php($plans = \App\Models\PricingPlan::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get())
<section class="home-section">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">{!! $tag !!}</span>
            <h2>{!! $heading !!}</h2>
            <p>{{ $text }}</p>
        </div>

        <div class="plans">
            @foreach ($plans as $plan)
            <article class="plan{{ $plan->is_featured ? ' plan--featured' : '' }}" data-reveal data-tilt>
                @if ($plan->badge)
                <span class="plan__badge">{{ $plan->badge }}</span>
                @endif
                <span class="icon-tile icon-tile--md icon-tile--{{ $plan->tile }}"><i class="{{ $plan->icon }}"></i></span>
                <h3>{{ $plan->name }}</h3>
                <p class="plan__for">{{ $plan->audience ?: $plan->description }}</p>
                @if (filled($plan->price))
                <p class="plan__price"><strong>{{ $plan->price }}</strong>@if ($plan->price_period) <span>{{ $plan->price_period }}</span>@endif</p>
                @endif
                <ul class="check-list">
                    @foreach ($plan->inclusions ?? [] as $item)
                    <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                    @endforeach
                </ul>
                @if ($plan->is_featured)
                <a href="#hero-demo" class="btn btn--primary btn--block" data-focus-form>{{ $plan->cta_label ?: 'Get a Quote' }} <i class="fas fa-arrow-right"></i></a>
                @else
                <a href="#hero-demo" class="btn btn--outline-blue btn--block" data-focus-form>{{ $plan->cta_label ?: 'Get a Quote' }}</a>
                @endif
            </article>
            @endforeach
        </div>

        <p class="plans__note" data-reveal><i class="fas fa-info-circle"></i> {{ $note }}</p>
    </div>
</section>
