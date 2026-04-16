@extends('layouts.app')

@section('content')
    <x-page-hero :title="$title" />
    <section class="mx-auto max-w-4xl px-4 py-14">
        <div class="prose max-w-none rounded-lg border border-black/10 bg-white p-8">
            {!! $body !!}
        </div>
    </section>
@endsection
