<!-- CONTENT -->
<div class="ms-10 mt-8 me-10 flex flex-col flex-grow">
    <span class="flex items-center p-2 text-gray-900 rounded-lg group">
        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-9 h-9 text-gray-500" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Pesanan</span>
    </span>

<!-- Tabel Data Pesanan -->
<div class="flex flex-col font-inter mt-5 mb-10">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-center divide-y divide-neutral-200">
                    <thead class="bg-[#ff9853be] text-sm font-medium text-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            <th scope="col" class="px-6 py-4">ID Penumpang</th>
                            <th scope="col" class="px-6 py-4">Email</th>
                            <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-4">Nomor Telepon</th>
                            <th scope="col" class="px-6 py-4">Alamat</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse ($dataPenumpang as $penumpang)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $penumpang->ID_Penumpang}}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $penumpang->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $penumpang->Nama_Lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $penumpang->Telepon }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $penumpang->Alamat }}</td>
                            <td class="rounded bg-green-400 text-xs font-medium uppercase leading-normal text-white">Done</td>
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
