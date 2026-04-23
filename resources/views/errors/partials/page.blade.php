@php
    $resolvedStatus = (string) ($status ?? 'Error');
    $resolvedTitle = $title ?? 'Terjadi kendala pada halaman ini.';
    $resolvedDescription = $description ?? 'Permintaan Anda belum bisa kami proses saat ini.';
    $resolvedPrimaryAction = $primaryAction ?? [
        'label' => 'Kembali ke beranda',
        'url' => route('home'),
    ];
    $resolvedSecondaryAction = $secondaryAction ?? [
        'label' => 'Hubungi kami',
        'url' => route('contact.create'),
    ];
    $resolvedHints = $hints ?? [
        'Periksa kembali alamat halaman yang Anda buka.',
        'Gunakan menu utama untuk kembali menjelajahi situs.',
        'Hubungi tim kampus jika masalah terus berulang.',
    ];
    $statusLabel = 'Status ' . $resolvedStatus;
    $exceptionMessage = trim((string) ($exception?->getMessage() ?? ''));
@endphp

<section class="error-page">
    <div class="error-page__shell">
        <div class="error-page__panel">
            <div class="error-page__copy">
                <span class="error-page__eyebrow">{{ $statusLabel }}</span>
                <p class="error-page__code">{{ $resolvedStatus }}</p>
                <h1>{{ $resolvedTitle }}</h1>
                <p class="error-page__description">{{ $resolvedDescription }}</p>

                @if ($exceptionMessage !== '' && app()->hasDebugModeEnabled())
                    <div class="error-page__message">
                        <strong>Detail:</strong>
                        <span>{{ $exceptionMessage }}</span>
                    </div>
                @endif

                <div class="error-page__actions">
                    <a href="{{ $resolvedPrimaryAction['url'] }}" class="btn-primary">
                        {{ $resolvedPrimaryAction['label'] }}
                    </a>
                    <a href="{{ $resolvedSecondaryAction['url'] }}" class="btn-secondary">
                        {{ $resolvedSecondaryAction['label'] }}
                    </a>
                </div>
            </div>

            <aside class="error-page__card" aria-label="Saran navigasi">
                <span class="error-page__card-badge">Bantuan cepat</span>
                <h2>Lanjut dari sini</h2>
                <ul class="error-page__hint-list">
                    @foreach ($resolvedHints as $hint)
                        <li>{{ $hint }}</li>
                    @endforeach
                </ul>

                {{-- <div class="error-page__quick-links">
                    <a href="{{ route('news.index') }}">Berita terbaru</a>
                    <a href="{{ route('academics.faculties') }}">Program studi</a>
                    <a href="{{ route('search.index') }}">Cari halaman</a>
                </div> --}}
            </aside>
        </div>
    </div>
</section>
