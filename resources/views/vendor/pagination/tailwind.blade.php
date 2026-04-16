@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-[#123136]/70">
            Menampilkan
            <span class="font-semibold text-[#123136]">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="font-semibold text-[#123136]">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-[#123136]">{{ $paginator->total() }}</span>
            data
        </p>

        <div class="flex flex-wrap items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-md border border-[#00a9b7]/20 bg-white px-3 text-sm font-semibold text-[#123136]/35">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 min-w-10 items-center justify-center rounded-md border border-[#00a9b7]/25 bg-white px-3 text-sm font-semibold text-[#006b73] transition hover:border-[#00a9b7] hover:bg-[#e9fbf8]">
                    Sebelumnya
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-md border border-[#00a9b7]/20 bg-white px-3 text-sm font-semibold text-[#123136]/45">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-md border border-[#00a9b7] bg-[#00a9b7] px-3 text-sm font-bold text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-md border border-[#00a9b7]/25 bg-white px-3 text-sm font-semibold text-[#006b73] transition hover:border-[#00a9b7] hover:bg-[#e9fbf8]">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 min-w-10 items-center justify-center rounded-md border border-[#00a9b7]/25 bg-white px-3 text-sm font-semibold text-[#006b73] transition hover:border-[#00a9b7] hover:bg-[#e9fbf8]">
                    Berikutnya
                </a>
            @else
                <span class="inline-flex h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-md border border-[#00a9b7]/20 bg-white px-3 text-sm font-semibold text-[#123136]/35">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif
