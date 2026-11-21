@extends('layout.main1')

@section('container')
<div class="w-full max-w-[568px]">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-pink-50 text-[#c8175d] dark:bg-pink-950/60 dark:text-pink-400 shadow-inner">
            <i class="bi bi-envelope-check text-4xl"></i>
        </div>

        <h1 class="mt-5 text-2xl font-bold text-slate-800 dark:text-slate-100">Verifikasi Email Anda</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Terima kasih telah mendaftar di Anang Ship. Kami telah mengirimkan tautan verifikasi ke email Anda:
        </p>

        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-pink-300">
            {{ auth()->user()?->email ?? session('registered_email') ?? 'Email Anda' }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 text-left">
                <i class="bi bi-check-circle-fill mr-1 text-emerald-600 dark:text-emerald-400"></i> Link verifikasi baru berhasil dikirimkan ke email Anda!
            </div>
        @endif

        <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">
            Silakan buka email Anda dan klik tombol verifikasi di dalamnya. Setelah selesai, Anda dapat login ke akun Anda.
        </p>

        <div class="mt-6 flex flex-col gap-3">
            <a href="/login" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#c8175d] py-3.5 text-sm font-semibold text-white transition hover:bg-[#a6104b]">
                <i class="bi bi-box-arrow-in-right"></i> Menuju Halaman Login
            </a>

            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ auth()->user()?->email ?? session('registered_email') }}">
                <input type="hidden" name="user_id" value="{{ auth()->user()?->id ?? session('registered_user_id') }}">
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    <i class="bi bi-arrow-repeat text-[#c8175d] dark:text-pink-400"></i> Kirim Ulang Email Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
