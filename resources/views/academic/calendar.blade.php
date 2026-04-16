@extends('layouts.app')

@section('content')
    <x-page-hero title="Kalender Akademik" />
    <section class="mx-auto max-w-5xl px-4 py-14">
        @foreach ($events as $event)
            <article class="mb-4 rounded-lg border border-black/10 bg-white p-5">
                <h2 class="font-display text-xl font-bold">{{ $event->title }}</h2>
                <p class="mt-2 text-sm">{{ $event->start_date->translatedFormat('d F Y') }}</p>
            </article>
        @endforeach
        {{ $events->links() }}
    </section>
@endsection
