@extends('layout.main')

@section('container')
<main>
    <div class="container1 h-auto w-auto bg-slate-600 mb-4">
        <div class="gap-11 columns-2 py-3 px-2">
            <div class="justify-center mx-auto"><a href="">Pembayaran</a></div>
            <a href="">a</a>
        </div>
    </div>
    <div class="flex mb-4">
        <div class="w-full md:w-1/2 px-1 py-2 bg-slate-500">
            Transaksi
            <div class="px-2 py-2 bg-slate-50">Pilih</div>
        </div>
        <div class="w-full md:w-1/2 md:ml-2 px-1 py-2 bg-red-500 mt-4 md:mt-0">
            Pesanan
        </div>
    </div>

    <div class="flex mb-4">
        <div class="w-full md:w-1/2 px-1 py-2 bg-slate-500">
            Informasi
            <div class="px-2 py-2 bg-slate-50">Nomor</div>
        </div>
        <div class="w-full md:w-1/2 md:ml-2 px-1 py-2 bg-red-500 mt-4 md:mt-0">
        Total
        </div>
    </div>

    Bayar
@endsection