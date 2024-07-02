<div>
    <!-- CONTENT -->

    <div class="ms-10 mt-10 me-10 flex flex-col flex-grow">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/dashboard.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Dashboard</span>
        </span>

        <div class="container">
            <div class="flex flex-wrap">

                <!-- Pengguna Baru -->
                <div class="p-4 max-w-sm">
                    <div class="flex rounded-lg h-full w-80 bg-sky-400 p-6 flex-col">
                        <div class="flex items-center">
                            <img src="/img/icons/peng-baru.png" class="flex-shrink-0 w-6 h-5 text-gray-500">
                            <span class="font-inter font-semibold mx-3 text-xl">Total Pengguna</span>
                        </div>
                        <div class="flex flex-col justify-between flex-grow">
                            <p class="leading-relaxed text-white text-8xl">
                                <!-- data dari data base nantinya -->
                                 {{ $usercount }}
                            </p>
                            <a href="/admin/pengguna" class="font-inter text-black hover:text-white inline-flex items-center">Selengkapnya
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Tiket -->
                <div class="p-4 max-w-sm">
                    <div class="flex rounded-lg h-full w-80 bg-sky-400 p-6 flex-col">
                        <div class="flex items-center">
                            <img src="/img/icons/ticket.png" class="flex-shrink-0 w-8 h-8 text-gray-500">
                            <span class="font-inter font-semibold mx-3 text-xl">Total Tiket</span>
                        </div>
                        <div class="flex flex-col justify-between flex-grow">
                            <p class="leading-relaxed text-white text-8xl">
                                <!-- data dari data base nantinya --> 
                                 {{ $tiketcount }}
                            </p>
                            <a href="/admin/tiket" class="font-inter text-black hover:text-white inline-flex items-center">Selengkapnya
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Rute -->
                <div class="p-4 max-w-sm">
                    <div class="flex rounded-lg h-full w-80 bg-sky-400 p-6 flex-col">
                        <div class="flex items-center">
                            <img src="/img/icons/route.png" class="flex-shrink-0 w-6 h-6 text-gray-500">
                            <span class="font-inter font-semibold mx-3 text-xl">Total Rute Perjalanan</span>
                        </div>
                        <div class="flex flex-col justify-between flex-grow">
                            <p class="leading-relaxed text-white text-8xl">
                                <!-- data dari data base nantinya --> {{ $rutecount}}
                            </p>
                            <a href="#" class="font-inter text-black hover:text-white inline-flex items-center">Selengkapnya
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Kapal -->
    <div class=" ms-10 mt-5 flex flex-col font-inter mb-10">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/ships.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Kapal</span>
        </span>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm text-surface">
                        <thead class="bg-[#ff9853be] border-b border-neutral-200 font-medium">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Kode Kapal</th>
                                <th scope="col" class="px-6 py-4">Nama Kapal</th>
                                <th scope="col" class="px-6 py-4">Kelas</th>
                                <th scope="col" class="px-6 py-4">Jumlah Kursi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataKapal as $kapal)
                            <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                                <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$kapal->ID_Kapal}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$kapal->Nama_Kapal}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$kapal->Kelas}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$kapal->Jumlah_Kursi}}</td>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="font-inter text-base pt-2">Belum Ada Data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pelabuhan -->
    <div class="ms-10 mt-5 flex flex-col font-inter mb-10">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/harbor.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Pelabuhan</span>
        </span>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm text-surface">
                        <thead class="bg-[#ff9853be] border-b border-neutral-200 font-medium">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Kode Pelabuhan</th>
                                <th scope="col" class="px-6 py-4">Nama Pelabuhan</th>
                                <th scope="col" class="px-6 py-4">Nama Kota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataPelabuhan as $pelabuhan)
                            <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                                <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loop->iteration }}</td>

                                <td class="whitespace-nowrap px-6 py-4">{{$pelabuhan->ID_Pelabuhan}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$pelabuhan->Nama_Pelabuhan}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$pelabuhan->Nama_Kota}}</td>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="font-inter text-base pt-2">Belum Ada Data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Rute -->
    <div class="ms-10 mt-5 flex flex-col font-inter mb-10">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/route.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Rute Perjalanan</span>
        </span>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm text-surface">
                        <thead class="bg-[#ff9853be] border-b border-neutral-200 font-medium">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Kode Rute</th>
                                <th scope="col" class="px-6 py-4">Nama Kapal</th>
                                <th scope="col" class="px-6 py-4">Pelabuhan Asal</th>
                                <th scope="col" class="px-6 py-4">Pelabuhan Tujuan</th>
                                <th scope="col" class="px-6 py-4">Tanggal Berangkat</th>
                                <th scope="col" class="px-6 py-4">Waktu Berangkat</th>
                            </tr>
                        </thead>
                        @php
                        use Carbon\Carbon;
                        @endphp
                        <tbody>
                            @forelse ($dataRute as $rute)
                            <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                                <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$rute->ID_Rute}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$rute->kapal->Nama_Kapal }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$rute->pelabuhanAsal->Nama_Pelabuhan}}, {{$rute->pelabuhanAsal->Nama_Kota }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$rute->pelabuhanTujuan->Nama_Pelabuhan }}, {{$rute->pelabuhanTujuan->Nama_Kota }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{Carbon::parse($rute->Tanggal)->format('d-m-Y')}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{Carbon::parse($rute->Jam)->format('H:i')}}</td>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="font-inter text-base pt-2">Belum Ada Data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tiket -->
    <div class="ms-10 mt-5 flex flex-col font-inter mb-10">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/ticket.png" class="flex-shrink-0 w-9 h-9 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Tiket</span>
        </span>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm text-surface">
                        <thead class="bg-[#ff9853be] border-b border-neutral-200 font-medium">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                <th scope="col" class="px-6 py-4">Kode Tiket</th>
                                <th scope="col" class="px-6 py-4">Rute Perjalanan</th>
                                <th scope="col" class="px-6 py-4">Tipe Penumpang</th>
                                <th scope="col" class="px-6 py-4">Kelas</th>
                                <th scope="col" class="px-6 py-4">Jumlah Tiket</th>
                                <th scope="col" class="px-6 py-4">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataTiket as $tiket)
                            <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                                <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loop->iteration }}</td>

                                <td class="whitespace-nowrap px-6 py-4">{{$tiket->ID_Tiket}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$tiket->rute->pelabuhanAsal->Nama_Pelabuhan }} - {{ $tiket->rute->pelabuhanTujuan->Nama_Pelabuhan }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$tiket->harga->Tipe_Penumpang}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$tiket->harga->Kelas}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{$tiket->Jumlah_Tiket}}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{number_format ($tiket->harga->Harga, 3) }}</td>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="font-inter text-base pt-2">Belum Ada Data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>