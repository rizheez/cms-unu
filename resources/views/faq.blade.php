@extends('layouts.app')

@section('content')
    <x-page-hero title="FAQ" />
    <section class="mx-auto max-w-4xl px-4 py-14">
        @foreach ($faqs as $category => $items)
            <h2 class="mb-4 mt-8 font-display text-2xl font-bold">{{ $category ?: 'Umum' }}</h2>
            @foreach ($items as $faq)
                <details class="mb-3 rounded-lg border border-black/10 bg-white p-5">
                    <summary class="cursor-pointer font-bold">{{ $faq->question }}</summary>
                    <p class="mt-3 text-[#123136]/75">{{ $faq->answer }}</p>
                </details>
            @endforeach
        @endforeach
    </section>
@endsection
