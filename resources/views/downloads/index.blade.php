@extends('layouts.app')

@section('content')
    <x-page-hero title="Unduhan" />
    <section class="mx-auto max-w-5xl px-4 py-14">
        @foreach ($downloads as $download)
            <article class="mb-4 flex items-center justify-between rounded-lg border border-black/10 bg-white p-6">
                <div>
                    <h2 class="font-display text-xl font-bold">{{ $download->title }}</h2>
                    <p class="mt-2 text-sm text-[#123136]/70">{{ $download->description }}</p>
                </div>
                <a class="rounded-full bg-[#ffc928] px-5 py-2 font-bold text-[#005f69]" href="{{ route('downloads.download', $download) }}">Unduh</a>
            </article>
        @endforeach
        {{ $downloads->links() }}
    </section>
@endsection
