<!-- CONTENT -->
<div class="ms-10 mt-8 me-10 flex flex-col flex-grow">
    <span class="flex items-center p-2 text-gray-900 rounded-lg group">
        <img src="/img/icons/harbor.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Rute Perjalanan</span>
    </span>

    <!-- message berhasil tambah data -->
    <div class="container font-inter">
        <div class="flex flex-wrap">
            @if (session()->has('success'))
            <div class="p-4 text-sm text-green-800 rounded-lg bg-green-200 mt-3 animate-fade">
                {{session('success')}}
            </div>
            @endif
        </div>

        <div class="my-5">
            @if (session()->has('error'))
            <div class="p-4 text-sm bg-red-500 text-slate-800 rounded-lg mt-3">
                {{session('error')}}
            </div>
        </div>
        @endif

        <!-- message berhasil hapus dan update -->
        <div class="flex flex-wrap font-inter">
            @if (session()->has('message'))
            <div class="p-4 text-sm text-green-800 rounded-lg bg-green-200 animate-fade">
                {{ session('message') }}
            </div>
            @endif
        </div>

        <!-- Formulir -->

        <div class="container block max-w-2xl overflow-hidden bg-white border border-gray-200 rounded-lg shadow mt-5">
            <form wire:submit="store">
                <div class="bg-[#ff9853be] p-6">
                    <span class="font-inter font-semibold text-xl ">Formulir Rute Perjalanan</span>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="kapal" class="block mb-2 text-sm font-medium text-gray-900">Nama Kapal</label>
                        <select name="ID_Kapal" id="ID_Kapal" wire:model="selectedKapal" class="bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" selected>- Pilih Nama Kapal -</option>
                            @foreach ($kapalList as $kapal)
                            <option value="{{$kapal->ID_Kapal}}" class="text-gray-900">{{$kapal->Nama_Kapal}}</option>
                            @endforeach
                        </select>
                        @error('selectedKapal')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="pelabuhaAsal" class="block mb-2 text-sm font-medium text-gray-900">Pelabuhan Asal</label>
                        <select id="pelabuhanAsal" wire:model="selectedPelabuhanAsal" class="bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" selected>- Pilih Pelabuhan Asal -</option>
                            @foreach ($pelabuhanList as $pelabuhan)
                            <option value="{{$pelabuhan->ID_Pelabuhan}}">{{$pelabuhan->Nama_Pelabuhan}}, {{$pelabuhan->Nama_Kota}}</option>
                            @endforeach
                        </select>
                        @error('selectedPelabuhanAsal')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="pelabuhaTujuan" class="block mb-2 text-sm font-medium text-gray-900">Pelabuhan Tujuan</label>
                        <select id="pelabuhanTujuan" wire:model="selectedPelabuhanTujuan" class="bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" selected>- Pilih Pelabuhan Tujuan -</option>
                            @foreach ($pelabuhanList as $pelabuhan)
                            <option value="{{$pelabuhan->ID_Pelabuhan}}">{{$pelabuhan->Nama_Pelabuhan}}, {{$pelabuhan->Nama_Kota}}</option>
                            @endforeach
                        </select>
                        @error('selectedPelabuhanTujuan')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Keberangkatan</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input type="date" wire:model="tanggal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " placeholder="Pilih Tanggal">
                        </div>
                        @error('tanggal')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Keberangkatan:</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="time" wire:model="jam" id="time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " min="09:00" max="18:00" value="00:00" required />
                        </div>
                        @error('jam')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>


        </div><br>
        <div class="flex flex-wrap gap-3">
            @if ($updateData == false)
            <button class="inline-block rounded bg-sky-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-sky-600 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-sky-600 active:shadow-primary-2 motion-reduce:transition-none group" type="submit" value="submit" wire:click.prevent="store()">
                <span class="flex-1 whitespace-nowrap">Tambah Data</span>
            </button>
            @else
            <button class="inline-block rounded bg-sky-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-sky-600 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-sky-600 active:shadow-primary-2 motion-reduce:transition-none group" name="submit" wire:click.prevent="update()">
                <span class="flex-1 whitespace-nowrap">Update</span>
            </button>
            <button class="inline-block rounded bg-red-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-red-600 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-red-600 active:shadow-primary-2 motion-reduce:transition-none group" name="submit" wire:click.prevent="cancel()">
                <span class="flex-1 whitespace-nowrap">Batal</span>
            </button>
            @endif
        </div>
    </div>
    </form>
</div>


<div class="flex flex-wrap justify-between ">
    {{-- Search --}}
    <form class="flex flex-wrap max-w-xs ">
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live.debounce.500ms="search" type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search..." required />
        </div>
    </form>

    {{-- Pagination --}}
    <div class="mt-2">
        {{ $dataRute->links('vendor.pagination.tailwind') }}
    </div>
</div>

<!-- Tabel dari database nanti nya -->
<div class="flex flex-col font-inter mb-10">
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
                            <th scope="col" class="px-6 py-4">Aksi</th>
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
                            <td class="whitespace-nowrap px-6 py-4">

                                <!-- Edit -->
                                <button wire:click="edit({{$rute->ID_Rute}})" class="flex-none p-2.5 bg-sky-500 rounded-xl hover:rounded-3xl hover:bg-sky-600 transition-all duration-300 text-white" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Delete -->
                                <button onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?') || event.stopImmediatePropagation()" wire:click="delete({{$rute->ID_Rute}})" class="flex-none p-2.5 bg-red-500 rounded-xl hover:rounded-3xl hover:bg-red-600 transition-all duration-300 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
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
{{-- <div class="mt-2 mb-14">
    {{ $dataRute->links(data: ['scrollTo' => false]) }}
</div> --}}