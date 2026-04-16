<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $defaultTickerText = 'Pendaftaran Mahasiswa Baru telah dibuka - Daftarkan dirimu sekarang! - Wisuda Sarjana akan diselenggarakan semester ini - UNU raih akreditasi ' . setting('accreditation', 'Baik Sekali') . ' - Seminar nasional terbuka untuk umum';
        $tickerText = trim((string) setting('ticker_text', $defaultTickerText));
        $tickerText = $tickerText !== '' ? $tickerText : $defaultTickerText;
    @endphp

    <a href="https://wa.me/{{ preg_replace('/\D+/', '', (string) setting('site_phone', '6281240002026')) }}" class="wa-float" aria-label="WhatsApp">WA</a>

    <div class="ticker-bar">
        <span class="ticker-label">Info</span>
        <div class="ticker-content">
            <span class="ticker-text">{{ $tickerText }} &nbsp;&nbsp; - &nbsp;&nbsp; {{ $tickerText }}</span>
        </div>
    </div>

    <header id="main-header" class="site-header">
        <a href="{{ route('home') }}" class="site-logo">
            <span class="logo-icon">NU</span>
            <span class="logo-text">
                {{ setting('site_name', 'Universitas Nahdlatul Ulama') }}
                <small>Indonesia</small>
            </span>
        </a>

        <nav aria-label="Navigasi utama">
            <ul class="site-nav">
                @forelse ($headerMenuItems as $item)
                    @include('partials.site-menu-item', ['item' => $item])
                @empty
                    <li class="site-nav-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="site-nav-item"><a href="{{ route('about.profile') }}">Tentang</a></li>
                    <li class="site-nav-item"><a href="{{ route('academics.index') }}">Akademik</a></li>
                    <li class="site-nav-item"><a href="{{ route('news.index') }}">Berita</a></li>
                    <li class="site-nav-item"><a href="{{ route('contact.create') }}">Kontak</a></li>
                @endforelse
            </ul>
        </nav>

        <a href="{{ url('/penerimaan-mahasiswa-baru') }}" class="btn-nav">Daftar Sekarang</a>
        <button class="mobile-menu-button" type="button" data-mobile-menu-button>Menu</button>

        <nav class="mobile-menu-wrap hidden" data-mobile-menu aria-label="Navigasi mobile">
            <ul class="mobile-menu">
                @forelse ($headerMenuItems as $item)
                    @include('partials.site-menu-item', ['item' => $item])
                @empty
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('about.profile') }}">Tentang</a></li>
                    <li><a href="{{ route('academics.index') }}">Akademik</a></li>
                    <li><a href="{{ route('news.index') }}">Berita</a></li>
                    <li><a href="{{ route('contact.create') }}">Kontak</a></li>
                @endforelse
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <span class="logo-icon">NU</span>
                <h2>{{ setting('site_name', 'Universitas Nahdlatul Ulama') }}</h2>
                <p>{{ setting('meta_description', 'Website resmi Universitas Nahdlatul Ulama.') }}</p>
                <div class="footer-socials">
                    <a href="{{ setting('social_facebook', '#') }}" class="social-btn">f</a>
                    <a href="{{ setting('social_instagram', '#') }}" class="social-btn">ig</a>
                    <a href="{{ setting('social_youtube', '#') }}" class="social-btn">yt</a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Tentang</h4>
                <ul>
                    @forelse ($footerMenuItems as $item)
                        <li><a href="{{ $item->resolvedUrl() }}" target="{{ $item->target }}" @if ($item->target === '_blank') rel="noopener noreferrer" @endif>{{ $item->label }}</a></li>
                    @empty
                        <li><a href="{{ route('about.profile') }}">Profil Universitas</a></li>
                        <li><a href="{{ route('about.vision-mission') }}">Visi & Misi</a></li>
                        <li><a href="{{ route('about.history') }}">Sejarah</a></li>
                        <li><a href="{{ route('about.structure') }}">Struktur Organisasi</a></li>
                    @endforelse
                </ul>
            </div>
            <div class="footer-col">
                <h4>Akademik</h4>
                <ul>
                    <li><a href="{{ route('academics.faculties') }}">Program Studi</a></li>
                    <li><a href="{{ route('academics.faculties') }}">Fakultas</a></li>
                    <li><a href="{{ route('academics.calendar') }}">Kalender Akademik</a></li>
                    <li><a href="{{ route('academics.lecturers') }}">Direktori Dosen</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Newsletter</h4>
                <p>Dapatkan info terbaru langsung di emailmu.</p>
                <form class="footer-newsletter">
                    <input type="email" placeholder="emailmu@contoh.com">
                    <button type="button">OK</button>
                </form>
                <div class="footer-contact">
                    <p>{{ setting('site_address', '-') }}</p>
                    <p>{{ setting('site_phone', '-') }}</p>
                    <p>{{ setting('site_email', '-') }}</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ now()->year }} {{ setting('site_name', 'Universitas Nahdlatul Ulama') }}. Hak cipta dilindungi.</p>
            <div class="footer-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
            </div>
        </div>
    </footer>
</body>
</html>
