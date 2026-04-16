@extends('layouts.app')

@section('content')
    <x-page-hero :title="$page->title" />
    <article class="prose mx-auto max-w-4xl px-4 py-14">
        {!! $page->content !!}
    </article>
@endsection
