@props(['post'])

<article class="card-lift overflow-hidden rounded-lg border border-black/10 bg-white shadow-sm">
    <div class="aspect-video bg-[#00a9b7]"></div>
    <div class="p-5">
        <p class="text-sm font-semibold text-[#00a9b7]">{{ $post->category?->name ?? 'Berita' }}</p>
        <h3 class="mt-2 font-display text-2xl font-bold"><a href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h3>
        <p class="mt-3 text-sm text-[#123136]/70">{{ $post->excerpt }}</p>
    </div>
</article>
