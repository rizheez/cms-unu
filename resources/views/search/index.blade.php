@extends('layouts.app')

@section('content')
    <x-page-hero title="Cari" />
    <section class="mx-auto max-w-5xl px-4 py-14">
        <form class="mb-8 flex gap-3">
            <input class="flex-1 rounded-lg border border-black/10 p-3" name="q" value="{{ $query }}" placeholder="Kata kunci">
            <button class="rounded-lg bg-[#005f69] px-5 py-3 font-bold text-white">Cari</button>
        </form>
        @foreach (['Artikel' => $posts, 'Halaman' => $pages, 'Program Studi' => $studyPrograms] as $label => $items)
            <h2 class="mt-8 font-display text-2xl font-bold">{{ $label }}</h2>
            @forelse ($items as $item)
                <p class="mt-3 rounded-lg border border-black/10 bg-white p-4">{{ $item->title ?? $item->name }}</p>
            @empty
                <p class="mt-3 text-[#123136]/60">Tidak ada hasil.</p>
            @endforelse
        @endforeach
    </section>
@endsection
