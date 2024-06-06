@extends('layout.main')

@section('container')

<!-- CONTENT -->

<!-- <div class="flex flex-col rounded-r-2xl left-0 w-40 bg-white h-full text-gray-600 border-slate-800 z-10 sidebar"> -->
<div class="w-full flex flex-initial px-3 lg:px-28 text-[#161931]">
    <aside class="hidden py-4 w-1/3 lg:w-1/4 md:block">
        <div class="sticky flex flex-col gap-2 p-4 text-sm border-r border-indigo-100 top-12">

            <h2 class="pl-3 mb-4 text-2xl font-semibold">Settings</h2>

            <a href="#" class="flex items-center px-3 py-2.5 font-bold bg-white  text-indigo-900 border rounded-full">
                Profil
            </a>
            <a href="#" class="flex items-center px-3 py-2.5 font-semibold  hover:text-indigo-900 hover:border hover:rounded-full">
                Pengaturan
            </a>
            <a href="#" class="flex items-center px-3 py-2.5 font-semibold hover:text-indigo-900 hover:border hover:rounded-full  ">
                Keluar
            </a>
        </div>
    </aside>
    <div class="max-w-2xl mx-auto w-1/2 flex-initial p-6 rounded-lg shadow-xl">
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