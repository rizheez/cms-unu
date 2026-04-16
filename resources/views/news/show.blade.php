@extends('layouts.app')

@section('content')
    <x-page-hero :title="$post->title" :subtitle="$post->category?->name" />
    <article class="prose mx-auto max-w-3xl px-4 py-14">
        {!! $post->content !!}
    </article>
@endsection
