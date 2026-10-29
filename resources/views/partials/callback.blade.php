{{--
    Small "call me back" card. Hidden until main.js shows it: after the visitor reads
    about half the page, or moves to leave the tab on desktop. Once per visit.
--}}
<aside class="callback" id="callback" aria-label="Request a call back" hidden>
    <button type="button" class="callback__close" data-callback-close aria-label="Close"><i class="fas fa-times"></i></button>
    <p class="callback__eyebrow"><span class="callback__dot"></span> UAE team available Sat to Thu, 9 AM to 6 PM</p>
    <p class="callback__title">Questions about <span data-callback-topic>the right POS</span>?</p>
    <p class="callback__text">Leave your number. An expert calls you back with answers and a free demo, no sales pressure.</p>

    <form class="callback__form" action="{{ $base }}/request-demo" method="post">
        @include('partials.form-extras')
        <input type="hidden" name="service" value="">
        <input type="hidden" name="message" value="">
        <input type="text" name="name" placeholder="Your name" autocomplete="name" aria-label="Your name" required>
        <input type="tel" name="phone" placeholder="+971 5X XXX XXXX" autocomplete="tel" inputmode="tel" aria-label="Phone or WhatsApp" required>
        <button type="submit" class="btn btn--primary btn--block">Call Me Back <i class="fas fa-phone-alt"></i></button>
    </form>
    <a href="{{ config('site.whatsapp') }}" class="callback__wa" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Prefer WhatsApp? Chat now</a>
</aside>
