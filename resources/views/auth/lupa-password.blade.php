@extends('layout.main1')

@section('container')
<section class="backdrop-brightness-75">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full bg-[#e2ac8b] rounded-xl shadow-inner md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6">
                <img src="/img/logo3.png" alt="logo" class="items-center justify-center w-40 mx-auto">
                <h1 class="text-lg font-semibold text-gray-900">Lupa Password</h1>
                <p class="text-sm text-gray-900">Masukkan email akun Anda. Kami akan mengirim link untuk mengatur ulang password.</p>

                @if (session('status'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">{{ session('status') }}</div>
                @endif

                <form class="space-y-4" action="{{ route('password.email') }}" method="post">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email :</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 block w-full p-2.5">
                        @error('email')
                        <div class="mt-1 text-xs font-semibold text-red-800">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="w-full text-slate-950 bg-[#f49855] font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-[#ED9455] hover:text-slate-100">Kirim Link Reset</button>
                    <p class="text-sm text-black"><a href="{{ route('login') }}" class="font-medium text-slate-50 hover:underline">Kembali ke login</a></p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
