@extends('layouts.app')

@section('content')
    <x-page-hero :title="$gallery->title" :subtitle="$gallery->description" />
    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-14 md:grid-cols-3">
        @forelse ($gallery->items as $item)
            <figure class="overflow-hidden rounded-lg border border-black/10 bg-white">
                @if ($item->youtube_embed_url)
                    <iframe
                        class="aspect-video w-full"
                        src="{{ $item->youtube_embed_url }}"
                        title="{{ $item->caption ?: $gallery->title }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>
                @elseif ($item->image)
                    <img
                        src="{{ asset('storage/'.$item->image) }}"
                        alt="{{ $item->caption ?: $gallery->title }}"
                        class="aspect-video w-full object-cover"
                        loading="lazy"
                    >
                @else
                    <div class="flex aspect-video items-center justify-center bg-[#d8f7f2] px-6 text-center text-sm text-[#123136]/70">
                        Media galeri belum diisi.
                    </div>
                @endif

                @if ($item->caption)
                    <figcaption class="p-4 text-sm text-[#123136]/80">{{ $item->caption }}</figcaption>
                @endif
            </figure>
        @empty
            <p class="rounded-lg border border-black/10 bg-white p-6 text-sm text-[#123136]/70 md:col-span-3">
                Belum ada item galeri.
            </p>
        @endforelse
    </section>
@endsection
