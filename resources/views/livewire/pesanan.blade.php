<!-- CONTENT -->
<div class="ms-10 mt-8 me-10 flex flex-col flex-grow">
    <span class="flex items-center p-2 text-gray-900 rounded-lg group">
        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-9 h-9 text-gray-500" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Pesanan</span>
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
                    <span class="font-inter font-semibold text-xl ">Formulir Tiket</span>
                </div>
                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <label for="ID_Rute" class="block mb-2 text-sm font-medium text-gray-900">Rute Perjalanan</label>
                        <select name="ID_Rute" id="ID_Rute" wire:model="ID_Rute" class="mt-1 bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">- Pilih Rute -</option>
                            @foreach($dataRute as $rute)
                            <option value="{{ $rute->ID_Rute }}">{{ $rute->pelabuhanAsal->Nama_Pelabuhan }}, {{$rute->pelabuhanAsal->Nama_Kota }} - {{ $rute->pelabuhanTujuan->Nama_Pelabuhan }}, {{$rute->pelabuhanTujuan->Nama_Kota }}</option>
                            @endforeach
                        </select>
                        @error('ID_Rute')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <label for="ID_User" class="block mb-2 text-sm font-medium text-gray-900">Nama User</label>
                        <select name="ID_User" id="ID_User" wire:model="ID_User" class="mt-1 bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">- Pilih User -</option>
                            @foreach($dataUser as $User)
                            <option value="{{ $User->ID_User }}">{{ $User->name}}</option>
                            @endforeach
                        </select>
                        @error('ID_User')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <label for="ID_Tiket" class="block mb-2 text-sm font-medium text-gray-900">Kode Tiket</label>
                        <select name="ID_Tiket" id="ID_Tiket" wire:model="ID_Tiket" class="mt-1 bg-gray-50 border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">- Pilih Tiket -</option>
                            @foreach($dataTiket as $tiket)
                            <option value="{{ $tiket->ID_Tiket }}">{{ $tiket->ID_Tiket }}</option>
                            @endforeach
                        </select>
                        @error('ID_Tiket')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex px-6 py-3">
                    <div class="relative z-0 w-full group">
                        <label for="Tanggal_Pesanan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pesanan</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input type="date" wire:model="Tanggal_Pesanan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " placeholder="Pilih Tanggal">
                        </div>
                        @error('Tanggal_Pesanan')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <label for="Total_Pesanan" class="block mb-2 text-sm font-medium text-gray-900">Total Pesanan</label>
                        <input type="text" name="Total_Pesanan" id="Total_Pesanan" wire:model="Total_Pesanan" class="block py-2.5 px-4 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Masukkan Total Pesanan" required />
                        @error('Total_Pesanan')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
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
        {{ $dataPesanan->links('vendor.pagination.tailwind') }}
    </div>
</div>

<!-- Tabel Data Pesanan -->
<div class="flex flex-col font-inter mt-5 mb-10">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-center divide-y divide-neutral-200">
                    <thead class="bg-[#ff9853be] text-sm font-medium text-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            <th scope="col" class="px-6 py-4">Rute Perjalanan</th>
                            <th scope="col" class="px-6 py-4">Kode User</th>
                            <th scope="col" class="px-6 py-4">Kode Tiket</th>
                            <th scope="col" class="px-6 py-4">Tanggal Pesanan</th>
                            <th scope="col" class="px-6 py-4">Waktu</th>
                            <th scope="col" class="px-6 py-4">Total Pesanan</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse ($dataPesanan as $key => $value)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $dataPesanan->firstItem() + $key }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $this->getRute($value->ID_Rute)}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $value->ID_User }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $value->ID_Tiket }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $value->Tanggal_Pesanan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $value->Waktu }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $value->Total_Pesanan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="edit({{ $value->ID_Pesanan }})" class="inline-block px-3 py-1.5 bg-sky-500 text-white rounded-md font-medium hover:bg-sky-600 focus:bg-primary-accent-300 focus:outline-none focus:ring-0">Edit</button>
                                <button onclick="confirm('Apakah Anda yakin ingin menghapus data ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $value->ID_Pesanan }})" class="inline-block px-3 py-1.5 bg-red-500 text-white rounded-md font-medium hover:bg-red-600 focus:bg-primary-accent-300 focus:outline-none focus:ring-0">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Belum ada data pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
</div>
</div>
{{-- <div class="mt-2 mb-14">
{{ $pesanan->links(data: ['scrollTo' => false]) }}
</div> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to set the current time to the input field
        function setCurrentTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}`;
            document.getElementById('Waktu').value = currentTime;
        }

        // Set the current time immediately
        setCurrentTime();

        // Update the current time every minute
        setInterval(setCurrentTime, 60000);
    });
</script>