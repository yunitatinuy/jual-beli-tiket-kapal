@extends('layout.main')

@section('container')

<!-- CONTENT -->

<div class="max-w-2xl mx-auto w-full bg-white p-6 border rounded-lg shadow-lg">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Edit
                </button>
            </div>
        </div>
    </div>
    
@endsection