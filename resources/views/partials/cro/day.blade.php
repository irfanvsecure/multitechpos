{{-- "A day at your business with MultiTech": a scrollable timeline of what the system does --}}
<section class="cro cro-day" data-cro="day">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag"><i class="far fa-clock"></i> A Day With MultiTech</span>
            <h2>One Day at Your <span class="text-gradient">{{ ucfirst($ind['place']) }}</span>, Running Itself</h2>
            <p>From opening to closing, here is what the system takes off your plate.</p>
        </div>

        <div class="cro-day__track" data-reveal>
            @foreach ($ind['day'] as [$time, $title, $text])
                <article class="cro-day__item">
                    <p class="cro-day__time">{{ $time }}</p>
                    <h3>{{ $title }}</h3>
                    <p>{{ $text }}</p>
                </article>
            @endforeach
            <article class="cro-day__item cro-day__item--cta">
                <p class="cro-day__time"><i class="fas fa-calendar-check"></i></p>
                <h3>Want this day in your {{ $ind['place'] }}?</h3>
                <a href="{{ $base }}/contact/" class="btn btn--white" data-focus-form>See it in a free demo <i class="fas fa-arrow-right"></i></a>
            </article>
        </div>

        <div class="cro-day__controls" aria-hidden="true">
            <button type="button" data-day-prev><i class="fas fa-arrow-left"></i></button>
            <button type="button" data-day-next><i class="fas fa-arrow-right"></i></button>
        </div>
    </div>
</section>
