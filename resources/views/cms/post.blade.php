@extends('layouts.app')

@include('cms.meta')
@section('body_class', 'page-post')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Blog', 'url' => '/our-blogs/'], ['name' => $post->title, 'url' => $current]]])
@include('partials.schema-article', ['headline' => $post->title, 'image' => $post->cover_image, 'published' => $post->published_at?->format('Y-m-d'), 'author' => $post->author_name])

<article class="post">
    <header class="post__header">
        <h1>{{ $post->title }}</h1>
        <div class="post__meta">By <a href="{{ $base }}/author/multitech_admin/">{{ $post->author_name }}</a> / {{ $post->published_at?->format('F j, Y') }}</div>
    </header>
    <div class="post__content{{ ($post->options['narrow'] ?? false) ? ' post__content--narrow' : '' }} prose">
{!! \App\Cms\Placeholders::render($post->content, $base) !!}
    </div>
    @include('partials.post-cta')
</article>

@php($navPrev = $prev ? ['path' => '/'.$prev->slug.'/', 'title' => $prev->title] : ($post->options['nav_prev'] ?? null))
@if ($navPrev || $next)
<nav class="post-nav" aria-label="Posts">
    @if ($navPrev)
    <a href="{{ $base }}{{ $navPrev['path'] }}" rel="prev"><span>&larr; Previous</span>{{ $navPrev['title'] }}</a>
    @endif
    @if ($next)
    <a href="{{ $base }}/{{ $next->slug }}/" rel="next"><span>Next &rarr;</span>{{ $next->title }}</a>
    @endif
</nav>
@endif
@include('cms.schema-blocks')
@endsection
