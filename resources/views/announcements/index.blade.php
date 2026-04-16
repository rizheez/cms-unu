@extends('layouts.app')

@section('content')
    <x-page-hero title="Pengumuman" />
    <section class="mx-auto max-w-5xl px-4 py-14">
        @foreach ($announcements as $announcement)
            <article class="mb-4 rounded-lg border border-black/10 bg-white p-6">
                <h2 class="font-display text-2xl font-bold">{{ $announcement->title }}</h2>
                <div class="mt-3">{!! $announcement->content !!}</div>
            </article>
        @endforeach
        {{ $announcements->links() }}
    </section>
@endsection
