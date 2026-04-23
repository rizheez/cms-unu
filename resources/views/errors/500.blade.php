@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 500,
        'title' => 'Terjadi gangguan di sisi server.',
        'description' => 'Kami sedang menangani kendala teknis ini agar layanan kampus kembali normal secepatnya.',
        'primaryAction' => [
            'label' => 'Kembali ke beranda',
            'url' => route('home'),
        ],
        'secondaryAction' => [
            'label' => 'Buka kontak',
            'url' => route('contact.create'),
        ],
        'hints' => [
            'Coba muat ulang halaman beberapa saat lagi.',
            'Gunakan halaman lain terlebih dahulu jika kebutuhan Anda mendesak.',
            'Laporkan kendala ini bila muncul terus-menerus pada halaman yang sama.',
        ],
    ])
@endsection
