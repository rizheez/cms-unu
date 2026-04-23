@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 419,
        'title' => 'Sesi Anda sudah berakhir.',
        'description' => 'Halaman ini biasanya muncul saat formulir dikirim setelah sesi terlalu lama tidak aktif.',
        'primaryAction' => [
            'label' => 'Muat ulang halaman',
            'url' => url()->current(),
        ],
        'secondaryAction' => [
            'label' => 'Kembali ke kontak',
            'url' => route('contact.create'),
        ],
        'hints' => [
            'Refresh halaman terlebih dahulu sebelum mengirim formulir lagi.',
            'Hindari membuka formulir terlalu lama di tab yang tidak aktif.',
            'Jika masih berulang, coba buka ulang halaman dari menu situs.',
        ],
    ])
@endsection
