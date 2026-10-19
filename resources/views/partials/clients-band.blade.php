{{-- Client logos strip (home page + all landing pages) --}}
@php
    $clients = [
        ['theivy', 'The Ivy Journal'],
        ['bazaar', 'Bazaar'],
        ['brain-games', 'Brain Games'],
        ['candadian-pizza', 'Canadian Pizza'],
        ['emd', 'Edrees Medical Equipment'],
        ['maras-turka', 'Maras Turka'],
    ];
@endphp
<section class="clients-band">
    <div class="wrap clients-band__inner">
        <div class="clients-band__intro">
            <p class="clients-band__eyebrow">Our happy clients</p>
            <p class="clients-band__title">Trusted by <strong>500+ businesses</strong> across the UAE</p>
            <a href="{{ $base }}/contact/" class="link-arrow">Join them, book a free demo <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="marquee" aria-label="Our clients">
            <div class="marquee__track">
                @foreach ([false, true] as $copy)
                    <ul class="marquee__group" @if ($copy) aria-hidden="true" @endif>
                        @foreach ($clients as [$file, $name])
                            <li class="client-logo">
                                <img src="{{ $base }}/wp-content/uploads/elementor/thumbs/{{ $file }}-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="{{ $copy ? '' : $name }}" width="150" height="150" loading="lazy">
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
</section>
