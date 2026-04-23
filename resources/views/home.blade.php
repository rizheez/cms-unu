@extends('layouts.app')

@section('content')
    @php
        $featured = $featuredPosts->first();
        $smallPosts = $featuredPosts->skip(1)->take(3);
        $studentsCount = (int) setting('home_students_count', 12400);
        $serviceYears = (int) setting('home_service_years', 30);
        $homeHeroVideo = trim((string) setting('home_hero_video', ''));
        $homeHeroVideoUrl =
            $homeHeroVideo !== ''
                ? (str($homeHeroVideo)->startsWith(['http://', 'https://'])
                    ? $homeHeroVideo
                    : \Illuminate\Support\Facades\Storage::disk('public')->url($homeHeroVideo))
                : null;
        $homeAboutImage = trim((string) setting('home_about_image', ''));
        $homeAboutImageUrl =
            $homeAboutImage !== ''
                ? (str($homeAboutImage)->startsWith(['http://', 'https://'])
                    ? $homeAboutImage
                    : \Illuminate\Support\Facades\Storage::disk('public')->url($homeAboutImage))
                : null;
        $programIcons = [
            'Informatika' => 'IT',
            'Sistem Informasi' => 'SI',
            'Teknologi Pangan' => 'TP',
            'Manajemen' => 'MB',
            'Akuntansi' => 'AK',
            'Pendidikan Guru Sekolah Dasar' => 'PG',
            'Pendidikan Bahasa Inggris' => 'EN',
        ];
        $announcementTypes = ['info', 'warning', 'success'];
        $popupAnnouncementKey = $popupAnnouncements
            ->map(fn($announcement) => $announcement->id . '-' . $announcement->updated_at?->timestamp)
            ->implode('|');
    @endphp

    @if ($popupAnnouncements->isNotEmpty())
        <div class="announcement-modal" data-announcement-modal
            data-announcement-key="announcements-{{ md5($popupAnnouncementKey) }}" role="dialog" aria-modal="true"
            aria-labelledby="announcement-modal-title" hidden>
            <div class="announcement-modal__panel">
                <button class="announcement-modal__close" type="button" data-announcement-close
                    aria-label="Tutup pengumuman">x</button>

                <div class="announcement-modal__slides">
                    @foreach ($popupAnnouncements as $popupAnnouncement)
                        @php
                            $popupAnnouncementType = in_array($popupAnnouncement->type, $announcementTypes, true)
                                ? $popupAnnouncement->type
                                : 'info';
                        @endphp

                        <article class="announcement-modal__slide" data-announcement-slide
                            @if (!$loop->first) hidden @endif>
                            {{-- <span class="announcement-badge announcement-badge--{{ $popupAnnouncementType }}">
                                {{ $popupAnnouncement->type }}
                            </span> --}}
                            @if ($popupAnnouncement->image_url)
                                <img class="announcement-image announcement-image--modal"
                                    src="{{ $popupAnnouncement->image_url }}" alt="{{ $popupAnnouncement->title }}">
                            @endif
                            <p class="announcement-modal__title"
                                @if ($loop->first) id="announcement-modal-title" @endif>
                                {{ $popupAnnouncement->title }}</p>
                            @if (filled($popupAnnouncement->content))
                                <div class="announcement-rich">{!! $popupAnnouncement->content !!}</div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="announcement-modal__footer">
                    @if ($popupAnnouncements->count() > 1)
                        <div class="announcement-modal__controls" aria-label="Navigasi pengumuman popup">
                            <button type="button" data-announcement-prev aria-label="Pengumuman sebelumnya">‹</button>
                            <span data-announcement-counter>1 / {{ $popupAnnouncements->count() }}</span>
                            <button type="button" data-announcement-next aria-label="Pengumuman berikutnya">›</button>
                        </div>
                    @endif
                    <a href="{{ route('announcements.index') }}" class="announcement-modal__link">Lihat semua pengumuman</a>
                </div>
            </div>
        </div>
    @endif

    <section @class(['hero noise', 'has-hero-video' => $homeHeroVideoUrl])>
        @if ($homeHeroVideoUrl)
            <video class="hero-bg-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                <source src="{{ $homeHeroVideoUrl }}">
            </video>
            <div class="hero-video-shade" aria-hidden="true"></div>
        @endif

        <svg class="arabesque" viewBox="0 0 400 400" aria-hidden="true">
            <path
                d="M200 20C240 90 310 100 380 100C310 140 300 210 300 280C260 210 190 200 120 200C190 160 200 90 200 20Z" />
            <path d="M70 250C100 300 150 305 200 305C150 330 145 375 145 395C115 350 65 345 20 345C65 315 70 285 70 250Z" />
        </svg>

        <div class="hero-copy" data-reveal>
            <span class="hero-badge">Akreditasi {{ setting('accreditation', 'Baik') }} BAN-PT</span>
            <h1>{{ setting('site_name', 'Universitas Nahdlatul Ulama Kalimantan Timur') }}</h1>
            <p>{{ setting('meta_description', 'Kampus yang menumbuhkan pengetahuan, karakter, dan kontribusi nyata untuk masyarakat.') }}
            </p>
            <div class="hero-btns">
                <a href="{{ route('academics.faculties') }}" class="btn-primary">Jelajahi Program</a>
                <a href="https://pmb.unukaltim.ac.id" target="_blank" rel="noopener" class="btn-outline">Konsultasi PMB</a>
            </div>
        </div>

        {{-- <div class="hero-visual" data-reveal data-delay="2">
            <div class="hero-img-frame">
                <div class="hero-img-placeholder">
                    <div class="campus-icon">🏛️</div>
                    <span>Foto Kampus UNU</span>
                </div>
            </div>
            <div class="hero-float-badge">
                <small>Tahun Berdiri</small>
                1994
            </div>
            <div class="hero-float-2">
                {{ setting('accreditation', 'Baik') == 'Baik Sekali' ? 'A+' : 'B+' }}
                <small>Akreditasi</small>
            </div>
        </div> --}}

    </section>

    <section class="stats-strip">
        <div class="stat-item" data-reveal>
            <span class="stat-num" data-count="{{ $studentsCount }}" data-suffix="+">0</span>
            <span class="stat-label">Mahasiswa</span>
        </div>
        <div class="stat-item" data-reveal data-delay="1">
            <span class="stat-num" data-count="{{ $lecturersCount }}" data-suffix="+">0</span>
            <span class="stat-label">Dosen & Praktisi</span>
        </div>
        <div class="stat-item" data-reveal data-delay="2">
            <span class="stat-num" data-count="{{ $studyProgramsCount }}">0</span>
            <span class="stat-label">Program Studi</span>
        </div>
        <div class="stat-item" data-reveal data-delay="3">
            <span class="stat-num" data-count="{{ $serviceYears }}" data-suffix="+">0</span>
            <span class="stat-label">Tahun Pengabdian</span>
        </div>
    </section>

    <div class="diagonal-divider" aria-hidden="true"></div>

    <section class="section-wrap">
        <div class="section-head" data-reveal>
            <div>
                <span class="section-label">Kabar Kampus</span>
                <h2 class="section-title">
                    {{-- Cerita terbaru dari ruang belajar dan pengabdian. --}}
                    Berita & Artikel Terbaru
                </h2>
            </div>
            <a href="{{ route('news.index') }}" class="section-link">Lihat semua berita</a>
        </div>

        <div class="news-grid">
            @if ($featured)
                <article class="news-featured card-lift" data-reveal>
                    <a href="{{ route('news.show', $featured->slug) }}">
                        <div class="news-image {{ $featured->featured_image_url ? 'has-image' : '' }}">
                            @if ($featured->featured_image_url)
                                <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}"
                                    loading="lazy">
                            @endif
                            <span>{{ \Illuminate\Support\Str::upper($featured->category?->name ?? 'Berita') }}</span>
                        </div>
                        <div class="news-content">
                            <p class="news-meta">{{ $featured->published_at?->translatedFormat('d F Y') ?? 'Terbaru' }} /
                                {{ $featured->reading_time }} menit baca</p>
                            <h3>{{ $featured->title }}</h3>
                            <p>{{ $featured->excerpt }}</p>
                        </div>
                    </a>
                </article>
            @endif

            <div class="news-small-stack">
                @forelse ($smallPosts as $post)
                    <article class="news-small card-lift" data-reveal data-delay="{{ $loop->iteration }}">
                        <a href="{{ route('news.show', $post->slug) }}">
                            <span class="news-pill">{{ $post->category?->name ?? 'Berita' }}</span>
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->published_at?->translatedFormat('d M Y') ?? 'Terbaru' }}</p>
                        </a>
                    </article>
                @empty
                    <div class="empty-state" data-reveal>Belum ada artikel lain yang ditampilkan.</div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($announcements->isNotEmpty())
        <section class="section-wrap announcement-section">
            <div class="section-head" data-reveal>
                <div>
                    <span class="section-label">Pengumuman</span>
                    <h2 class="section-title">Info penting kampus.</h2>
                </div>
                <a href="{{ route('announcements.index') }}" class="section-link">Semua pengumuman</a>
            </div>

            <div class="announcement-grid">
                @foreach ($announcements as $announcement)
                    @php
                        $announcementType = in_array($announcement->type, $announcementTypes, true)
                            ? $announcement->type
                            : 'info';
                    @endphp

                    <article class="announcement-card announcement-card--{{ $announcementType }} card-lift" data-reveal
                        data-delay="{{ ($loop->iteration - 1) % 3 }}">
                        <div class="announcement-card__meta">
                            <span class="announcement-badge announcement-badge--{{ $announcementType }}">
                                {{ $announcement->type }}
                            </span>
                            <time datetime="{{ $announcement->created_at?->toDateString() }}">
                                {{ $announcement->created_at?->translatedFormat('d M Y') ?? 'Terbaru' }}
                            </time>
                        </div>
                        @if ($announcement->image_url)
                            <img class="announcement-image announcement-image--card" src="{{ $announcement->image_url }}"
                                alt="{{ $announcement->title }}" loading="lazy">
                        @endif
                        <h3>{{ $announcement->title }}</h3>
                        @if (filled($announcement->content))
                            <div class="announcement-card__content announcement-rich">
                                {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 150) }}</div>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-wrap prodi-section">
        <div class="section-head" data-reveal>
            <div>
                <span class="section-label">Akademik</span>
                <h2 class="section-title">
                    {{-- Pilih jalur belajar yang paling dekat dengan masa depanmu. --}}
                    Program Studi
                    <span class="underline-lime">Unggulan</span>
                </h2>
            </div>
            <a href="{{ route('academics.faculties') }}" class="section-link">Semua program</a>
        </div>

        <div class="prodi-grid">
            @foreach ($studyPrograms->take(6) as $program)
                <a href="{{ route('academics.study-program', $program->slug) }}" class="prodi-card card-lift" data-reveal
                    data-delay="{{ ($loop->iteration - 1) % 3 }}">
                    <span
                        class="prodi-icon">{{ $programIcons[$program->name] ?? \Illuminate\Support\Str::substr($program->name, 0, 2) }}</span>
                    <span class="prodi-level">{{ $program->degree }}</span>
                    <h3>{{ $program->name }}</h3>
                    <p>{{ $program->faculty?->name }}</p>
                    <strong>Akreditasi {{ $program->accreditation }}</strong>
                </a>
            @endforeach
        </div>
    </section>

    <section class="about-split">
        <div class="about-visual {{ $homeAboutImageUrl ? 'has-image' : '' }}" data-reveal>
            @if ($homeAboutImageUrl)
                <img src="{{ $homeAboutImageUrl }}"
                    alt="Suasana kampus {{ setting('site_name', 'Universitas Nahdlatul Ulama') }}" loading="lazy">
            @endif
            <strong>Ilmu, adab, dan karya.</strong>
        </div>
        <div class="about-copy" data-reveal data-delay="1">
            <span class="section-label">Tentang UNUKALTIM</span>
            <h2 class="section-title">Warisan Ilmu,
                Masa Depan Gemilang</h2>
            <p class="italic leading-relaxed mb-28 text-xl">Kampus yang menumbuhkan pengetahuan, karakter, dan kontribusi nyata untuk masyarakat.</p>
            <p class="text-base">Universitas Nahdlatul Ulama Kalimantan Timur adalah institusi pendidikan tinggi yang berkomitmen untuk menghasilkan lulusan yang kompeten, berakhlak mulia, dan berkontribusi positif di era digital.</p>
        </div>
    </section>

    <section class="gallery-section noise">
        <div class="section-head" data-reveal>
            <div>
                <span class="section-label">Galeri</span>
                <h2 class="section-title">Momen kampus yang hidup.</h2>
            </div>
            <a href="{{ route('gallery.index') }}" class="section-link">Buka galeri</a>
        </div>

        <div class="gallery-grid">
            @forelse ($galleries->take(5) as $gallery)
                <a href="{{ route('gallery.show', $gallery->slug) }}"
                    class="gallery-item {{ $loop->first ? 'featured' : '' }}" data-reveal
                    data-delay="{{ ($loop->iteration - 1) % 3 }}">
                    <span>{{ $gallery->title }}</span>
                    <p>{{ $gallery->event_date?->translatedFormat('d M Y') ?? 'Dokumentasi' }}</p>
                </a>
            @empty
                <div class="empty-state">Belum ada galeri kampus.</div>
            @endforelse
        </div>
    </section>

    @if ($partners->isNotEmpty())
        <section class="partners-band">
            <span class="section-label">Mitra Kolaborasi</span>
            <div class="partners-marquee">
                <div class="partners-track">
                    @foreach ($partners->concat($partners) as $partner)
                        @php
                            $partnerLogoUrl = $partner->logo
                                ? (str($partner->logo)->startsWith(['http://', 'https://'])
                                    ? $partner->logo
                                    : \Illuminate\Support\Facades\Storage::disk('public')->url($partner->logo))
                                : null;
                        @endphp

                        <span class="partner-logo-card">
                            @if ($partnerLogoUrl)
                                <img src="{{ $partnerLogoUrl }}" alt="Logo {{ $partner->name }}" loading="lazy"
                                    onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                <span hidden>{{ $partner->name }}</span>
                            @else
                                <span>{{ $partner->name }}</span>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
