@extends('layout.main')

@section('container')

<!-- CONTENT -->
<div class="flex my-12">
    <div class="flex flex-col rounded-r-2xl left-0 w-40 bg-white h-full text-gray-600 border-slate-800 z-10 sidebar">
        <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">
            <ul class="flex flex-col py-3 space-y-1">
                <li>
                    <a href="#" class="relative flex flex-row items-center h-11 text-gray-600 border-l-4 border-transparent">
                        <!-- Icono Home de Font Awesome -->
                        <span class="inline-flex justify-center items-center ml-4">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate flex">Profil</span><img src="/img/icons/user.png" alt="" class="w-4 h-4 ml-auto mr-4">
                    </a>
                </li>
                <li>
                    <a href="#" class="relative flex flex-row items-center h-11 text-gray-600 border-l-4 border-transparent">
                        <!-- Icono Check Circle de Font Awesome -->
                        <span class="inline-flex justify-center items-center ml-4">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Pengaturan</span> <img src="/img/icons/setting.png" alt="" class="w-4 h-4 ml-auto mr-4">
                    </a>
                </li>
                <li>
                    <a href="#" class="relative flex flex-row items-center h-11 text-gray-600 border-l-4 border-transparent">
                        <!-- Icono Users de Font Awesome -->
                        <span class="inline-flex justify-center items-center ml-4">
                            <i class="fas fa-users"></i>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Keluar</span> <img src="/img/icons/keluar.png" alt="" class="w-4 h-4 ml-auto mr-4">
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="max-w-2xl mx-auto w-full bg-slate-600 flex-auto p-6 rounded-lg shadow-xl">
        <div class="pb-4">
            <span class="font-inter font-semibold text-xl ">Detail Pengguna</span>
        </div>
        <!-- Card child container -->
        <div class="flex flex-col space-y-4">
            <!-- Repeatable card -->
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <img src="/img/icons/user.png" class="w-10 h-10">
                    <div>
                        <div class="text-lg font-semibold">Nama</div>
                        <div class="text-sm text-gray-600">Firjatullah Anang Setiawan</div>
                    </div>
                </div>
                <button class="bg-[#fcc09b] text-white px-4 py-2 rounded-lg hover:bg-[#ac836a]">
                    Edit
                </button>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Email</div>
                        <div class="text-sm text-gray-600">firjatullahanang@gmail.com</div>
                    </div>
                </div>
                <button class="bg-[#fcc09b] text-white px-4 py-2 rounded-lg hover:bg-[#ac836a]">
                    Edit
                </button>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Telepon</div>
                        <div class="text-sm text-gray-600">0812456789008</div>
                    </div>
                </div>
                <button class="bg-[#fcc09b] text-white px-4 py-2 rounded-lg hover:bg-[#ac836a]">
                    Edit
                </button>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Password</div>
                        <div class="text-sm text-gray-600">*************</div>
                    </div>
                </div>
                <button class="bg-[#fcc09b] text-white px-4 py-2 rounded-lg hover:bg-[#ac836a]">
                    Edit
                </button>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Pembayaran</div>
                        <div class="text-sm text-gray-600"></div>
                    </div>
                </div>
                <button class="bg-[#fcc09b] text-white px-4 py-2 rounded-lg hover:bg-[#ac836a]">
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection