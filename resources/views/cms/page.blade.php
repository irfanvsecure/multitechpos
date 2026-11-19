@extends('layouts.app')

@include('cms.meta')
@if ($page->body_class)
    @section('body_class', $page->body_class)
@endif
@if ($page->page_kind)
    @section('page_kind', $page->page_kind)
@endif
@unless ($page->show_cro)
    @section('no_cro', '1')
@endunless

@section('content')
{!! \App\Cms\Renderer::page($page, $base, $current) !!}
@include('cms.schema-blocks')
@endsection
