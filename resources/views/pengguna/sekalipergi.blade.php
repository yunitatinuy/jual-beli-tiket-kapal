@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
    @endphp
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
                                Carbon::parse($route->Tanggal)->format('Y-m-d');

                            if (!isset($groupedRoutes[$key])) {
                                $groupedRoutes[$key] = [
                                    'asal' => $route->pelabuhanAsal->Nama_Pelabuhan,
                                    'tujuan' => $route->pelabuhanTujuan->Nama_Pelabuhan,
                                    'tanggal' => Carbon::parse($route->Tanggal)->format('d-m-Y'),
                                    'kapal' => [],
                                ];
                            }

                            if (!isset($groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal])) {
                                $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal] = [
                                    'nama_kapal' => $route->kapal->Nama_Kapal,
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

                            $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal]['perjalanan'][] = [
                                'jam' => Carbon::parse($route->Jam)->format('H:i'),
                                'tiket' => $route->tiket,
                            ];

                            foreach ($route->tiket as $tiket) {
                                $harga = number_format($tiket->harga->Harga, 0, ',', '.') . '.000';

                                if ($tiket->harga->Kelas == 'VIP') {
                                    if ($tiket->harga->Tipe_Penumpang == 'dewasa' && $tiket->harga->Harga > 0) {
                                        $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal]['kelas']['VIP'][
                                            'dewasa'
                                        ][] = $harga;
                                    } elseif ($tiket->harga->Tipe_Penumpang == 'anak' && $tiket->harga->Harga > 0) {
                                        $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal]['kelas']['VIP'][
                                            'anak'
                                        ][] = $harga;
                                    }
                                } elseif ($tiket->harga->Kelas == 'Ekonomi') {
                                    if ($tiket->harga->Tipe_Penumpang == 'dewasa' && $tiket->harga->Harga > 0) {
                                        $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal]['kelas']['Ekonomi'][
                                            'dewasa'
                                        ][] = $harga;
                                    } elseif ($tiket->harga->Tipe_Penumpang == 'anak' && $tiket->harga->Harga > 0) {
                                        $groupedRoutes[$key]['kapal'][$route->kapal->Nama_Kapal]['kelas']['Ekonomi'][
                                            'anak'
                                        ][] = $harga;
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
                                    <span class="px-2 text-sm font-medium"><img src="/img/icons/arrow.png"
                                            class="w-4"></span>
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
                                @foreach ($groupedRoute['kapal'] as $kapal)
                                    @foreach ($kapal['perjalanan'] as $perjalanan)
                                        <div class="container mt-4 booking">
                                            <form action="{{ route('pesantiket') }}" method="GET">
                                                <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                                                    style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                                                    <div class="container flex flex-row justify-around">
                                                        <img src="/img/icons/ships.png" class="w-12 h-12">
                                                        <div class="flex flex-row">
                                                            <ul>
                                                                <li><strong>Nama Kapal</strong></li>
                                                                <li>{{ $kapal['nama_kapal'] }}</li>
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
                                                                <li><strong>Kelas VIP</strong></li>
                                                                @if (count($kapal['kelas']['VIP']['dewasa']) > 0)
                                                                    <li>Dewasa :
                                                                        @foreach ($kapal['kelas']['VIP']['dewasa'] as $harga_vip_dewasa)
                                                                            Rp{{ $harga_vip_dewasa }}@if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </li>
                                                                @endif
                                                                @if (count($kapal['kelas']['VIP']['anak']) > 0)
                                                                    <li>Anak :
                                                                        @foreach ($kapal['kelas']['VIP']['anak'] as $harga_vip_anak)
                                                                            Rp{{ $harga_vip_anak }}@if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                        <div>
                                                            <input type="hidden" name="asal" value="{{ $groupedRoute['asal'] }}">
                                                            <input type="hidden" name="tujuan" value="{{ $groupedRoute['tujuan'] }}">
                                                            <input type="hidden" name="tanggal" value="{{ $groupedRoute['tanggal'] }}">
                                                            <input type="hidden" name="kapal" value="{{ $kapal['nama_kapal'] }}">
                                                            <input type="hidden" name="jam" value="{{ $perjalanan['jam'] }}">
                                                            <input type="hidden" name="harga_vip_dewasa" value="{{ implode(',', $kapal['kelas']['VIP']['dewasa']) }}">
                                                            <input type="hidden" name="harga_vip_anak" value="{{ implode(',', $kapal['kelas']['VIP']['anak']) }}">
                                                            <button type="submit"
                                                                class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">
                                                                Pesan Tiket
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <br>
                                            <div class="border border-b-black"></div><br>
                                        </div>
                                    @endforeach

                                    @if (count($kapal['kelas']['Ekonomi']['dewasa']) > 0 || count($kapal['kelas']['Ekonomi']['anak']) > 0)
                                        <div class="container mt-4 booking">
                                            <form action="{{ route('pesantiket') }}" method="GET">
                                                <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                                                    style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                                                    <div class="container flex flex-row justify-around">
                                                        <img src="/img/icons/ships.png" class="w-12 h-12">
                                                        <div class="flex flex-row">
                                                            <ul>
                                                                <li><strong>Nama Kapal</strong></li>
                                                                <li>{{ $kapal['nama_kapal'] }}</li>
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
                                                                <li><strong>Kelas Ekonomi</strong></li>
                                                                @if (count($kapal['kelas']['Ekonomi']['dewasa']) > 0)
                                                                    <li>Dewasa :
                                                                        @foreach ($kapal['kelas']['Ekonomi']['dewasa'] as $harga_ekonomi_dewasa)
                                                                            Rp{{ $harga_ekonomi_dewasa }}@if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </li>
                                                                @endif
                                                                @if (count($kapal['kelas']['Ekonomi']['anak']) > 0)
                                                                    <li>Anak :
                                                                        @foreach ($kapal['kelas']['Ekonomi']['anak'] as $harga_ekonomi_anak)
                                                                            Rp{{ $harga_ekonomi_anak }}@if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                        <div>
                                                            <input type="hidden" name="asal" value="{{ $groupedRoute['asal'] }}">
                                                            <input type="hidden" name="tujuan" value="{{ $groupedRoute['tujuan'] }}">
                                                            <input type="hidden" name="tanggal" value="{{ $groupedRoute['tanggal'] }}">
                                                            <input type="hidden" name="kapal" value="{{ $kapal['nama_kapal'] }}">
                                                            <input type="hidden" name="jam" value="{{ $perjalanan['jam'] }}">
                                                            <input type="hidden" name="harga_ekonomi_dewasa" value="{{ implode(',', $kapal['kelas']['Ekonomi']['dewasa']) }}">
                                                            <input type="hidden" name="harga_ekonomi_anak" value="{{ implode(',', $kapal['kelas']['Ekonomi']['anak']) }}">
                                                            <button type="submit"
                                                                class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">
                                                                Pesan Tiket
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <br>
                                            <div class="border border-b-black"></div><br>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </center>
    </div>
@endsection
