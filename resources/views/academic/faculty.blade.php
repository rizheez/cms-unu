@extends('layouts.app')

@section('content')
    <x-page-hero :title="$faculty->name" :subtitle="$faculty->description" />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <h2 class="font-display text-3xl font-bold">Program Studi</h2>
        <div class="mt-6 grid gap-6 md:grid-cols-3">
            @foreach ($faculty->studyPrograms as $program)
                <x-prodi-card :program="$program" />
            @endforeach
        </div>
    </section>
@endsection
