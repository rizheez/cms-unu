@props([
    'title',
    'subtitle' => null,
    'badge' => 'Halaman',
])

<section class="relative overflow-hidden bg-[linear-gradient(160deg,#09c3cd_0%,#005f69_100%)] px-4 py-12 text-white sm:px-6 lg:px-12 lg:py-14">
    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:72px_72px]"></div>
    <div class="absolute -left-32 -top-32 h-[500px] w-[500px] rounded-full bg-[radial-gradient(circle,rgba(255,201,40,0.55),transparent_70%)]"></div>

    <div class="relative z-10 mx-auto max-w-4xl">
        <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-white/60">
            <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
            <span>/</span>
            <span class="text-white/90">{{ $title }}</span>
        </nav>

        <div class="mt-7 inline-flex items-center rounded-full bg-[#ffc928] px-4 py-2 text-xs font-extrabold uppercase tracking-[0.06em] text-[#005f69]">
            {{ $badge }}
        </div>

        <h1 class="mt-6 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-[52px]">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-5 max-w-3xl text-base leading-8 text-white/75">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</section>
