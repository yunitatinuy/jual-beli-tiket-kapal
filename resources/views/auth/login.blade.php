@extends('layout.main1')

@section('container')
    @php
        $input = 'block w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:outline-none focus:ring-0 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-100 dark:placeholder:text-slate-500';
    @endphp

    <div class="w-full max-w-[568px]">
        {{-- Notifikasi --}}
        @if (session()->has('success') || session()->has('succes'))
            <div class="mb-4 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill text-lg text-emerald-600 dark:text-emerald-400"></i>
                <div>{{ session('success') ?? session('succes') }}</div>
            </div>
        @endif
        @if (session()->has('loginError'))
            <div class="mb-4 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 dark:border-rose-800 dark:bg-rose-950/50 dark:text-rose-300 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill text-lg text-rose-600 dark:text-rose-400"></i>
                <div>{{ session('loginError') }}</div>
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-800" x-data="{ lihat: false }">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#c8175d]">Masuk ke akun</p>
                <div class="sm:hidden">
                    @include('partials.theme-switcher')
                </div>
            </div>
            <h1 class="mt-3 text-[32px] font-semibold leading-tight text-slate-800 dark:text-slate-100">Selamat datang kembali</h1>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Masuk untuk melanjutkan pemesanan dan melihat e-tiketmu.</p>

            <form action="/login" method="post" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Alamat email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com"
                        autocomplete="email" required autofocus class="{{ $input }} @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Kata sandi</label>
                    <div class="relative">
                        <input :type="lihat ? 'text' : 'password'" name="password" id="password" placeholder="Masukkan kata sandi"
                            autocomplete="current-password" required class="{{ $input }} pr-20 @error('password') border-red-400 @enderror">
                        <button type="button" @click="lihat = !lihat" x-text="lihat ? 'Sembunyi' : 'Lihat'"
                            class="absolute inset-y-0 right-4 text-xs font-semibold text-[#c8175d]"></button>
                    </div>
                    @error('password')<p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 accent-[#c8175d]"> Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="font-semibold text-[#c8175d] hover:underline">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="w-full rounded-xl bg-[#c8175d] py-3.5 text-sm font-semibold text-white transition hover:bg-[#a6104b]">Masuk</button>
            </form>

            <div class="my-6 flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                <span class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></span> Belum punya akun? <span class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></span>
            </div>

            <a href="/register" class="block w-full rounded-xl border border-slate-200 py-3.5 text-center text-sm font-semibold text-[#c8175d] transition hover:bg-pink-50 dark:border-slate-700 dark:hover:bg-slate-700">Buat akun baru</a>

            <p class="mt-6 text-[11px] text-slate-500 dark:text-slate-400">Jangan bagikan kata sandi atau kode OTP kepada siapa pun.</p>
        </section>
    </div>
@endsection