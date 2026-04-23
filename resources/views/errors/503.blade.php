@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 503,
        'title' => 'Layanan sedang dalam perawatan.',
        'description' => 'Beberapa bagian situs mungkin sementara tidak tersedia selama proses pemeliharaan berlangsung.',
        'primaryAction' => [
            'label' => 'Kembali ke beranda',
            'url' => route('home'),
        ],
        'secondaryAction' => [
            'label' => 'Lihat berita',
            'url' => route('news.index'),
        ],
        'hints' => [
            'Silakan coba kembali dalam beberapa menit.',
            'Perawatan dilakukan untuk menjaga stabilitas dan keamanan layanan.',
            'Gunakan kanal informasi resmi kampus untuk pembaruan terbaru.',
        ],
    ])
@endsection
