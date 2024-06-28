@extends('layout.main')

@section('container')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="flex flex-wrap justify-between my-20">
    <div class="container w-full flex mb-4">
        <img src="/img/icons/harbor.png" class="w-7 h-5 ms-16 pr-2">
        <span href="#" class="text-white px-2 text-sm font-medium bg-neutral-400 rounded-sm">Batam</span>
        <span href="#" class="px-2 text-sm font-medium"><img src="/img/icons/swap.png" class="w-4"></span>
        <span href="#" class="text-white  px-2 text-sm font-medium bg-neutral-400 rounded-sm">Karimun</span>
    </div>
    <!-- container -->
    <div class="container booking">
        <div class="container justify-content-center py-8 px-5 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]" style="width: 95%; background-color:#c2c1c1; border-radius:30px;">
            <div class="container flex flex-nowrap space-x-12 mx-6 pt-2 items-center">
                <img src="/img/icons/ships.png" class="w-12">
                <div class="">
                    Nongsapura Feri Terminal, Batam <br>
                    50m Perjalanan Feri Batam Feri Cepat Kelas Ekonomi
                </div>
                <div class="">
                    08 : 00 - 09 : 50
                </div>
                <div class="pl-24">
                    Tanah Merah Feri Terminal, Singapore
                </div>
            </div>
            <br>
            <div class="border border-b-black">
            </div>
            <br>
            <div class="container">
                <h1 class="flex justify-center font-bold text-xl">DETAIL TIKET PENUMPANG</h1>
                <form action="{{ route('pesantiket.store') }}" method="POST">
                    @csrf
                    <div class="main-user-info">
                        <div class="user-input-box">
                            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <div class="mt-2">
                                        <input type="text" class="form-control" id="Nama_Lengkap" name="Nama_Lengkap" value="{{ old('Nama_Lengkap') }}" autocomplete="given-name" placeholder="Nama Lengkap" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        @error('Nama_Lengkap')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <div class="mt-2">
                                        <input type="number" class="form-control" id="Telepon" name="Telepon" value="{{ old('Telepon') }}" autocomplete="given-name" placeholder="Telepon" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        @error('Telepon')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="sm:col-span-3">
                                    <div class="mt-2">
                                        <textarea class="form-control" id="Alamat" name="Alamat" autocomplete="family-name" placeholder="Alamat" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('Alamat') }}</textarea>
                                    </div>
                                    @error('Alamat')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <br>
                                <button type="submit" class="my-2 h-auto font-sans text-sm font-medium text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">Pesan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection