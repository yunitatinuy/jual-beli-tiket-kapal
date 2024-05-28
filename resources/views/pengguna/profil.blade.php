@extends('layout.main')

@section('container')

<!-- CONTENT -->
<div class="flex ">
    <div class="my-8 bg-slate-600 h-1/3 w-auto rounded-lg flex-none">
        <div class="m-2">
            <div class="items-center inline-flex">Profil
                <img src="/img/icons/user.png" alt="" class="w-4 h-4 mx-2">
            </div>
        </div>
        <div class="m-2">
            <div class="items-center inline-flex">Pengaturan
                <img src="/img/icons/setting.png" alt="" class="w-4 h-4 mx-2">
            </div>
        </div>
        <div class="m-2">
            <div class="items-center inline-flex">Keluar
                <img src="/img/icons/keluar.png" alt="" class="w-4 h-4 mx-2">
            </div>
        </div>
    </div>
<div class="max-w-2xl mx-auto w-full bg-slate-600 flex-auto p-6 rounded-lg shadow-xl my-8">
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