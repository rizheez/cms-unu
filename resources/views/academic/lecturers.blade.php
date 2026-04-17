@extends('layouts.app')

@section('content')
    <x-page-hero title="Direktori Dosen" />

    <section class="mx-auto max-w-7xl px-4 py-14">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($lecturers as $lecturer)
                <article class="overflow-hidden rounded-lg border border-black/10 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="{{ route('academics.lecturer', $lecturer->slug) }}" class="block">
                        <div class="grid aspect-[4/3] place-items-center bg-[linear-gradient(135deg,#f4fffc,#d8f7f2)]">
                            @if ($lecturer->photo_url)
                                <img
                                    src="{{ $lecturer->photo_url }}"
                                    alt="Foto {{ $lecturer->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <span class="font-display text-5xl font-extrabold text-[#005f69]/20">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($lecturer->name, 0, 2)) }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h2 class="font-display text-xl font-bold text-[#123136]">{{ $lecturer->name }}</h2>
                            <p class="mt-2 text-sm font-semibold text-[#00a9b7]">{{ $lecturer->position ?: 'Dosen' }}</p>
                            <p class="mt-3 text-sm text-[#123136]/70">{{ $lecturer->studyProgram?->name ?? $lecturer->faculty?->name }}</p>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        @if ($lecturers->isEmpty())
            <div class="rounded-lg border border-dashed border-black/10 bg-white p-8 text-center text-[#123136]/60">
                Belum ada dosen yang ditampilkan.
            </div>
        @endif

        <div class="mt-8">
            {{ $lecturers->links() }}
        </div>
    </section>
@endsection
