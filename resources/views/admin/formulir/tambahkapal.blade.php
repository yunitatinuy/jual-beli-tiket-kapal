@extends('layout.adm')

@section('container')

<!-- CONTENT -->

<div class="ms-10 mt-10 me-10 flex flex-col flex-grow">
    <span class="flex items-center p-2 text-gray-900 rounded-lg group">
        <img src="/img/icons/ships.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Kapal</span>
    </span>

    <!-- Formulir -->

    <div class="my-5">
            @if (session()->has('error'))
        <div class="bg-red-500 text-slate-800 px-4 py-2">
            {{session('error')}}
        </div>
    </div>
    @endif
<form action="" method="post">
    @csrf
    <div class="container block max-w-2xl overflow-hidden bg-white border border-gray-200 rounded-lg shadow mt-10">
        <div class="bg-sky-200 p-6">
            <span class="font-inter font-semibold text-xl ">Formulir Rute Perjalanan</span>
        </div>

        <div class="flex p-6">
            <div class="relative z-0 w-full group">
                <input type="text" name="namakapal" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama Kapal</label>
                @error('title')
                <span class="font-inter text-sm">{{$message}}</span>
                @enderror
            </div>
        </div>

        <div class="flex p-6">
            <div class="relative z-0 w-full group">
                <input type="text" name="kelas" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kelas</label>
                @error('title')
                <span class="font-inter text-sm">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="flex p-6">
            <div class="relative z-0 w-full group">
                <input type="text" name="jumlahkursi" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="" class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Jumlah Kursi</label>
                @error('title')
                <span class="font-inter text-sm">{{$message}}</span>
                @enderror
            </div>
        </div>

    </div><br>
    <a href="#" class="flex items-center p-2 text-gray-900 border-2 rounded-lg bg-slate-100 hover:bg-sky-500 hover:text-white  transition duration-700 group max-w-24">
        <span class="flex-1 ms-3 whitespace-nowrap">Simpan</span>
    </a>
</div>
</form>
</div>
@endsection