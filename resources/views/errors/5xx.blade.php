@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => $exception->getStatusCode(),
        'title' => 'Terjadi gangguan pada layanan.',
        'description' => 'Sistem sedang mengalami kendala sementara. Silakan coba kembali beberapa saat lagi.',
    ])
@endsection
