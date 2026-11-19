{{-- Client logos strip (home page + all landing pages) --}}
@php
    // client_logos table (managed through the MCP)
    $clients = \App\Cms\Content::clientLogos();
@endphp
<section class="clients-band">
    <div class="wrap clients-band__inner">
        <div class="clients-band__intro">
            <p class="clients-band__eyebrow">Our happy clients</p>
            <p class="clients-band__title">Trusted by <strong>{{ setting('stat_businesses') }} businesses</strong> across the UAE</p>
            <a href="{{ $base }}/contact/" class="link-arrow">Join them, book a free demo <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="marquee" aria-label="Our clients">
            <div class="marquee__track">
                @foreach ([false, true] as $copy)
                    <ul class="marquee__group" @if ($copy) aria-hidden="true" @endif>
                        @foreach ($clients as $client)
                            <li class="client-logo">
                                <img src="{{ str_starts_with($client->image, '/') ? $base : '' }}{{ $client->image }}" alt="{{ $copy ? '' : $client->name }}" width="150" height="150" loading="lazy">
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
</section>
