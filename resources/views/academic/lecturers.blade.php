@extends('layouts.app')

@section('content')
    <x-page-hero title="Direktori Dosen" />
    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($lecturers as $lecturer)
                <article class="rounded-lg border border-black/10 bg-white p-6">
                    <h2 class="font-display text-xl font-bold"><a href="{{ route('academics.lecturer', $lecturer) }}">{{ $lecturer->name }}</a></h2>
                    <p class="mt-2 text-sm text-[#123136]/70">{{ $lecturer->studyProgram?->name }}</p>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $lecturers->links() }}</div>
    </section>
@endsection
