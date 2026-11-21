@php
    use App\Models\Pesanan;
    $badge = fn ($status) => match ($status) {
        Pesanan::STATUS_LUNAS => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        Pesanan::STATUS_DIBATALKAN => 'bg-rose-50 text-rose-700 border border-rose-200',
        default => 'bg-amber-50 text-amber-700 border border-amber-200',
    };
@endphp

<div class="space-y-6">
    {{-- Header Dashboard --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Dashboard Operasional</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas tiket kapal, transaksi masuk, dan keberangkatan hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Sistem Online
            </span>
        </div>
    </div>

    {{-- Kartu Ringkasan Metrik (4 Kolom) --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        {{-- 1. Pendapatan Bulan Ini --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pendapatan Bulan Ini</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-xl text-[#c8175d]">
                    <i class="bi bi-wallet2"></i>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-800">{{ rupiah($ringkasan['pendapatanBulanIni']) }}</p>
            <p class="mt-2 text-xs text-slate-500">Total sepanjang waktu: <span class="font-semibold text-slate-700">{{ rupiah($ringkasan['pendapatanTotal']) }}</span></p>
        </div>

        {{-- 2. Pesanan Hari Ini --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pesanan Hari Ini</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-xl text-sky-600">
                    <i class="bi bi-cart-check"></i>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-800">{{ $ringkasan['pesananHariIni'] }}</p>
            <p class="mt-2 text-xs text-slate-500"><span class="font-semibold text-emerald-600">{{ $ringkasan['lunasHariIni'] }}</span> pembayaran lunas hari ini</p>
        </div>

        {{-- 3. Menunggu Pembayaran --}}
        <a href="/admin/pesanan?status={{ Pesanan::STATUS_MENUNGGU_PEMBAYARAN }}"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Pembayaran</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600 group-hover:bg-amber-100 transition">
                    <i class="bi bi-hourglass-split"></i>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-amber-600">{{ $ringkasan['menungguBayar'] }}</p>
            <p class="mt-2 flex items-center gap-1 text-xs font-semibold text-amber-600 group-hover:underline">
                Lihat antrean pembayaran <i class="bi bi-arrow-right"></i>
            </p>
        </a>

        {{-- 4. Total Pengguna --}}
        <a href="/admin/pengguna"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pengguna Terdaftar</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-xl text-indigo-600 group-hover:bg-indigo-100 transition">
                    <i class="bi bi-people"></i>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-slate-800">{{ $ringkasan['totalPengguna'] }}</p>
            <p class="mt-2 flex items-center gap-1 text-xs font-semibold text-indigo-600 group-hover:underline">
                Kelola data pengguna <i class="bi bi-arrow-right"></i>
            </p>
        </a>
    </div>

    {{-- Grid: Keberangkatan Hari Ini & Pesanan Terbaru --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Keberangkatan Hari Ini --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-pink-50 text-lg text-[#c8175d]">
                        <i class="bi bi-compass"></i>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Keberangkatan Hari Ini</h2>
                        <p class="text-xs text-slate-400">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="/admin/rute" class="text-xs font-semibold text-[#c8175d] hover:underline">Semua Rute &rarr;</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500">
                        <tr>
                            <th class="px-3.5 py-3 font-semibold">Jam</th>
                            <th class="px-3.5 py-3 font-semibold">Rute Pelayaran</th>
                            <th class="px-3.5 py-3 font-semibold">Kapal</th>
                            <th class="px-3.5 py-3 text-center font-semibold">Penumpang Lunas</th>
                            <th class="px-3.5 py-3 text-center font-semibold">Sisa Kursi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($keberangkatan as $rute)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-3.5 py-3 font-bold text-slate-800">{{ substr($rute->Jam, 0, 5) }} WIB</td>
                                <td class="px-3.5 py-3 font-semibold text-slate-700">
                                    {{ $rute->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }}
                                    <i class="bi bi-arrow-right mx-1 text-slate-400"></i>
                                    {{ $rute->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}
                                </td>
                                <td class="px-3.5 py-3 text-slate-600">{{ $rute->kapal?->Nama_Kapal ?? '-' }}</td>
                                <td class="px-3.5 py-3 text-center font-semibold text-emerald-600">{{ (int) $rute->penumpang_lunas }}</td>
                                <td class="px-3.5 py-3 text-center">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {{ (int) $rute->sisa_kursi > 0 ? 'bg-sky-50 text-sky-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ (int) $rute->sisa_kursi }} kursi
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    <i class="bi bi-calendar-x text-2xl text-slate-300"></i>
                                    <p class="mt-1 text-xs">Tidak ada jadwal keberangkatan hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Pesanan Terbaru --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-pink-50 text-lg text-[#c8175d]">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Pesanan Terbaru</h2>
                        <p class="text-xs text-slate-400">Transaksi tiket masuk terakhir</p>
                    </div>
                </div>
                <a href="/admin/pesanan" class="text-xs font-semibold text-[#c8175d] hover:underline">Semua Pesanan &rarr;</a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500">
                        <tr>
                            <th class="px-3.5 py-3 font-semibold">Kode Booking</th>
                            <th class="px-3.5 py-3 font-semibold">Pemesan</th>
                            <th class="px-3.5 py-3 font-semibold">Rute</th>
                            <th class="px-3.5 py-3 font-semibold">Total</th>
                            <th class="px-3.5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pesananTerbaru as $p)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-3.5 py-3 font-mono font-semibold text-slate-800">#{{ $p->Kode_Booking ?? $p->ID_Pesanan }}</td>
                                <td class="px-3.5 py-3">
                                    <p class="font-semibold text-slate-800">{{ $p->user?->name ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $p->user?->email }}</p>
                                </td>
                                <td class="px-3.5 py-3 text-slate-600">
                                    {{ $p->rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $p->rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}
                                </td>
                                <td class="px-3.5 py-3 font-semibold text-slate-800">{{ rupiah($p->Total_Harga) }}</td>
                                <td class="px-3.5 py-3">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badge($p->Status) }}">
                                        {{ $p->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    <i class="bi bi-inbox text-2xl text-slate-300"></i>
                                    <p class="mt-1 text-xs">Belum ada pesanan terbaru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
