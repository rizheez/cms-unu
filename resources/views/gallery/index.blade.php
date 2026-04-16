@extends('layouts.app')

@section('content')
    <x-page-hero title="Galeri" />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($galleries as $gallery)
                <article class="rounded-lg border border-black/10 bg-white p-6">
                    <div class="aspect-video rounded-lg bg-[#00a9b7]"></div>
                    <h2 class="mt-4 font-display text-2xl font-bold"><a href="{{ route('gallery.show', $gallery) }}">{{ $gallery->title }}</a></h2>
                    <p class="mt-2 text-sm text-[#123136]/70">{{ $gallery->description }}</p>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $galleries->links() }}</div>
    </section>
@endsection
