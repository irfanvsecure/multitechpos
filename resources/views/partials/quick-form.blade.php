{{--
    Short demo form (name + phone) for the inner page banners.
    $source - page name, sent with the lead so sales know where it came from
--}}
<div class="hero-form hero-form--compact" id="hero-demo" data-tilt>
    <p class="hero-form__eyebrow"><i class="fas fa-bolt"></i> Free demo &amp; consultation</p>
    <p class="hero-form__title">See it working for your business</p>
    <p class="hero-form__text">Leave your number and our UAE team will call you back.</p>

    <form class="hero-form__form" action="{{ $base }}/request-demo" method="post">
        @include('partials.form-extras')
        <input type="hidden" name="service" value="{{ \Illuminate\Support\Str::limit($source, 95, '') }}">
        <input type="hidden" name="message" value="Free demo request from the page: {{ $source }}">
        <label class="hero-form__field">
            <span>Your name</span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed Khan" autocomplete="name" required>
        </label>
        <label class="hero-form__field">
            <span>Phone / WhatsApp</span>
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+971 5X XXX XXXX" autocomplete="tel" inputmode="tel" required>
        </label>
        <button type="submit" class="btn btn--primary btn--block hero-form__submit">Get My Free Demo <i class="fas fa-arrow-right"></i></button>
    </form>
    <p class="hero-form__note"><i class="fas fa-lock"></i> 100% free &middot; No obligation</p>
</div>
