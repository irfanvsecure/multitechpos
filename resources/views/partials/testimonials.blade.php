{{-- Customer testimonials from config/site.php. Hidden until real reviews are added there. --}}
@php($testimonials = config('site.testimonials', []))
@if (count($testimonials))
    <section class="home-section home-section--light">
        <div class="wrap">
            <div class="home-head" data-reveal>
                <span class="tag">Customer Stories</span>
                <h2>What Our <span class="text-gradient">Customers Say</span></h2>
                <p>Business owners across the UAE on running their shops, restaurants and pharmacies with MultiTech POS.</p>
            </div>

            <div class="reviews">
                @foreach ($testimonials as $review)
                    <figure class="review" data-reveal data-tilt>
                        <i class="fas fa-quote-left review__mark" aria-hidden="true"></i>
                        <blockquote>{{ $review['quote'] }}</blockquote>
                        <figcaption>
                            @if (! empty($review['photo']))
                                <img src="{{ $base }}{{ $review['photo'] }}" alt="{{ $review['name'] }}" width="48" height="48" loading="lazy">
                            @else
                                <span class="review__initial" aria-hidden="true">{{ mb_substr($review['name'], 0, 1) }}</span>
                            @endif
                            <span><strong>{{ $review['name'] }}</strong>{{ $review['business'] ?? '' }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
