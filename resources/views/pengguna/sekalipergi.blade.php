@extends('layout.main')

@section('container')
@php
use Carbon\Carbon;
@endphp
<main>
    <div class="w-full h-full bg-fixed bg-no-repeat bg-cover bg-bground1">
        <center>
            @if (isset($searchResults))
            @if (isset($searchResults['routes']))
            @php
            $groupedRoutes = [];
            foreach ($searchResults['routes'] as $route) {
            $key =
            $route->pelabuhanAsal->Nama_Pelabuhan .
            '-' .
            $route->pelabuhanTujuan->Nama_Pelabuhan .
            '-' .
            Carbon::parse($route->Tanggal)->format('Y-m-d') .
            '-' .
            $route->kapal->Nama_Kapal;

            if (!isset($groupedRoutes[$key])) {
            $groupedRoutes[$key] = [
            'asal' => $route->pelabuhanAsal->Nama_Pelabuhan,
            'tujuan' => $route->pelabuhanTujuan->Nama_Pelabuhan,
            'tanggal' => Carbon::parse($route->Tanggal)->format('d-m-Y'),
            'kapal' => $route->kapal->Nama_Kapal,
            'perjalanan' => [],
            'kelas' => [
            'VIP' => [
            'dewasa' => [],
            'anak' => [],
            ],
            'Ekonomi' => [
            'dewasa' => [],
            'anak' => [],
            ],
            ],
            ];
            }

            $groupedRoutes[$key]['perjalanan'][] = [
            'jam' => Carbon::parse($route->Jam)->format('H:i'),
            'tiket' => $route->tiket,
            ];

            foreach ($route->tiket as $tiket) {
            $harga = number_format($tiket->harga->Harga, 0, ',', '.') . '.000';

            if ($tiket->harga->Kelas == 'VIP') {
            if ($tiket->harga->Tipe_Penumpang == 'dewasa') {
            $groupedRoutes[$key]['kelas']['VIP']['dewasa'][] = $harga;
            } elseif ($tiket->harga->Tipe_Penumpang == 'anak') {
            $groupedRoutes[$key]['kelas']['VIP']['anak'][] = $harga;
            }
            } elseif ($tiket->harga->Kelas == 'Ekonomi') {
            if ($tiket->harga->Tipe_Penumpang == 'dewasa') {
            $groupedRoutes[$key]['kelas']['Ekonomi']['dewasa'][] = $harga;
            } elseif ($tiket->harga->Tipe_Penumpang == 'anak') {
            $groupedRoutes[$key]['kelas']['Ekonomi']['anak'][] = $harga;
            }
            }
            }
            }
            @endphp

            @foreach ($groupedRoutes as $groupedRoute)
            <div class="w-10/12">
                <div class="flex flex-wrap justify-between">
                    <div class="flex justify-start flex-auto mx-5 mt-10">
                        <img src="/img/icons/harbor.png" class="h-5 pr-2 w-7 ms-2">
                        <span class="px-2 text-sm font-medium text-white rounded-sm bg-neutral-400">
                            <ul>
                                <li>{{ $groupedRoute['asal'] }}</li>
                            </ul>
                        </span>
                        <span class="px-2 text-sm font-medium"><img src="/img/icons/arrow.png" class="w-4"></span>
                        <span class="px-2 text-sm font-medium text-white rounded-sm bg-neutral-400">
                            <ul>
                                <li>{{ $groupedRoute['tujuan'] }}</li>
                            </ul>
                        </span>
                    </div>
                    <div class="flex justify-end flex-auto mx-5 mt-10">
                        <img src="/img/icons/calendar.png" class="h-5 pr-2 w-7 ms-2">
                        <span class="px-2 text-sm font-medium text-white rounded-sm bg-neutral-400">
                            <ul>
                                <li>{{ $groupedRoute['tanggal'] }}</li>
                            </ul>
                        </span>
                    </div>
                </div><br>

                <div class="shadow-sm h-svh backdrop-blur-sm rounded-xl">
                    @foreach ($groupedRoute['perjalanan'] as $perjalanan)
                    <div class="container mt-4 booking">
                        <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]" style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                            <div class="container flex flex-row justify-around">
                                <img src="/img/icons/ships.png" class="w-12">
                                <div class="flex flex-row">
                                    <ul>
                                        <li><strong>Nama Kapal</strong></li>
                                        <li>{{ $groupedRoute['kapal'] }}</li>
                                    </ul>
                                </div>
                                <div class="justify-items-end">
                                    <ul>
                                        <li><strong>Waktu</strong></li>
                                        <li>{{ $perjalanan['jam'] }}</li>
                                    </ul>
                                </div>
                                <div class="justify-items-end">
                                    <ul>
                                        <li><strong>Rute</strong></li>
                                        <li>{{ $groupedRoute['asal'] }} ke {{ $groupedRoute['tujuan'] }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex flex-col">
                                    <ul>
                                        @if (!empty($groupedRoute['kelas']['VIP']['dewasa']))
                                        <li><strong>Kelas VIP</strong></li>
                                        <li>Dewasa :
                                            @foreach ($groupedRoute['kelas']['VIP']['dewasa'] as $harga_vip_dewasa)
                                            Rp{{ $harga_vip_dewasa }}@if (!$loop->last)
                                            ,
                                            @endif
                                            @endforeach
                                        </li>
                                        @if (!empty($groupedRoute['kelas']['VIP']['anak']))
                                        <li>Anak :
                                            @foreach ($groupedRoute['kelas']['VIP']['anak'] as $harga_vip_anak)
                                            Rp{{ $harga_vip_anak }}@if (!$loop->last)
                                            ,
                                            @endif
                                            @endforeach
                                        </li>
                                        @endif
                                        @endif
                                    </ul>
                                </div>
                                <div>
                                    <a href="/login" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                        Pesan Tiket
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="border border-b-black"></div><br>
                    </div>
                    @endforeach
                    @if (!empty($groupedRoute['kelas']['Ekonomi']['dewasa']))
                    <div class="container mt-4 booking">
                        <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]" style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                            <div class="container flex flex-row justify-around">
                                <img src="/img/icons/ships.png" class="w-12">
                                <div class="flex flex-row">
                                    <ul>
                                        <li><strong>Nama Kapal</strong></li>
                                        <li>{{ $groupedRoute['kapal'] }}</li>
                                    </ul>
                                </div>
                                <div class="justify-items-end">
                                    <ul>
                                        <li><strong>Waktu</strong></li>
                                        <li>{{ $perjalanan['jam'] }}</li>
                                    </ul>
                                </div>
                                <div class="justify-items-end">
                                    <ul>
                                        <li><strong>Rute</strong></li>
                                        <li>{{ $groupedRoute['asal'] }} ke
                                            {{ $groupedRoute['tujuan'] }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex flex-col">
                                    <ul>
                                        @if (!empty($groupedRoute['kelas']['Ekonomi']['dewasa']))
                                        <li><strong>Kelas Ekonomi</strong></li>
                                        <li>Dewasa :
                                            @foreach ($groupedRoute['kelas']['Ekonomi']['dewasa'] as $harga_eko_dewasa)
                                            Rp{{ $harga_eko_dewasa }}@if (!$loop->last)
                                            ,
                                            @endif
                                            @endforeach
                                        </li>
                                        @if (!empty($groupedRoute['kelas']['Ekonomi']['anak']))
                                        <li>Anak :
                                            @foreach ($groupedRoute['kelas']['Ekonomi']['anak'] as $harga_eko_anak)
                                            Rp{{ $harga_eko_anak }}@if (!$loop->last)
                                            ,
                                            @endif
                                            @endforeach
                                        </li>
                                        @endif
                                        @endif
                                    </ul>
                                </div>
                                <div>
                                    <a href="/login" class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                        Pesan Tiket
                                    </a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="border border-b-black"></div><br>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            <div class="flex items-center justify-center h-screen">
                <div class="max-w-xl p-8 bg-[#DEC3B4] rounded-lg shadow-lg backdrop-filter backdrop-blur-md">
                    <p class="mb-4 text-lg font-semibold">{{ $searchResults['message'] }}</p>
                    <a href="/dashboard_pengguna" class="inline-block px-6 py-3 text-white transition duration-150 ease-in-out bg-[#f9a273] hover:bg-[#eb9f77] rounded-lg focus:ring-4 focus:ring-blue-300 focus:outline-none">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
            @endif
            @endif
        </center>
    </div>
</main>
@endsection