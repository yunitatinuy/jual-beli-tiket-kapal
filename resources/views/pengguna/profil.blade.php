@extends('layout.main')

@section('container')

<!-- CONTENT -->
<div class="w-full my-10 flex flex-initial px-3 lg:px-28 text-[#161931]  ">
    <aside class="hidden py-4 w-1/5 md:block bg-gradient-to-b from-[#ffd9c1] to-transparent my-2 rounded-2xl ">
        <div class="sticky flex flex-col gap-2 p-4 text-sm border-r border-indigo-100 top-12 ">
            <h2 class="pl-3 mb-4 text-2xl font-semibold">Settings</h2>
            <a href="/dashboard_pengguna" class="flex items-center px-3 py-2.5 font-semibold hover:bg-[#fcc09b] hover:text-white hover:border-black hover:rounded-full  ">
                Keluar
            </a>
        </div>
    </aside>
    
    <!-- Detail Pengguna -->
    <div class="grid-cols-2 w-1/2 font-inter">
    <div class="w-full p-6 rounded-lg shadow-xl">
        <div class="pb-4">
            <span class="font-inter font-semibold text-xl ">Detail Akun</span>
        </div>
        <!-- Card child container -->
        <div class="flex flex-col space-y-4">
            <!-- Repeatable card -->
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <img src="/img/icons/user.png" class="w-10 h-10">
                    <div>
                        <div class="text-lg font-semibold">Nama</div>
                        <div class="text-sm text-gray-600">{{Auth::user()->name}}</div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Email</div>
                        <div class="text-sm text-gray-600">{{$user->email}}</div>
                    </div>
                </div>
            </div>
           <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Password</div>
                        <div class="text-sm text-gray-600">*************</div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 border rounded-lg shadow-sm bg-white">
                <div class="flex items-center space-x-4">
                    <div>
                        <div class="text-lg font-semibold">Pembayaran</div>
                        <div class="text-sm text-gray-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Data Pengguna -->
<div class="w-1/2 p-6 rounded-lg">
        <div class="pb-4">
            <span class="font-inter font-semibold text-xl ">Edit Akun</span>
        </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-2 focus:border-slate-50 focus:bg-slate-300" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-2 focus:border-slate-50 focus:bg-slate-300" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-2 focus:border-slate-50 focus:bg-slate-300" placeholder="Masukkan Password Baru . . .">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-2 focus:border-slate-50 focus:bg-slate-300" placeholder="Confirmasi Password Baru . . .">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</div>

@endsection