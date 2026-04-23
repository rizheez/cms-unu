@extends('layouts.app')

@section('content')
    @php
        $siteName = setting('site_name', 'Universitas Nahdlatul Ulama');
        $siteAddress = trim((string) setting('site_address', '-'));
        $sitePhone = setting('site_phone', '-');
        $siteEmail = setting('site_email', '-');
        $siteLatitude = trim((string) setting('site_latitude', ''));
        $siteLongitude = trim((string) setting('site_longitude', ''));
        $hasCoordinates = is_numeric($siteLatitude) && is_numeric($siteLongitude);
        $mapsQuery = $hasCoordinates ? "{$siteLatitude},{$siteLongitude}" : $siteAddress;
        $googleMapsUrl = trim((string) setting('google_maps_url', ''));
        $mapsUrl = $googleMapsUrl !== '' ? $googleMapsUrl : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapsQuery);
        $mapsEmbedUrl = $googleMapsUrl !== '' ? $googleMapsUrl : 'https://www.google.com/maps?q=' . rawurlencode($mapsQuery) . '&output=embed';
    @endphp

    <x-page-hero title="Kontak" subtitle="Sampaikan pertanyaan dan kebutuhan Anda." />
    <section class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-2">
        <form method="post" action="{{ route('contact.store') }}" class="rounded-lg border border-black/10 bg-white p-8">
            @csrf
            @if (session('status'))
                <p class="mb-6 rounded-lg bg-[#d8f7f2] p-4 font-semibold">{{ session('status') }}</p>
            @endif
            <div class="grid gap-5">
                <input class="border-b border-black/20 p-3" name="name" placeholder="Nama" value="{{ old('name') }}" required>
                <input class="border-b border-black/20 p-3" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
                <input class="border-b border-black/20 p-3" name="phone" placeholder="Telepon" value="{{ old('phone') }}">
                <input class="border-b border-black/20 p-3" name="subject" placeholder="Subjek" value="{{ old('subject') }}" required>
                <textarea class="border-b border-black/20 p-3" name="message" placeholder="Pesan" rows="6" required>{{ old('message') }}</textarea>
                <button class="rounded-full bg-[#ffc928] px-6 py-3 font-bold text-[#005f69]">Kirim Pesan</button>
            </div>
        </form>
        <aside class="rounded-lg bg-[#005f69] p-8 text-white">
            <h2 class="font-display text-3xl font-bold">{{ $siteName }}</h2>
            <p class="mt-4 text-white/75">{{ $siteAddress }}</p>
            <p class="mt-4 text-white/75">{{ $sitePhone }}</p>
            <p class="mt-2 text-white/75">{{ $siteEmail }}</p>
            @if ($mapsQuery !== '' && $mapsQuery !== '-')
                <iframe
                    class="mt-8 h-[400px] w-full rounded-lg border-0"
                    src="{{ $mapsEmbedUrl }}"
                    title="Peta lokasi {{ $siteName }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            @endif
        </aside>
    </section>
@endsection
