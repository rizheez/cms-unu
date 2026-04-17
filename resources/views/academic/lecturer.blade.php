@extends('layouts.app')

@section('content')
    <x-page-hero :title="$lecturer->name" />

    <section class="mx-auto grid max-w-6xl gap-8 px-4 py-14 lg:grid-cols-[360px_minmax(0,1fr)]">
        <aside class="overflow-hidden rounded-lg border border-black/10 bg-white shadow-sm">
            <div class="grid aspect-[4/5] place-items-center bg-[linear-gradient(135deg,#f4fffc,#d8f7f2)]">
                @if ($lecturer->photo_url)
                    <img
                        src="{{ $lecturer->photo_url }}"
                        alt="Foto {{ $lecturer->name }}"
                        class="h-full w-full object-cover"
                    >
                @else
                    <span class="font-display text-7xl font-extrabold text-[#005f69]/20">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($lecturer->name, 0, 2)) }}
                    </span>
                @endif
            </div>

            <div class="space-y-4 p-6">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Jabatan</p>
                    <p class="mt-1 font-display text-xl font-bold text-[#123136]">{{ $lecturer->position ?: 'Dosen' }}</p>
                </div>

                @if ($lecturer->studyProgram || $lecturer->faculty)
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Unit Akademik</p>
                        <p class="mt-1 text-sm font-semibold text-[#123136]">{{ $lecturer->studyProgram?->name ?? $lecturer->faculty?->name }}</p>
                        @if ($lecturer->studyProgram && $lecturer->faculty)
                            <p class="mt-1 text-sm text-[#123136]/60">{{ $lecturer->faculty->name }}</p>
                        @endif
                    </div>
                @endif

                @if ($lecturer->email)
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Email</p>
                        <a href="mailto:{{ $lecturer->email }}" class="mt-1 block break-words text-sm font-semibold text-[#123136]">{{ $lecturer->email }}</a>
                    </div>
                @endif
            </div>
        </aside>

        <article class="rounded-lg border border-black/10 bg-white p-8 shadow-sm">
            <div class="grid gap-4 border-b border-black/10 pb-6 sm:grid-cols-2">
                @if ($lecturer->nidn)
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">NIDN</p>
                        <p class="mt-1 font-semibold text-[#123136]">{{ $lecturer->nidn }}</p>
                    </div>
                @endif

                @if ($lecturer->education_level)
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Pendidikan</p>
                        <p class="mt-1 font-semibold text-[#123136]">{{ $lecturer->education_level }}</p>
                    </div>
                @endif

                @if ($lecturer->expertise)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Keahlian</p>
                        <p class="mt-1 font-semibold text-[#123136]">{{ $lecturer->expertise }}</p>
                    </div>
                @endif
            </div>

            <div class="prose mt-8 max-w-none">
                {!! $lecturer->bio ?: '<p>Profil dosen belum tersedia.</p>' !!}
            </div>
        </article>
    </section>
@endsection
