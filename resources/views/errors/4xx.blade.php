@extends('layouts.app')

@section('content')
    @include('errors.partials.page', [
        'status' => $exception->getStatusCode(),
        'title' => 'Permintaan tidak dapat diproses.',
        'description' => 'Ada kendala pada permintaan yang dikirim, sehingga halaman ini belum bisa ditampilkan.',
    ])
@endsection
