@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 404,
        'title' => 'Halaman yang Anda cari tidak ditemukan.',
        'description' => 'Tautan mungkin sudah berubah, dipindahkan, atau alamat yang dibuka belum tepat.',
        'primaryAction' => [
            'label' => 'Kembali ke beranda',
            'url' => route('home'),
        ],
        'secondaryAction' => [
            'label' => 'Jelajahi berita',
            'url' => route('news.index'),
        ],
        'hints' => [
            'Periksa lagi ejaan URL pada browser Anda.',
            'Gunakan fitur pencarian untuk menemukan halaman yang dibutuhkan.',
            'Buka menu utama untuk kembali ke bagian penting situs kampus.',
        ],
    ])
@endsection
