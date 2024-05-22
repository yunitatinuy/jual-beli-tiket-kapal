@extends('layout.main2')

@section('container')
<main>
<div class="container my-40 w-1/4 p-3 justify-center rounded-3xl backdrop-blur-sm shadow-5-strong">
    <div class="mb-4 flex justify-center">
        <span class="text-3xl font-marko uppercase">Anang Ferry</span>
    </div>

    <div class="flex flex-row">
        <div class="justify-items-center">
        <img src="img/logo3.png" alt="logo" width="160">
        </div>

        <div class="font-jsMath text-2xl">
        <span>Wujudkan</span><br>
        <span>Perjalanan</span><br>
        <span>Anda</span><br>
        <span>Bersama</span><br>
        <span></span>Kami</span>
        </div>
    </div>
    <div class="flex justify-center mt-2">
        <a href="/dashboard_pengguna" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
        Cari Tiket
        <img src="/img/cari.png" alt="" class="w-4 ms-2">
        </a>
    </div>

</div>
</main>
@endsection