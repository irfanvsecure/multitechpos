{{-- 30-second check: four yes/no questions, then a score and the next step --}}
<section class="cro cro-quiz" data-cro="quiz" data-wa="{{ $wa }}" data-place="{{ $ind['place'] }}">
    <div class="wrap">
        <div class="cro-quiz__card" data-reveal>
            <span class="tag tag--glass"><i class="fas fa-stopwatch"></i> 30-Second Check</span>
            <h2>Is Your {{ ucfirst($ind['place']) }} <span class="text-gradient">Outgrowing</span> Its Current System?</h2>
            <p class="cro-quiz__intro">Answer {{ count($ind['quiz']) }} quick questions and get your score.</p>

            <div class="cro-quiz__progress" aria-hidden="true">
                @foreach ($ind['quiz'] as $q)
                    <span></span>
                @endforeach
            </div>

            @foreach ($ind['quiz'] as $i => $q)
                <div class="cro-quiz__q" data-q="{{ $i }}" @if ($i > 0) hidden @endif>
                    <p class="cro-quiz__num">Question {{ $i + 1 }} of {{ count($ind['quiz']) }}</p>
                    <p class="cro-quiz__text">{{ $q }}</p>
                    <div class="cro-quiz__answers">
                        <button type="button" class="cro-quiz__btn" data-answer="1">Yes</button>
                        <button type="button" class="cro-quiz__btn" data-answer="0">No</button>
                    </div>
                </div>
            @endforeach

            <div class="cro-quiz__result" hidden aria-live="polite">
                <div class="cro-quiz__ring"><span data-score>0</span><small>/{{ count($ind['quiz']) }}</small></div>
                <div>
                    <p class="cro-quiz__verdict" data-verdict></p>
                    <p class="cro-quiz__advice" data-advice></p>
                    <div class="cro-quiz__actions">
                        <a href="{{ $base }}/contact/" class="btn btn--primary" data-focus-form>Book a free demo <i class="fas fa-arrow-right"></i></a>
                        <a href="{{ config('site.whatsapp') }}" class="btn btn--ghost" target="_blank" rel="noopener" data-wa-out><i class="fab fa-whatsapp"></i> Ask on WhatsApp</a>
                        <button type="button" class="cro-link cro-link--light" data-restart>Start again</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
