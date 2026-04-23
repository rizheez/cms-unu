@extends('layouts.app')

@section('content')
    <x-page-hero title="Berita & Artikel" />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="mb-8 flex flex-wrap gap-3">
            @foreach ($categories as $category)
                <a
                    @class([
                        'rounded-full border px-4 py-2 font-semibold transition',
                        'border-[#123136] bg-[#123136] text-white' => isset($activeCategory) && $activeCategory->is($category),
                        'border-black/10 bg-white text-[#123136]' => ! isset($activeCategory) || ! $activeCategory->is($category),
                    ])
                    href="{{ route('news.category', $category) }}"
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="space-y-8">
            @forelse ($posts as $post)
                <x-news-card :post="$post" horizontal />
            @empty
                <div class="rounded-3xl border border-dashed border-black/10 bg-white p-8 text-sm text-[#123136]/70">
                    Belum ada berita yang ditampilkan.
                </div>
            @endforelse

            <div>{{ $posts->links() }}</div>
        </div>
    </section>
@endsection
