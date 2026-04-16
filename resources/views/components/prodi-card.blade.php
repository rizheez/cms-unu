@props(['program'])

<article class="card-lift rounded-lg border border-black/10 bg-white p-6 shadow-sm">
    <p class="text-sm font-bold text-[#00a9b7]">{{ $program->degree_level }}</p>
    <h3 class="mt-2 font-display text-2xl font-bold">{{ $program->name }}</h3>
    <p class="mt-2 text-sm text-[#123136]/70">{{ $program->faculty?->name }}</p>
    <p class="mt-4 inline-flex rounded-full bg-[#ffc928] px-3 py-1 text-sm font-bold text-[#005f69]">{{ $program->accreditation ?: 'Akreditasi' }}</p>
</article>
