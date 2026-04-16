@extends('layouts.app')

@section('content')
    <x-page-hero :title="$post->title" :subtitle="$post->category?->name" />

    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-14 lg:grid-cols-[minmax(0,1fr)_320px]">
        <div>
            <article class="overflow-hidden rounded-3xl border border-black/10 bg-white shadow-sm">
                @if ($post->featured_image_url)
                    <figure>
                        <img
                            src="{{ $post->featured_image_url }}"
                            alt="{{ $post->title }}"
                            class="aspect-video w-full object-cover"
                        >
                    </figure>
                @endif

                <div class="border-b border-black/10 px-6 py-5 text-sm text-[#123136]/65 md:px-8">
                    <div class="flex flex-wrap items-center gap-3">
                        <span>{{ $post->published_at?->translatedFormat('d F Y') ?? 'Terbaru' }}</span>
                        <span>|</span>
                        <span>{{ $post->reading_time }} menit baca</span>
                        <span>|</span>
                        <span>{{ number_format((int) $post->views) }} dilihat</span>
                    </div>
                </div>

                <div class="prose max-w-none px-6 py-8 md:px-8">
                    {!! $post->content !!}
                </div>
            </article>
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
                            <p class="text-xs font-semibold text-[#00a9b7]">{{ $latestPost->category?->name ?? 'Berita' }}</p>
                            <h3 class="mt-2 text-sm font-semibold leading-6 text-[#123136]">{{ $latestPost->title }}</h3>
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
    </section>
@endsection
