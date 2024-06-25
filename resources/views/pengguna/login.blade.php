@extends('layout.main1')

@section('container')
    <section class="backdrop-brightness-75">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            @if (session()->has('succes'))
                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        {{ session('succes') }}
                    </div>
                </div>
            @endif

            @if (session()->has('loginError'))
                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        {{ session('loginError') }}
                    </div>
                </div>
            @endif
            <div class="w-full bg-[#e2ac8b] rounded-xl shadow-inner light:border md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6">
                    <img src="/img/logo3.png" alt="" class="items-center justify-center w-40 mx-auto">
                    <form class="space-y-4 md:space-y-6" action="" method="post"> 
                        @csrf
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email :</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback font-semibold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Password :</label>
                            <input type="password" name="password" id="password"
                                class="bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-[#c99a7c] focus:ring-4 focus:border-slate-50 focus:bg-slate-300 block w-full p-2.5 @error('password') is-invalid @enderror">
                                @error('password')
                                <div class="invalid-feedback font-semibold">
                                    {{ $message }}
                                </div>
                            @enderror  
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" type="checkbox"
                                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300"
                                        >
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-black light:text-gray-300">Remember me</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full text-slate-950 bg-slate-50 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-slate-300">Login</button>
                        <p class="text-sm font-light text-black light:text-gray-400 ">Belum punya akun? <a href="/register"
                                class="font-medium text-slate-50 hover:underline hover:text-slate-50">Registrasi disini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
