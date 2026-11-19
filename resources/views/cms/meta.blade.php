{{-- Page-level @sections from the database (shared by cms.page and cms.post). Expects $seo. --}}
@section('title', $seo['title'])
@if (filled($seo['description']))
    @section('description', $seo['description'])
@endif
@if (filled($seo['og_image']))
    @section('og_image', (str_starts_with($seo['og_image'], '/') ? $base : '').$seo['og_image'])
@endif
@if (filled($seo['og_type']))
    @section('og_type', $seo['og_type'])
@endif
@if (filled($seo['robots']))
    @section('robots', $seo['robots'])
@endif
@foreach (['og_title', 'og_description', 'twitter_card', 'canonical'] as $field)
    @if (filled($seo[$field]))
        @section($field, $seo[$field])
    @endif
@endforeach
