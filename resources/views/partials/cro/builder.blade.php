{{-- Setup builder: business, size and modules in four short steps, then a summary to send --}}
@php
    $types = [
        ['Retail Shop', 'fas fa-shopping-cart'], ['Restaurant / Cafe', 'fas fa-utensils'], ['Supermarket / Grocery', 'fas fa-shopping-basket'],
        ['Pharmacy', 'fas fa-pills'], ['Salon / Spa', 'fas fa-spa'], ['Other', 'fas fa-store'],
    ];
@endphp
<section class="cro cro-builder" data-cro="builder" data-wa="{{ $wa }}" data-old="{{ old('service') }}">
    <div class="wrap cro-builder__grid">
        <div class="cro-builder__intro" data-reveal>
            <span class="tag tag--glass"><i class="fas fa-layer-group"></i> Setup Builder</span>
            <h2>Build Your MultiTech Setup in <span class="text-gradient">One Minute</span></h2>
            <p>Answer three quick questions. You get a clear summary of the system that fits your business, ready to send to our team for a price.</p>
            <ul class="cro-builder__perks">
                <li><i class="fas fa-check-circle"></i> No sign-up, no email needed</li>
                <li><i class="fas fa-check-circle"></i> Pay only for the modules you pick</li>
                <li><i class="fas fa-check-circle"></i> Free setup advice from our UAE team</li>
            </ul>
        </div>

        <div class="cro-builder__card" data-reveal>
            <ol class="cro-builder__steps">
                <li class="is-active"><span>1</span> Business</li>
                <li><span>2</span> Size</li>
                <li><span>3</span> Modules</li>
                <li><span>4</span> Your setup</li>
            </ol>

            <div class="cro-builder__panel" data-step="1">
                <p class="cro-builder__q">What kind of business do you run?</p>
                <div class="cro-builder__tiles cro-builder__tiles--3">
                    @foreach ($types as [$type, $icon])
                        <label class="cro-tile">
                            <input type="radio" name="b_type" value="{{ $type }}" @checked($type === $ind['business'])>
                            <span><i class="{{ $icon }}"></i>{{ $type }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="cro-builder__panel" data-step="2" hidden>
                <p class="cro-builder__q">How many branches?</p>
                <div class="cro-builder__tiles cro-builder__tiles--3">
                    @foreach (['1 branch', '2 to 5 branches', '6 or more'] as $i => $v)
                        <label class="cro-tile cro-tile--sm"><input type="radio" name="b_branches" value="{{ $v }}" @checked($i === 0)><span>{{ $v }}</span></label>
                    @endforeach
                </div>
                <p class="cro-builder__q">How many tills or counters per branch?</p>
                <div class="cro-builder__tiles cro-builder__tiles--3">
                    @foreach (['1 counter', '2 to 3 counters', '4 or more'] as $i => $v)
                        <label class="cro-tile cro-tile--sm"><input type="radio" name="b_counters" value="{{ $v }}" @checked($i === 0)><span>{{ $v }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="cro-builder__panel" data-step="3" hidden>
                <p class="cro-builder__q">Which modules do you need? <small>We ticked the usual ones for your business.</small></p>
                <div class="cro-builder__tiles cro-builder__tiles--2">
                    @foreach ($cro['modules'] as $key => [$label, $icon])
                        <label class="cro-tile cro-tile--check">
                            <input type="checkbox" name="b_modules" value="{{ $label }}" @checked(in_array($key, $ind['modules'], true))>
                            <span><i class="{{ $icon }}"></i>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="cro-builder__panel" data-step="4" hidden>
                <p class="cro-builder__q">Your MultiTech setup</p>
                <dl class="cro-builder__summary"></dl>

                {{-- Contact details: the setup above goes with the request (main.js fills the hidden fields) --}}
                <form class="cro-builder__form" action="{{ $base }}/request-demo" method="post">
                    @include('partials.form-extras')
                    <input type="hidden" name="service" value="">
                    <input type="hidden" name="message" value="">
                    <p class="cro-builder__form-title">Where should we send your price?</p>
                    <div class="cro-builder__fields">
                        <label class="hero-form__field">
                            <span>Your name</span>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed Khan" autocomplete="name" required>
                        </label>
                        <label class="hero-form__field">
                            <span>Email</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" required>
                        </label>
                        <label class="hero-form__field">
                            <span>Phone / WhatsApp <small>(optional)</small></span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+971 5X XXX XXXX" autocomplete="tel" inputmode="tel">
                        </label>
                        <label class="hero-form__field">
                            <span>Company <small>(optional)</small></span>
                            <input type="text" name="company" value="{{ old('company') }}" placeholder="Your business name" autocomplete="organization">
                        </label>
                    </div>
                    <div class="cro-builder__send">
                        <button type="submit" class="btn btn--primary">Get my price <i class="fas fa-paper-plane"></i></button>
                        <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener" data-wa-out><i class="fab fa-whatsapp"></i> Or send on WhatsApp</a>
                    </div>
                    <p class="hero-form__note"><i class="fas fa-lock"></i> We only use your details to send your price</p>
                </form>
            </div>

            <div class="cro-builder__nav">
                <button type="button" class="cro-link" data-back hidden><i class="fas fa-arrow-left"></i> Back</button>
                <button type="button" class="btn btn--primary" data-next>Next <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</section>
