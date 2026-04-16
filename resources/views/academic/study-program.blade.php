@extends('layouts.app')

@section('content')
    <x-page-hero :title="$studyProgram->name" :subtitle="$studyProgram->faculty?->name" />
    <section class="mx-auto max-w-4xl px-4 py-14">
        <p class="text-lg">{{ $studyProgram->description }}</p>
        <p class="mt-6 rounded-full bg-[#ffc928] px-4 py-2 font-bold text-[#005f69]">Akreditasi {{ $studyProgram->accreditation ?: '-' }}</p>
    </section>
@endsection
