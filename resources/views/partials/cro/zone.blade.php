{{--
    Picks the interactive sections for this page (added by the layout).
    $kind - home | landing | post | general   (pages set it with @section('page_kind'))
    $slug - page URL without slashes
    Landing pages rotate between calculator / quiz / day so neighbouring pages look different.
    "mid" widgets go in a <template>; main.js moves them up into the page.
--}}
@php
    // banner pages that do not sell a product behave like general pages
    if ($kind === 'landing' && preg_match('/^(about-us|customers|downloads|our-blogs)$/', $slug)) {
        $kind = 'general';
    }
    // read the file directly when the server's cached config predates config/cro.php
    $cro = config('cro') ?: require config_path('cro.php');
    $industry = $cro['default'];
    foreach ($cro['match'] as $pattern => $key) {
        if (preg_match('/('.$pattern.')/i', $slug)) {
            $industry = $key;
            break;
        }
    }
    $ind = $cro['industries'][$industry];
    $wa = 'https://wa.me/'.ltrim(config('site.phone'), '+').'?text=';

    [$mid, $end] = match ($kind) {
        'home' => ['finder', 'builder'],
        'landing' => [['calculator', 'quiz', 'day'][crc32($slug) % 3], 'builder'],
        'post' => [null, 'quiz'],
        default => [null, 'finder'],
    };
    $shared = compact('ind', 'industry', 'cro', 'wa');
@endphp

@if ($mid)
    <template id="cro-mid">@include('partials.cro.'.$mid, $shared)</template>
@endif
@if ($end)
    @include('partials.cro.'.$end, $shared)
@endif
