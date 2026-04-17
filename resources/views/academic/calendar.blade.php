@extends('layouts.app')

@section('content')
    <x-page-hero title="Kalender Akademik" />

    <section class="mx-auto max-w-6xl px-4 py-14">
        <div class="mb-10 max-w-3xl">
            <span class="section-label">Agenda Kampus</span>
            <h2 class="section-title mt-3">Tanggal perkuliahan, ujian, penerimaan, dan wisuda.</h2>
            <p class="mt-4 text-base leading-7 text-[#123136]/65">
                Pantau jadwal akademik resmi agar setiap tahapan perkuliahan dapat dipersiapkan lebih awal.
            </p>
        </div>

        @if ($events->isNotEmpty())
            <div
                class="relative space-y-5 before:absolute before:bottom-0 before:left-5 before:top-0 before:w-px before:bg-black/10">
                @foreach ($events as $event)
                    @php
                        $eventColor =
                            preg_match('/^#[0-9a-fA-F]{6}$/', (string) $event->color) === 1 ? $event->color : '#00a9b7';
                        $dateRange = $event->start_date?->translatedFormat('d F Y') ?? 'Tanggal belum ditentukan';

                        if ($event->end_date && !$event->end_date->isSameDay($event->start_date)) {
                            $dateRange .= ' - ' . $event->end_date->translatedFormat('d F Y');
                        }
                    @endphp

                    <article class="relative pl-14">
                        <span
                            class="absolute left-0 top-6 grid h-10 w-10 place-items-center rounded-full border-4 border-[#f4fffc] shadow-sm"
                            style="background-color: {{ $eventColor }}" aria-hidden="true"></span>

                        <div
                            class="rounded-lg border border-black/10 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h3 class="font-display text-2xl font-bold text-[#123136]">{{ $event->title }}</h3>
                                    <p class="mt-2 text-sm font-semibold text-[#123136]/60">{{ $dateRange }}</p>
                                </div>

                                @if ($event->category)
                                    <span
                                        class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-[0.06em] text-white"
                                        style="background-color: {{ $eventColor }}">
                                        {{ $event->category }}
                                    </span>
                                @endif
                            </div>

                            @if ($event->description)
                                <p class="mt-5 max-w-3xl text-sm leading-7 text-[#123136]/70">
                                    {{ $event->description }}
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $events->links() }}
            </div>
        @else
            <div class="rounded-lg border border-dashed border-black/10 bg-white p-8 text-center text-[#123136]/60">
                Kalender akademik belum tersedia.
            </div>
        @endif
    </section>
@endsection
