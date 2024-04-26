@extends('layout.main2')

@section('container')
<main>
<div class="container max-w-screen-xl w-full justify-center">
    <div class="m-10 flex justify-center">
        <span class="text-3xl font-marko uppercase">Anang Ferry</span>
    </div>
    <div>
    <table class="flex justify-center font-jsMath font-bold">
        <tbody>
            <tr>
                <td rowspan="10" class="pe-4"><img src="/img/logo.png" alt="logo" width="110"></td>
                <td>Wujudkan</td>
            </tr>
            <tr>
                <td>Perjalanan</td>
            </tr>
            <tr>
                <td>Anda</td>
            </tr>
            <tr>
                <td>Bersama</td>
            </tr>
            <tr>
                <td>Kami</td>
            </tr>
        </thead>
    </table>
    <br>
    </div>

    <div class="flex justify-center">
        <a href="/dashboard_pengguna" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
        Cari Tiket
        <img src="/img/cari.png" alt="" class="w-4 ms-2">
        </a>
    </div>
</div>
</main>
@endsection