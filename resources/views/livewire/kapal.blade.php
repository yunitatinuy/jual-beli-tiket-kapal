<!-- CONTENT -->
<div class="ms-10 mt-10 me-10 flex flex-col flex-grow">
    <span class="flex items-center p-2 text-gray-900 rounded-lg group">
        <img src="/img/icons/ships.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Kapal</span>
    </span>

    <div class="container font-inter">
        <div class="flex flex-wrap">
            @if (session()->has('success'))
            <div class="bg-green-500 text-black px-4 py-2 mt-3">
                {{session('success')}}
            </div>
            @endif
        </div>

        <div class="my-5">
            @if (session()->has('error'))
            <div class="bg-red-500 text-slate-800 px-4 py-2">
                {{session('error')}}
            </div>
        </div>
        @endif

        @if (session()->has('message'))
    <div class="bg-green-500 text-black px-4 py-2">
        {{ session('message') }}
    </div>
@endif
                <!-- Formulir -->
        
            <div class="container block max-w-2xl overflow-hidden bg-white border border-gray-200 rounded-lg shadow mt-10">
                <form wire:submit="store">
                <div class="bg-sky-200 p-6">
                    <span class="font-inter font-semibold text-xl ">Formulir Rute Perjalanan</span>
                </div>

                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <input type="text" name="namakapal" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " wire:model="namakapal"  />
                        <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama Kapal</label>

                        @error('namakapal')
                            <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                        @enderror
                        </div>
                </div>

                <div class="flex p-6">
                    <div class="relative z-0 w-full group">
                        <input type="text" name="kelas" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " wire:model="kelas"  />
                        <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kelas</label>
                        @error('kelas')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                    @enderror
                        </div>
                </div>
                <div class="flex p-6 mb-3">
                    <div class="relative z-0 w-full group">
                        <input type="text" name="jumlahkursi" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " wire:model="jumlahkursi"  />
                        <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Jumlah Kursi</label>
                        @error('jumlahkursi')
                        <span class="text-red-500 text-xs mt-3 block">{{$message}}</span>
                    @enderror
                        </div>
                </div>

            </div><br>
            <div class="flex flex-wrap">
                <button class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none group" type="submit" value="submit" wire:click.prevent="store">
                    <span class="flex-1 whitespace-nowrap">Tambah Data</span>
                </button>
            </div>
    </div>
    </form>
</div>

{{-- Search --}}
<form class="max-w-xs mt-5">   
    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
    <div class="relative">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
        </div>
        <input wire:model.live.debounce.500ms="search" type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." required />
    </div>
</form>

<!-- Tabel dari database nanti nya -->
<div class="mt-4">
    {{ $dataKapal->links() }}
</div>
<div class="flex flex-col mt-5">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-center text-sm text-surface">
                    <thead class="bg-sky-200 border-b border-neutral-200 font-medium">
                        <tr>
                            <th scope="col" class="px-6 py-4">No</th>
                            <th scope="col" class="px-6 py-4">Kode Kapal</th>
                            <th scope="col" class="px-6 py-4">Nama Kapal</th>
                            <th scope="col" class="px-6 py-4">Kelas</th>
                            <th scope="col" class="px-6 py-4">Jumlah Kursi</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataKapal as $key => $value)
                        <tr class="border-b border-secondary-200 bg-secondary-50 text-neutral-800">
                            <td class="whitespace-nowrap px-6 py-4 font-medium">{{$dataKapal->firstitem() + $key}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$value->idkapal}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$value->namakapal}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$value->kelas}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$value->jumlahkursi}}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                
                                <!-- Edit -->
                                <button class="flex-none p-2.5 bg-sky-500 rounded-xl hover:rounded-3xl hover:bg-sky-600 transition-all duration-300 text-white" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                
                                <!-- Delete -->
                                <button wire:click="delete({{$value->idkapal}})" class="flex-none p-2.5 bg-red-500 rounded-xl hover:rounded-3xl hover:bg-red-600 transition-all duration-300 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $dataKapal->links() }}
                </div>
                </div>
            </div>
    </div>
</div>

