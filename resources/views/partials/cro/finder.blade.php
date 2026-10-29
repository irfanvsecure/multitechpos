{{-- Problem finder: tick problems, see the MultiTech fix for each one --}}
<section class="cro cro-finder" data-cro="finder" data-wa="{{ $wa }}">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag"><i class="fas fa-search"></i> Problem Finder</span>
            <h2>What’s Slowing Your <span class="text-gradient">Business</span> Down?</h2>
            <p>Tick everything that sounds familiar. We show you the MultiTech fix for each one, straight away.</p>
        </div>

        <div class="cro-finder__board" data-reveal>
            <div class="cro-finder__cols">
                @foreach ($cro['problems'] as [$group, $icon, $items])
                    <fieldset class="cro-finder__col">
                        <legend><i class="{{ $icon }}"></i> {{ $group }}</legend>
                        @foreach ($items as [$problem, $fix, $link])
                            <label class="cro-finder__item">
                                <input type="checkbox" data-fix="{{ $fix }}" data-link="{{ $base }}{{ $link }}" value="{{ $problem }}">
                                <span class="cro-finder__box"><i class="fas fa-check"></i></span>
                                <span>{{ $problem }}</span>
                            </label>
                        @endforeach
                    </fieldset>
                @endforeach
            </div>

            <div class="cro-finder__result" aria-live="polite">
                <p class="cro-finder__empty"><i class="far fa-hand-point-up"></i> Tick a problem above to see how MultiTech fixes it.</p>
                <div class="cro-finder__found" hidden>
                    <p class="cro-finder__count"><strong data-count-out>0</strong> fixes for your business</p>
                    <ul class="cro-finder__fixes"></ul>
                    <div class="cro-finder__actions">
                        <a href="{{ $base }}/contact/" class="btn btn--primary" data-focus-form>Show me these in a demo <i class="fas fa-arrow-right"></i></a>
                        <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener" data-wa-out><i class="fab fa-whatsapp"></i> Send my list</a>
                        <button type="button" class="cro-link" data-clear>Clear all</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
