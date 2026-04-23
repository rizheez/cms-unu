@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 429,
        'title' => 'Permintaan terlalu sering dikirim.',
        'description' => 'Sistem menahan permintaan sementara agar layanan tetap stabil untuk semua pengunjung.',
        'primaryAction' => [
            'label' => 'Coba lagi nanti',
            'url' => route('home'),
        ],
        'secondaryAction' => [
            'label' => 'Hubungi kami',
            'url' => route('contact.create'),
        ],
        'hints' => [
            'Tunggu beberapa saat sebelum mengirim ulang formulir atau request.',
            'Hindari klik berulang pada tombol kirim atau muat ulang cepat-cepatan.',
            'Jika Anda membutuhkan bantuan segera, gunakan halaman kontak resmi.',
        ],
    ])
@endsection
