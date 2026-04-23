@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => 403,
        'title' => 'Akses ke halaman ini dibatasi.',
        'description' => 'Halaman ini membutuhkan izin tertentu, jadi belum bisa dibuka dari akun atau sesi saat ini.',
        'primaryAction' => [
            'label' => 'Kembali ke beranda',
            'url' => route('home'),
        ],
        'secondaryAction' => [
            'label' => 'Hubungi kami',
            'url' => route('contact.create'),
        ],
        'hints' => [
            'Pastikan Anda sudah masuk dengan akun yang benar bila halaman ini bersifat khusus.',
            'Coba buka kembali dari navigasi resmi situs, bukan dari tautan lama.',
            'Hubungi admin kampus jika Anda merasa seharusnya memiliki akses.',
        ],
    ])
@endsection
