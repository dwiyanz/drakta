@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>Pembayaran Berhasil 🎉</h1>
    <p>Terima kasih telah melakukan pembayaran.</p>
    <a href="{{ route('product.index') }}" class="btn btn-primary">Kembali ke Home</a>
</div>
@endsection
