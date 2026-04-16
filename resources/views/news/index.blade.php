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

        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_320px]">
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

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-3xl border border-black/10 bg-white p-6 shadow-sm">
                    <h2 class="font-display text-xl font-bold text-[#123136]">Berita Terbaru</h2>
                    <div class="mt-5 space-y-4">
                        @forelse ($latestPosts as $latestPost)
                            <a
                                href="{{ route('news.show', $latestPost) }}"
                                class="block rounded-2xl border border-black/5 px-4 py-3 transition hover:border-[#00a9b7]/30 hover:bg-[#00a9b7]/5"
                            >
                                <h3 class="text-sm font-semibold leading-6 text-[#123136]">{{ $latestPost->title }}</h3>
                                <p class="mt-2 text-xs text-[#123136]/60">
                                    {{ $latestPost->published_at?->translatedFormat('d F Y') ?? 'Terbaru' }}
                                </p>
                            </a>
                        @empty
                            <p class="text-sm text-[#123136]/70">Belum ada berita terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
