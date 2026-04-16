@extends('layouts.app')

@section('content')
    <x-page-hero :title="$gallery->title" :subtitle="$gallery->description" />
    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-14 md:grid-cols-3">
        @foreach ($gallery->items as $item)
            <figure class="rounded-lg border border-black/10 bg-white p-4">
                <div class="aspect-video rounded-lg bg-[#d8f7f2]"></div>
                <figcaption class="mt-3 text-sm">{{ $item->caption }}</figcaption>
            </figure>
        @endforeach
    </section>
@endsection
