@extends('layout.adm')

@section('container')

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
                        <img src="/img/icons/peng-baru.png" class="flex-shrink-0 w-6 h-6 text-gray-500">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pengguna</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white text-8xl">
                            <!-- data dari data base nantinya --> 0
                        </p>
                        <a href="#" class="font-inter text-black hover:text-white inline-flex items-center">Selengkapnya
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Pemesanan -->
            <div class="p-4 max-w-sm">
                <div class="flex rounded-lg h-full w-80 bg-sky-400 p-6 flex-col">
                    <div class="flex items-center">
                        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-6 h-6 text-gray-500">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pemesanan Tiket</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white text-8xl">
                            <!-- data dari data base nantinya --> 0
                        </p>
                        <a href="#" class="font-inter text-black hover:text-white inline-flex items-center">Selengkapnya
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Pemasukan -->
            <div class="p-4 max-w-sm">
                <div class="flex rounded-lg h-full w-80 bg-sky-400 p-6 flex-col">
                    <div class="flex items-center">
                        <img src="/img/icons/dollar.png" class="flex-shrink-0 w-6 h-6 text-gray-500">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pemasukan</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white text-8xl">
                            <!-- data dari data base nantinya --> 0
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
@endsection