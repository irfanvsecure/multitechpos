{{-- Contact page: what happens after the form is sent, so visitors know what to expect --}}
<section class="contact-next">
    <div class="wrap">
        <div class="home-head" data-reveal>
            <span class="tag">What Happens Next</span>
            <h2>From Your Message to a <span class="text-gradient">Working Demo</span></h2>
            <p>No pushy sales calls. Just a short chat, then a demo set up for how your business works.</p>
        </div>

        <ol class="contact-next__list">
            @foreach ([
                ['fas fa-phone-volume', 'blue', 'We call you back', 'A POS expert from our Abu Dhabi office calls or WhatsApps you during working hours.'],
                ['fas fa-clipboard-list', 'violet', 'We learn your business', 'A few quick questions: your industry, branches, devices and what slows you down today.'],
                ['fas fa-desktop', 'green', 'Free live demo', 'We show you the system with your kind of products, in person or online, at a time that suits you.'],
                ['fas fa-file-invoice-dollar', 'amber', 'Clear quote', 'You get a written price for the setup you need. No obligation, no hidden extras.'],
            ] as $i => [$icon, $tile, $name, $text])
                <li class="contact-next__item" data-reveal data-tilt>
                    <span class="contact-next__num">{{ $i + 1 }}</span>
                    <span class="icon-tile icon-tile--md icon-tile--{{ $tile }}"><i class="{{ $icon }}"></i></span>
                    <h3>{{ $name }}</h3>
                    <p>{{ $text }}</p>
                </li>
            @endforeach
        </ol>

        <div class="contact-next__fast" data-reveal>
            <p><i class="fas fa-bolt"></i> Need an answer right now?</p>
            <div class="btn-row">
                <a href="tel:{{ config('site.phone') }}" class="btn btn--primary"><i class="fas fa-phone-alt"></i> {{ config('site.phone_display') }}</a>
                <a href="{{ config('site.whatsapp') }}" class="btn btn--light" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>
