@extends('layout.main1')

@section('container')
<section class="backdrop-brightness-75">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-[#e2ac8b] rounded-xl shadow-inner md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6">
                <img src="/img/logo3.png" alt="logo" class="items-center justify-center w-40 mx-auto">
                <h1 class="text-lg font-semibold text-gray-900">Atur Password Baru</h1>

                <form class="space-y-4" action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email :</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 block w-full p-2.5">
                        @error('email')
                        <div class="mt-1 text-xs font-semibold text-red-800">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru :</label>
                        <input type="password" name="password" id="password" required autocomplete="new-password" class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 block w-full p-2.5">
                        <p class="mt-1 text-xs text-gray-800">Minimal 8 karakter, mengandung huruf dan angka.</p>
                        @error('password')
                        <div class="mt-1 text-xs font-semibold text-red-800">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Ulangi Password Baru :</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 block w-full p-2.5">
                    </div>
                    <button type="submit" class="w-full text-slate-950 bg-[#f49855] font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-[#ED9455] hover:text-slate-100">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
