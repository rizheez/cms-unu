@props(['title', 'subtitle' => null])

<section class="bg-gradient-to-br from-[#00a9b7] to-[#005f69] px-4 py-16 text-white">
    <div class="mx-auto max-w-7xl">
        <h1 class="font-display text-4xl font-extrabold md:text-6xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-4 max-w-2xl text-white/75">{{ $subtitle }}</p>
        @endif
    </div>
</section>
