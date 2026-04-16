@extends('layouts.app')

@section('content')
    <x-page-hero :title="$post->title" :subtitle="$post->category?->name" />
    @if ($post->featured_image_url)
        <figure class="mx-auto max-w-5xl px-4 pt-14">
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="aspect-video w-full rounded-lg object-cover shadow-sm"
            >
        </figure>
    @endif
    <article class="prose mx-auto max-w-3xl px-4 py-14">
        {!! $post->content !!}
    </article>
@endsection
