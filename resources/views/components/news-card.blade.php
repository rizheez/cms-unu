@props(['post'])

<article class="card-lift overflow-hidden rounded-lg border border-black/10 bg-white shadow-sm">
    @if ($post->featured_image_url)
        <a href="{{ route('news.show', $post) }}" class="block aspect-video overflow-hidden bg-[#00a9b7]">
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="h-full w-full object-cover"
                loading="lazy"
            >
        </a>
    @else
        <div class="aspect-video bg-[#00a9b7]"></div>
    @endif
    <div class="p-5">
        <p class="text-sm font-semibold text-[#00a9b7]">{{ $post->category?->name ?? 'Berita' }}</p>
        <h3 class="mt-2 font-display text-2xl font-bold"><a href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h3>
        <p class="mt-3 text-sm text-[#123136]/70">{{ $post->excerpt }}</p>
    </div>
</article>
