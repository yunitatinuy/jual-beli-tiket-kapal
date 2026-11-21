@extends('layout.main1')

@section('container')
    @php
        $input = 'block w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:outline-none focus:ring-0 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-100 dark:placeholder:text-slate-500';
    @endphp

    <div class="w-full max-w-[568px]"
        x-data="{
            modalVerifikasi: {{ session('show_verify_modal') ? 'true' : 'false' }},
            lihat1: false,
            lihat2: false,
            pw: '',
            cpw: '',
            cocok() { return this.cpw === '' || this.pw === this.cpw }
        }">

        {{-- ================= POP UP MODAL VERIFIKASI EMAIL ================= --}}
        <div x-cloak x-show="modalVerifikasi"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

            <div @click.away="modalVerifikasi = false"
                x-show="modalVerifikasi"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 text-center shadow-2xl dark:border-slate-700 dark:bg-slate-900">

                {{-- Tombol Tutup (X) --}}
                <button type="button" @click="modalVerifikasi = false"
                    class="absolute top-5 right-5 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200 transition"
                    title="Tutup pop-up">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>

                {{-- Ikon Amplop / Verifikasi --}}
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-pink-50 text-[#c8175d] dark:bg-pink-950/60 dark:text-pink-400 shadow-inner">
                    <i class="bi bi-envelope-check text-4xl"></i>
                </div>

                <h2 class="mt-5 text-2xl font-bold text-slate-800 dark:text-slate-100">Verifikasi Email Anda</h2>

                <p class="mt-2.5 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    @if (session('status') === 'registered')
                        Pendaftaran akun berhasil! Kami telah mengirimkan tautan verifikasi ke alamat email:
                    @else
                        {{ session('verify_message') ?? 'Kami telah mengirimkan tautan verifikasi ke alamat email Anda:' }}
                    @endif
                </p>

                {{-- Tampilan Alamat Email Penerima --}}
                <div class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-300 break-all">
                    <i class="bi bi-envelope-fill text-[#c8175d] dark:text-pink-400"></i>
                    <span>{{ session('registered_email') ?? (auth()->user()?->email ?? 'nama@email.com') }}</span>
                </div>

                {{-- Pesan Sukses Kirim Ulang --}}
                @if (session('status') === 'verification-link-sent')
                    <div class="mt-4 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 text-left">
                        <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                        <span>Tautan verifikasi baru berhasil dikirimkan! Silakan periksa kotak masuk atau spam.</span>
                    </div>
                @endif

                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Silakan buka email Anda dan klik tombol verifikasi di dalamnya. <strong>Setelah verifikasi selesai, Anda akan diarahkan ke halaman login untuk masuk ke akun Anda.</strong>
                </p>

                {{-- Tombol Aksi --}}
                <div class="mt-6 flex flex-col gap-2.5">
                    <a href="/login"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#c8175d] py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#a6104b] focus:outline-none focus:ring-2 focus:ring-[#c8175d]/20">
                        <i class="bi bi-box-arrow-in-right"></i> Menuju Halaman Login
                    </a>

                    <form action="{{ route('verification.resend') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('registered_email') ?? auth()->user()?->email }}">
                        <input type="hidden" name="user_id" value="{{ session('registered_user_id') ?? auth()->user()?->id }}">
                        <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                            <i class="bi bi-arrow-repeat text-[#c8175d] dark:text-pink-400"></i> Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ================= FORM PENDAFTARAN ================= --}}
        <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-800">

            <div class="flex items-center justify-between">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#c8175d]">Daftar akun</p>
                <div class="sm:hidden">
                    @include('partials.theme-switcher')
                </div>
            </div>
            <h1 class="mt-3 text-[32px] font-semibold leading-tight text-slate-800 dark:text-slate-100">Buat akun baru</h1>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Daftar untuk mulai memesan dan mengelola tiket kapalmu.</p>

            <form action="/register" method="post" class="mt-6 space-y-5"
                @submit="if (!cocok()) { $event.preventDefault(); $refs.cpw.focus() }">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Nama lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                        autocomplete="name" required class="{{ $input }} @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Alamat email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com"
                        autocomplete="email" required class="{{ $input }} @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Kata sandi</label>
                    <div class="relative">
                        <input :type="lihat1 ? 'text' : 'password'" name="password" id="password" x-model="pw" placeholder="Masukkan kata sandi"
                            autocomplete="new-password" required class="{{ $input }} pr-20 @error('password') border-red-400 @enderror">
                        <button type="button" @click="lihat1 = !lihat1" x-text="lihat1 ? 'Sembunyi' : 'Lihat'"
                            class="absolute inset-y-0 right-4 text-xs font-semibold text-[#c8175d]"></button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-[11px] text-slate-500 dark:text-slate-400">Minimal 8 karakter, gunakan kombinasi huruf dan angka.</p>
                    @enderror
                </div>

                <div>
                    <label for="c_password" class="mb-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Konfirmasi kata sandi</label>
                    <div class="relative">
                        <input :type="lihat2 ? 'text' : 'password'" name="c_password" id="c_password" x-model="cpw" x-ref="cpw" placeholder="Ulangi kata sandi"
                            autocomplete="new-password" required
                            :class="cocok() ? '' : 'border-red-400'"
                            class="{{ $input }} pr-20 @error('c_password') border-red-400 @enderror">
                        <button type="button" @click="lihat2 = !lihat2" x-text="lihat2 ? 'Sembunyi' : 'Lihat'"
                            class="absolute inset-y-0 right-4 text-xs font-semibold text-[#c8175d]"></button>
                    </div>
                    <p x-cloak x-show="!cocok()" class="mt-1.5 text-xs font-medium text-red-700">Konfirmasi kata sandi tidak sama.</p>
                    @error('c_password')<p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-start gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <input type="checkbox" required class="mt-0.5 rounded border-slate-300 accent-[#c8175d]">
                    <span>Saya menyetujui <a href="#" class="font-semibold text-[#c8175d]">Syarat &amp; Ketentuan</a> serta <a href="#" class="font-semibold text-[#c8175d]">Kebijakan Privasi</a> ANANG SHIP.</span>
                </label>

                <button type="submit" class="w-full rounded-xl bg-[#c8175d] py-3.5 text-sm font-semibold text-white transition hover:bg-[#a6104b]">Daftar sekarang</button>

                <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                    Sudah punya akun? <a href="/login" class="font-semibold text-[#c8175d] hover:underline">Masuk di sini</a>
                </p>
            </form>
        </section>
    </div>
@endsection