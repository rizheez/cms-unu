@props([
    'post',
    'horizontal' => false,
])

<article @class([
    'card-lift overflow-hidden rounded-3xl border border-black/10 bg-white shadow-sm',
    'md:grid md:grid-cols-[320px_minmax(0,1fr)]' => $horizontal,
])>
    @if ($post->featured_image_url)
        <a
            href="{{ route('news.show', $post) }}"
            @class([
                'block overflow-hidden bg-[#00a9b7]',
                'aspect-video' => ! $horizontal,
                'h-full min-h-[220px]' => $horizontal,
            ])
        >
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="h-full w-full object-cover"
                loading="lazy"
            >
        </a>
    @else
        <div @class([
            'bg-[#00a9b7]',
            'aspect-video' => ! $horizontal,
            'h-full min-h-[220px]' => $horizontal,
        ])></div>
    @endif
    <div class="p-6 md:p-8">
        <p class="text-sm font-semibold text-[#00a9b7]">{{ $post->category?->name ?? 'Berita' }}</p>
        <h3 class="mt-2 font-display text-2xl font-bold leading-tight">
            <a href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
        </h3>
        <p class="mt-3 text-sm leading-7 text-[#123136]/70">{{ $post->excerpt }}</p>
        <div class="mt-5 flex flex-wrap items-center gap-3 text-xs text-[#123136]/60">
            <span>{{ $post->published_at?->translatedFormat('d M Y') ?? 'Terbaru' }}</span>
            <span>|</span>
            <span>{{ $post->reading_time }} menit baca</span>
        </div>
        <a href="{{ route('news.show', $post) }}" class="mt-6 inline-flex items-center text-sm font-semibold text-[#123136]">
            Baca selengkapnya
        </a>
    </div>
</article>
