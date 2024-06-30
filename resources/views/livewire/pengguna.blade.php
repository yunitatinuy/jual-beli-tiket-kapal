    <!-- CONTENT -->
    <div class="ms-10 mt-10 me-10 flex flex-col flex-grow">
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/pengguna.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Pengguna</span>
        </span>

        <div class="flex flex-wrap justify-between mt-10 ">
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
                {{ $dataPengguna->links('vendor.pagination.tailwind') }}
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
                                    <th scope="col" class="px-6 py-4">Id Pengguna</th>
                                    <th scope="col" class="px-6 py-4">Nama</th>
                                    <th scope="col" class="px-6 py-4">Email</th>
                                    <th scope="col" class="px-6 py-4">Role</th>
                                    <th scope="col" class="px-6 py-4">Tanggal Daftar</th>
                                </tr>
                            </thead>
                            @php
                            use Carbon\Carbon;
                            @endphp
                            <tbody>
                                @forelse ($dataPengguna as $users)
                                <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loop->iteration }}</td>

                                    <td class="whitespace-nowrap px-6 py-4">{{$users->id}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{$users->name}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{$users->email}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{$users->role}}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{$users->created_at->format('d/m/y')}}</td>
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
    {{ $dataPengguna->links(data: ['scrollTo' => false]) }}
    </div> --}}