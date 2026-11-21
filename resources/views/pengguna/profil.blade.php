@extends('layout.main')

@section('container')
    @php
        use Illuminate\Support\Str;

        $errorsBag = $errors ?? new \Illuminate\Support\ViewErrorBag;
        $email = $user->email;
        $emailMasked = Str::mask($email, '*', 3, max(strpos($email, '@') - 3, 0));
        $terverifikasi = !empty($user->email_verified_at);
        $formAktif = old('_form'); // 'profil' | 'sandi'
        $hasProfilError = $formAktif === 'profil' || ($errorsBag->has('name') || ($errorsBag->has('email') && $formAktif !== 'sandi'));
        $hasSandiError = $formAktif === 'sandi' || $errorsBag->has('password') || ($errorsBag->has('current_password') && $formAktif === 'sandi');
        $input = 'block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:outline-none focus:ring-1 focus:ring-[#c8175d] transition';
        $label = 'mb-2 block text-xs font-semibold text-slate-800';
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-xs text-slate-500 mb-5">
        <a href="/" class="hover:text-[#c8175d]">Beranda</a> <span class="mx-1.5">/</span>
        <span class="text-slate-700 font-medium">Profil saya</span>
    </nav>

    <h1 class="text-[28px] font-semibold text-slate-800">Profil saya</h1>
    <p class="mt-2 text-sm text-slate-500">Kelola informasi pribadi dan keamanan akunmu dalam satu tempat.</p>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mt-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="alert">
            <i class="bi bi-check-circle-fill text-lg text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mt-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-lg text-red-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[320px_1fr]"
        x-data="{
            ubahProfil: {{ $hasProfilError ? 'true' : 'false' }},
            ubahSandi: {{ $hasSandiError ? 'true' : 'false' }},
            tab: 'data-pribadi'
        }">

        {{-- ===== Sidebar ===== --}}
        <aside class="flex flex-col gap-5">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Akun pengguna</p>
                <h2 class="mt-2 text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $emailMasked }}</p>

                @if ($terverifikasi)
                    <span class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1 text-xs font-medium text-emerald-700">
                        <i class="bi bi-shield-check text-emerald-600"></i> Akun terverifikasi
                    </span>
                @else
                    <span class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1 text-xs font-medium text-amber-700">
                        <i class="bi bi-shield-exclamation text-amber-600"></i> Belum diverifikasi
                    </span>
                @endif

                <nav class="mt-5 space-y-1.5 border-t border-slate-100 pt-5 text-sm">
                    <a href="#data-pribadi" @click="tab = 'data-pribadi'"
                        class="flex items-center justify-between rounded-xl px-3.5 py-2.5 font-medium transition"
                        :class="tab === 'data-pribadi' ? 'bg-pink-50 font-semibold text-[#c8175d]' : 'text-slate-700 hover:bg-slate-50'">
                        <span><i class="bi bi-person mr-2"></i> Data pribadi</span>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </a>
                    <a href="#keamanan" @click="tab = 'keamanan'"
                        class="flex items-center justify-between rounded-xl px-3.5 py-2.5 font-medium transition"
                        :class="tab === 'keamanan' ? 'bg-pink-50 font-semibold text-[#c8175d]' : 'text-slate-700 hover:bg-slate-50'">
                        <span><i class="bi bi-shield-lock mr-2"></i> Keamanan akun</span>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </a>
                </nav>

                <form action="{{ route('logout') }}" method="POST" class="mt-5 border-t border-slate-100 pt-4">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 text-xs font-semibold text-red-600 hover:text-red-700 hover:underline">
                        <i class="bi bi-box-arrow-right"></i> Keluar dari akun
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-pink-100 bg-pink-50/70 p-6">
                <h3 class="text-base font-semibold text-[#c8175d]"><i class="bi bi-shield-lock-fill mr-1.5"></i> Privasimu terlindungi</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-600">Sebagian informasi ditampilkan tersamar untuk keamanan akun. Data pemesanan tiket Anda tersimpan secara aman.</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-slate-800">Pusat Bantuan</h3>
                <p class="mt-2 text-xs text-slate-500">Punya kendala pemesanan tiket atau perubahan data penumpang?</p>
                <a href="/informasi" class="mt-4 block rounded-xl border border-slate-200 py-2.5 text-center text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition">
                    Buka pusat bantuan
                </a>
            </section>
        </aside>

        {{-- ===== Konten ===== --}}
        <div class="flex flex-col gap-6">

            {{-- 1. Data pribadi --}}
            <section id="data-pribadi" class="scroll-mt-28 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-800">Data pribadi</h3>
                        <p class="mt-1 text-sm text-slate-500">Informasi ini digunakan sebagai data kontak dan pemesan tiket default.</p>
                    </div>
                    <button type="button" @click="ubahProfil = !ubahProfil"
                        class="rounded-xl px-4 py-2.5 text-xs font-semibold transition"
                        :class="ubahProfil ? 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' : 'bg-[#c8175d] text-white hover:bg-[#a6104b]'"
                        x-text="ubahProfil ? 'Batal' : 'Ubah profil'"></button>
                </div>

                {{-- Tampilan Informasi --}}
                <dl x-show="!ubahProfil" class="mt-6 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50/70 p-4">
                        <dt class="text-xs font-medium text-slate-500">Nama lengkap</dt>
                        <dd class="mt-1.5 text-sm font-semibold text-slate-800">{{ $user->name }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50/70 p-4">
                        <dt class="text-xs font-medium text-slate-500">Alamat email</dt>
                        <dd class="mt-1.5 text-sm font-semibold text-slate-800">{{ $emailMasked }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50/70 p-4 sm:col-span-2">
                        <dt class="text-xs font-medium text-slate-500">Status verifikasi email</dt>
                        <dd class="mt-1.5 flex items-center gap-2 text-sm font-semibold {{ $terverifikasi ? 'text-emerald-700' : 'text-amber-700' }}">
                            <i class="bi {{ $terverifikasi ? 'bi-check-circle-fill text-emerald-600' : 'bi-exclamation-circle-fill text-amber-600' }}"></i>
                            {{ $terverifikasi ? 'Email sudah diverifikasi' : 'Email belum diverifikasi' }}
                        </dd>
                    </div>
                </dl>

                {{-- Form ubah profil --}}
                <form x-cloak x-show="ubahProfil" action="{{ route('profile.update') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="profil">

                    <div>
                        <label for="name" class="{{ $label }}">Nama lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $input }}">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="{{ $label }}">Alamat email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $input }}">
                        <p class="mt-1 text-[11px] text-slate-500">Jika email diubah, Anda perlu memverifikasi email baru.</p>
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="current_password_profil" class="{{ $label }}">Kata sandi saat ini</label>
                        <input type="password" id="current_password_profil" name="current_password" autocomplete="current-password"
                            placeholder="Wajib diisi hanya jika mengubah email" class="{{ $input }}">
                        <p class="mt-1 text-[11px] text-slate-500">Konfirmasi kata sandi diperlukan hanya jika Anda mengubah alamat email.</p>
                        @if ($formAktif === 'profil') @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror @endif
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="rounded-xl bg-[#c8175d] px-6 py-3 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b]">
                            Simpan perubahan
                        </button>
                        <button type="button" @click="ubahProfil = false" class="rounded-xl border border-slate-200 px-6 py-3 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </button>
                    </div>
                </form>
            </section>

            {{-- 2. Keamanan akun (Kata Sandi) --}}
            <section id="keamanan" class="scroll-mt-28 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-800">Keamanan akun</h3>
                        <p class="mt-1 text-sm text-slate-500">Perbarui kata sandi secara berkala untuk menjaga akunmu tetap aman.</p>
                    </div>
                    <button type="button" @click="ubahSandi = !ubahSandi"
                        class="rounded-xl px-4 py-2.5 text-xs font-semibold transition"
                        :class="ubahSandi ? 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' : 'border border-slate-200 bg-white text-[#c8175d] hover:bg-pink-50'"
                        x-text="ubahSandi ? 'Batal' : 'Ubah kata sandi'"></button>
                </div>

                {{-- Tampilan Sandi Statis --}}
                <div x-show="!ubahSandi" class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-xl bg-slate-50/70 p-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Kata sandi</p>
                        <p class="mt-1 text-base tracking-widest text-slate-700">••••••••••••</p>
                        <p class="mt-1 text-xs text-slate-400">Terakhir diperbarui: akun aktif</p>
                    </div>
                    <button type="button" @click="ubahSandi = true"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition">
                        Ganti kata sandi
                    </button>
                </div>

                {{-- Form ubah sandi --}}
                <form x-cloak x-show="ubahSandi" action="{{ route('profile.update') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="sandi">
                    {{-- name & email dikirimkan agar validasi controller tetap terpenuhi --}}
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div>
                        <label for="current_password_sandi" class="{{ $label }}">Kata sandi saat ini <span class="text-red-500">*</span></label>
                        <input type="password" id="current_password_sandi" name="current_password" autocomplete="current-password" required placeholder="Masukkan kata sandi saat ini" class="{{ $input }}">
                        @if ($formAktif === 'sandi') @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror @endif
                    </div>

                    <div>
                        <label for="password" class="{{ $label }}">Kata sandi baru <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" autocomplete="new-password" required placeholder="Masukkan kata sandi baru" class="{{ $input }}">
                        <p class="mt-1 text-[11px] text-slate-500">Minimal 8 karakter, harus mengandung huruf dan angka.</p>
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Konfirmasi kata sandi baru <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required placeholder="Ulangi kata sandi baru" class="{{ $input }}">
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="rounded-xl bg-[#c8175d] px-6 py-3 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b]">
                            Simpan kata sandi
                        </button>
                        <button type="button" @click="ubahSandi = false" class="rounded-xl border border-slate-200 px-6 py-3 text-xs font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
