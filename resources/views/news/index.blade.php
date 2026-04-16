@extends('layouts.app')

@section('content')
    <x-page-hero title="Berita & Artikel" />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="mb-8 flex flex-wrap gap-3">
            @foreach ($categories as $category)
                <a class="rounded-full border border-black/10 bg-white px-4 py-2 font-semibold" href="{{ route('news.category', $category) }}">{{ $category->name }}</a>
            @endforeach
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($posts as $post)
                <x-news-card :post="$post" />
            @endforeach
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    </section>
@endsection
