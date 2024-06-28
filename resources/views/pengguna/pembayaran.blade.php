@extends('layout.main')

@section('container')
<div class="container">
    <h2>Halaman Pembayaran</h2>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('pembayaran.process') }}" method="POST">
        @csrf

        <!-- Contoh detail pembayaran atau informasi pesanan -->
        <div class="form-group">
            <label for="nomor_pemesanan">Nomor Pemesanan</label>
            <input type="text" class="form-control" id="nomor_pemesanan" name="nomor_pemesanan" value="{{ $nomor_pemesanan }}" readonly>
        </div>

        <div class="form-group">
            <label for="total_pembayaran">Total Pembayaran</label>
            <input type="text" class="form-control" id="total_pembayaran" name="total_pembayaran" value="{{ $total_pembayaran }}" readonly>
        </div>

        <!-- Informasi metode pembayaran -->
        <div class="form-group">
            <label for="metode_pembayaran">Metode Pembayaran</label>
            <select class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                <option value="transfer_bank">Transfer Bank</option>
                <option value="kartu_kredit">Kartu Kredit</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
    </form>
</div>
@endsection