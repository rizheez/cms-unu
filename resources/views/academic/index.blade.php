@extends('layouts.app')

@section('content')
    <x-page-hero title="Akademik" subtitle="Fakultas dan program studi Universitas Nahdlatul Ulama." />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($faculties as $faculty)
                <article class="rounded-lg border border-black/10 bg-white p-6">
                    <h2 class="font-display text-2xl font-bold"><a href="{{ route('academics.faculty', $faculty) }}">{{ $faculty->name }}</a></h2>
                    <p class="mt-3 text-[#123136]/70">{{ $faculty->description }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
