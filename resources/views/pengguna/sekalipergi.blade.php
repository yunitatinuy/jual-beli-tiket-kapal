@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="w-full h-full min-h-screen bg-fixed bg-no-repeat bg-cover bg-bground1">
        <center>
            @if (session('error') || $errors->any())
                <div class="w-10/12 p-4 mt-10 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            @if (isset($searchResults))
                @if (isset($searchResults['jadwal']))
                    @php
                        $pertama = $searchResults['jadwal']->first()['rute'];
                    @endphp
                    <div class="w-10/12">
                        <div class="flex flex-wrap justify-between">
                            <div class="flex justify-start flex-auto mx-5 mt-10">
                                <img src="/img/icons/calendar.png" class="h-5 pr-2 w-7 ms-2">
                                <span class="px-2 text-sm font-medium text-white rounded-sm bg-neutral-400">
                                    {{ Carbon::parse($pertama->Tanggal)->format('d-m-Y') }}
                                </span>
                            </div>
                            <div class="flex justify-end flex-auto mx-5 mt-10">
                                <span class="px-2 text-sm font-medium text-white rounded-sm bg-neutral-400">
                                    {{ $dewasa }} Dewasa, {{ $anak }} Anak
                                </span>
                            </div>
                        </div><br>

                        <div class="shadow-sm backdrop-blur-sm rounded-xl pb-6">
                            @foreach ($searchResults['jadwal'] as $item)
                                @php $rute = $item['rute']; @endphp

                                @foreach ($item['offers'] as $kelas => $tipeHarga)
                                    @php
                                        // Kelas hanya bisa dipesan jika tiket untuk setiap tipe penumpang yang diminta tersedia
                                        $kurang = [];
                                        $total = 0;
                                        foreach (['dewasa' => $dewasa, 'anak' => $anak] as $tipe => $jumlah) {
                                            if ($jumlah <= 0) continue;
                                            if (!isset($tipeHarga[$tipe])) {
                                                $kurang[] = "tidak ada tiket {$tipe}";
                                            } elseif ($tipeHarga[$tipe]['sisa'] < $jumlah) {
                                                $kurang[] = "sisa tiket {$tipe} {$tipeHarga[$tipe]['sisa']}";
                                            } else {
                                                $total += $tipeHarga[$tipe]['harga'] * $jumlah;
                                            }
                                        }
                                        $bisaDipesan = empty($kurang);
                                    @endphp

                                    <div class="container mt-4 booking">
                                        <form action="{{ route('pesantiket') }}" method="GET">
                                            <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                                                style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                                                <div class="container flex flex-row flex-wrap items-center justify-around gap-4">
                                                    <img src="/img/icons/ships.png" class="w-12 h-12">
                                                    <ul>
                                                        <li><strong>Nama Kapal</strong></li>
                                                        <li>{{ $rute->kapal->Nama_Kapal ?? '-' }}</li>
                                                    </ul>
                                                    <ul>
                                                        <li><strong>Waktu</strong></li>
                                                        <li>{{ Carbon::parse($rute->Jam)->format('H:i') }}</li>
                                                    </ul>
                                                    <ul>
                                                        <li><strong>Rute</strong></li>
                                                        <li>{{ $rute->pelabuhanAsal->Nama_Pelabuhan ?? '-' }} ke {{ $rute->pelabuhanTujuan->Nama_Pelabuhan ?? '-' }}</li>
                                                    </ul>
                                                    <ul>
                                                        <li><strong>Kelas {{ $kelas }}</strong></li>
                                                        @foreach (['dewasa' => 'Dewasa', 'anak' => 'Anak'] as $tipe => $label)
                                                            @isset($tipeHarga[$tipe])
                                                                <li>{{ $label }}: {{ rupiah($tipeHarga[$tipe]['harga']) }}
                                                                    <span class="text-xs text-gray-600">(sisa {{ $tipeHarga[$tipe]['sisa'] }})</span>
                                                                </li>
                                                            @endisset
                                                        @endforeach
                                                    </ul>
                                                    <ul>
                                                        <li><strong>Total</strong></li>
                                                        <li>{{ $bisaDipesan ? rupiah($total) : '-' }}</li>
                                                    </ul>
                                                    <div>
                                                        <input type="hidden" name="rute" value="{{ $rute->ID_Rute }}">
                                                        <input type="hidden" name="kelas" value="{{ $kelas }}">
                                                        <input type="hidden" name="dewasa" value="{{ $dewasa }}">
                                                        <input type="hidden" name="anak" value="{{ $anak }}">
                                                        @if ($bisaDipesan)
                                                            <button type="submit"
                                                                class="font-sans inline-flex items-center px-3 py-2 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">
                                                                Pesan Tiket
                                                            </button>
                                                        @else
                                                            <span class="text-sm text-red-700">Tidak tersedia ({{ implode(', ', $kurang) }})</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <br>
                                        <div class="border border-b-black"></div><br>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-screen">
                        <div class="max-w-xl p-8 bg-[#DEC3B4] rounded-lg shadow-lg backdrop-filter backdrop-blur-md">
                            <p class="mb-4 text-lg font-semibold">{{ $searchResults['message'] }}</p>
                            <a href="/dashboard_pengguna"
                                class="inline-block px-6 py-3 text-white transition duration-150 ease-in-out bg-[#f9a273] hover:bg-[#eb9f77] rounded-lg focus:ring-4 focus:ring-blue-300 focus:outline-none">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="flex items-center justify-center h-screen">
                    <div class="max-w-xl p-8 bg-[#DEC3B4] rounded-lg shadow-lg">
                        <p class="mb-4 text-lg font-semibold">Silakan cari jadwal kapal dari dashboard.</p>
                        <a href="/dashboard_pengguna"
                            class="inline-block px-6 py-3 text-white bg-[#f9a273] hover:bg-[#eb9f77] rounded-lg">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </center>
    </div>
@endsection
