{{-- Loss calculator: sliders with the visitor's own numbers, result in AED per year --}}
@php($calc = $ind['calculator'])
<section class="cro cro-calc" data-cro="calculator" data-formula="{{ $calc['formula'] }}" data-wa="{{ $wa }}">
    <div class="wrap cro-calc__grid">
        <div class="cro-calc__inputs" data-reveal>
            <span class="tag"><i class="fas fa-calculator"></i> {{ $calc['tag'] }}</span>
            <h2>{{ $calc['title'] }}</h2>
            <p>{{ $calc['intro'] }}</p>

            @foreach ($calc['sliders'] as $i => $s)
                <label class="cro-slider">
                    <span class="cro-slider__top">
                        <span>{{ $s['label'] }}</span>
                        <output data-prefix="{{ $s['prefix'] ?? '' }}" data-suffix="{{ $s['suffix'] ?? '' }}"></output>
                    </span>
                    <input type="range" min="{{ $s['min'] }}" max="{{ $s['max'] }}" step="{{ $s['step'] }}" value="{{ $s['value'] }}" data-label="{{ $s['label'] }}">
                </label>
            @endforeach
        </div>

        <div class="cro-calc__result" data-reveal>
            <p class="cro-calc__label">{{ $calc['result'] }}</p>
            <p class="cro-calc__big">AED <span data-total>0</span></p>
            <p class="cro-calc__month">That is about <strong>AED <span data-month>0</span></strong> a month.</p>
            <p class="cro-calc__fix"><i class="fas fa-lightbulb"></i> {{ $calc['fix'] }}</p>
            <a href="{{ config('site.whatsapp') }}" class="btn btn--primary btn--block" target="_blank" rel="noopener" data-wa-out><i class="fab fa-whatsapp"></i> Show me how to cut this</a>
            <a href="{{ $base }}/contact/" class="btn btn--ghost btn--block" data-focus-form>Book a free demo</a>
            <p class="cro-calc__note">Based only on the numbers you entered.</p>
        </div>
    </div>
</section>
