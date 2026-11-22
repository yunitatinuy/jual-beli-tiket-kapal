@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        $rute = $pesanan->rute;
        $kode = $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan;
        $s = Str::lower($pesanan->status_label ?? '');
        $batal = Str::contains($s, ['batal', 'expired', 'kadaluarsa', 'gagal']);
        $menunggu = !$batal && Str::contains($s, ['menunggu', 'pending', 'belum']);
        $lunas = !$batal && !$menunggu;

        $berangkat = $rute ? Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam))->locale('id') : null;
        $asal = $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-';
        $tujuan = $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-';
        $kodeAsal = strtoupper($rute?->pelabuhanAsal?->Kode_Pelabuhan ?? substr($asal, 0, 3));
        $kodeTujuan = strtoupper($rute?->pelabuhanTujuan?->Kode_Pelabuhan ?? substr($tujuan, 0, 3));
        $jamTiba = !empty($rute?->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
        $tglTiba = !empty($rute?->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('l, d M Y') : ($berangkat ? $berangkat->translatedFormat('l, d M Y') : '');
        $durasi = $rute->Durasi ?? 'Langsung';
        $batasCheckin = $berangkat?->copy()->subHours(2);

        $jumlahPenumpang = $pesanan->penumpangs->count();
        $dewasaCount = $pesanan->penumpangs->where('Tipe_Penumpang', 'dewasa')->count() ?: 1;
        $anakCount = $pesanan->penumpangs->where('Tipe_Penumpang', 'anak')->count() ?: 0;

        $created = $pesanan->created_at ? Carbon::parse($pesanan->created_at)->locale('id') : now()->locale('id');
        $dibayar = !empty($pesanan->Dibayar_Pada) ? Carbon::parse($pesanan->Dibayar_Pada)->locale('id') : ($lunas ? $created : null);

        // Masking helpers
        $maskPhone = function ($phone) {
            $digits = preg_replace('/\D/', '', (string) $phone);
            if (strlen($digits) < 7) return $phone ?: '-';
            $prefix = substr($digits, 0, 4);
            $suffix = substr($digits, -4);
            return '+62 ' . substr($prefix, 1) . '-****-' . $suffix;
        };

        $maskEmail = function ($email) {
            if (!$email || !str_contains($email, '@')) return '-';
            [$name, $domain] = explode('@', $email, 2);
            $len = strlen($name);
            $visible = min(4, max(1, $len - 3));
            return substr($name, 0, $visible) . '***@' . $domain;
        };

        $maskIdentitas = function ($val) {
            $digits = preg_replace('/\D/', '', (string) $val);
            if (strlen($digits) >= 4) {
                return 'No HP •••• ' . substr($digits, -4);
            }
            return $val ?: '-';
        };

        // Rincian harga
        $dewasaItem = collect($rincian ?? [])->firstWhere('tipe', 'Dewasa');
        $anakItem = collect($rincian ?? [])->firstWhere('tipe', 'Anak');
        $hargaDewasa = $dewasaItem['harga'] ?? ($pesanan->Total_Harga / max(1, $jumlahPenumpang));
        $subtotalDewasa = $dewasaItem['subtotal'] ?? ($hargaDewasa * $dewasaCount);
        $hargaAnak = $anakItem['harga'] ?? 0;
        $subtotalAnak = $anakItem['subtotal'] ?? 0;
        $biayaLayanan = 0;
    @endphp

    <style>
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>

    {{-- Breadcrumb --}}
    <nav class="no-print text-xs text-slate-500 dark:text-slate-400">
        <a href="/" class="hover:text-[#c8175d] dark:hover:text-pink-400">Beranda</a> <span class="mx-1">/</span>
        <a href="{{ route('transaksi') }}" class="hover:text-[#c8175d] dark:hover:text-pink-400">Tiket saya</a> <span class="mx-1">/</span>
        <span class="text-slate-800 dark:text-slate-200">E-tiket</span>
    </nav>

    {{-- Judul & Tombol Kembali --}}
    <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-slate-800 dark:text-slate-100">E-tiket perjalanan</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">#{{ $kode }} · Simpan e-tiket dan tunjukkan saat check-in di pelabuhan.</p>
        </div>
        <div class="no-print flex items-center gap-2">
            <a href="{{ route('transaksi') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">
                <i class="bi bi-arrow-left"></i> Kembali ke tiket saya
            </a>
            @if ($lunas)
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            @endif
        </div>
    </div>

    {{-- Konten Utama Grid 2 Kolom --}}
    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">

        {{-- Kolom Kiri --}}
        <div class="flex flex-col gap-5">

            {{-- 1. Card E-Tiket Utama --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-7 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 pb-5 dark:border-slate-800">
                    <div class="flex items-center gap-3.5">
                        <img src="/img/logo3.png" alt="Anang Ferry" class="h-10 w-10 object-contain">
                        <div>
                            <p class="text-lg font-bold uppercase text-[#c8175d] dark:text-pink-400">Anang Ferry</p>
                            <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">E-tiket kapal laut</p>
                        </div>
                    </div>
                    @if ($lunas)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                            <i class="bi bi-check2"></i> {{ $pesanan->status_label }}
                        </span>
                    @elseif ($menunggu)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                            <i class="bi bi-clock"></i> {{ $pesanan->status_label }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                            {{ $pesanan->status_label }}
                        </span>
                    @endif
                </div>

                {{-- Ringkasan Kode, Kapal, Kelas --}}
                <div class="mt-5 flex flex-wrap justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Kode pesanan</p>
                        <p class="mt-1 font-mono text-sm font-bold text-slate-800 dark:text-slate-100">#{{ $kode }}</p>
                    </div>
                    <div class="flex gap-6 sm:gap-8">
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Nama Kapal</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-1.5">
                                <i class="bi bi-tsunami text-[#c8175d] dark:text-pink-400"></i> {{ $rute?->kapal?->Nama_Kapal ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Kelas</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $pesanan->Kelas ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Jadwal Perjalanan --}}
                <div class="mt-5 grid grid-cols-1 items-center gap-4 rounded-xl bg-slate-50 p-6 sm:grid-cols-[1fr_auto_1fr] dark:bg-slate-800/60">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ strtoupper($asal) }} ({{ $kodeAsal }})</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $berangkat?->format('H.i') }} WIB</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">{{ $berangkat?->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <div class="text-center text-[11px] text-slate-400 dark:text-slate-500">
                        {{ $durasi }}
                        <div class="my-1.5 flex w-28 items-center gap-1 mx-auto">
                            <span class="h-1.5 w-1.5 rounded-full border border-slate-300 dark:border-slate-600"></span>
                            <span class="flex-1 border-t border-dashed border-slate-300 dark:border-slate-600"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-[#c8175d] dark:bg-pink-400"></span>
                        </div>
                        Langsung
                    </div>
                    <div class="sm:text-right">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ strtoupper($tujuan) }} ({{ $kodeTujuan }})</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $jamTiba }}</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">{{ $tglTiba }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">
                    <i class="bi bi-clock text-slate-400"></i>
                    Tiba di pelabuhan paling lambat {{ $berangkat?->translatedFormat('d M Y') }}, pukul {{ $batasCheckin?->format('H.i') }} WIB (2 jam sebelum keberangkatan).
                </div>
            </section>

            {{-- 2. Data Penumpang --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Daftar penumpang</h3>
                    <span class="text-xs text-slate-500 font-medium dark:text-slate-400">
                        {{ $dewasaCount }} dewasa{{ $anakCount > 0 ? ', ' . $anakCount . ' anak' : '' }}
                    </span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3.5 py-3 font-semibold">#</th>
                                <th class="px-3.5 py-3 font-semibold">Nama Lengkap</th>
                                <th class="px-3.5 py-3 font-semibold">Identitas</th>
                                <th class="px-3.5 py-3 font-semibold">Kategori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                            @foreach ($pesanan->penumpangs as $i => $pen)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-3.5 py-3 font-mono text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-3.5 py-3 font-semibold text-slate-800 dark:text-slate-200">{{ $pen->Nama_Lengkap }}</td>
                                    <td class="px-3.5 py-3 text-slate-600 dark:text-slate-400">{{ $maskIdentitas($pen->Telepon) }}</td>
                                    <td class="px-3.5 py-3">
                                        <span class="rounded-md bg-pink-50 px-2 py-0.5 text-[11px] font-medium text-[#c8175d] dark:bg-pink-950/60 dark:text-pink-300">
                                            {{ ucfirst($pen->Tipe_Penumpang ?? 'Dewasa') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 rounded-xl bg-slate-50 p-3.5 text-xs text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                    <i class="bi bi-info-circle text-[#c8175d] mr-1"></i> Nama penumpang harus sesuai dengan identitas resmi (No HP/Paspor/SIM) yang dibawa saat verifikasi check-in di pelabuhan.
                </p>
            </section>

            {{-- 3. Informasi Pemesan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Informasi pemesan</h3>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3 text-xs">
                    <div>
                        <span class="block text-slate-400 font-medium dark:text-slate-500">Nama pemesan</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $pesanan->user->name ?? $pesanan->penumpangs->first()?->Nama_Lengkap }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-medium dark:text-slate-500">Email</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $maskEmail($pesanan->user->email ?? '') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-medium dark:text-slate-500">Nomor telepon</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $maskPhone($pesanan->penumpangs->first()?->Telepon ?? '') }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- 4. Petunjuk Sebelum Berangkat --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Sebelum berangkat</h3>
                <ol class="mt-4 space-y-3.5 text-sm text-slate-600 dark:text-slate-300">
                    <li class="flex gap-4">
                        <span class="text-xs font-bold text-[#c8175d] dark:text-pink-400">01</span>
                        <span>Tiba di pelabuhan paling lambat pukul <strong>{{ $batasCheckin?->format('H.i') }} WIB</strong> (2 jam sebelum keberangkatan).</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-xs font-bold text-[#c8175d] dark:text-pink-400">02</span>
                        <span>Tunjukkan e-tiket dan identitas asli (No HP/Paspor) kepada petugas untuk proses verifikasi tiket.</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-xs font-bold text-[#c8175d] dark:text-pink-400">03</span>
                        <span>E-tiket ini bukan boarding pass fisik. Ikuti arahan petugas loket untuk proses masuk kapal.</span>
                    </li>
                </ol>
            </section>

            {{-- 5. Perubahan atau Pembatalan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Perubahan atau pembatalan perjalanan</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                    Ketentuan pembatalan tiket mengikuti kebijakan resmi operator pelayaran ANANG SHIP. Hubungi bantuan jika ada kendala perjalanan atau perubahan data.
                </p>
            </section>
        </div>

        {{-- Kolom Kanan (Sidebar) --}}
        <aside class="flex flex-col gap-5">

            {{-- Card QR Code & Kode Booking --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Kode pemesanan</h3>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Tunjukkan barcode ini kepada petugas saat check-in di pelabuhan.</p>
                <div id="qrcode" class="mx-auto my-5 flex justify-center p-3 bg-white rounded-2xl border border-slate-100 shadow-inner w-fit"></div>
                <p class="text-2xl font-mono font-bold tracking-wider text-slate-800 dark:text-slate-100">{{ $kode }}</p>

                @if ($lunas)
                    <a href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}"
                        class="no-print mt-6 block w-full rounded-xl bg-[#c8175d] py-3 text-xs font-semibold text-white transition hover:bg-[#a6104b] shadow-sm">
                        <i class="bi bi-file-earmark-pdf mr-1"></i> Unduh e-tiket (PDF)
                    </a>
                @elseif ($menunggu)
                    <a href="{{ route('invoices.show', $pesanan->ID_Pesanan) }}"
                        class="no-print mt-6 block w-full rounded-xl bg-[#c8175d] py-3 text-xs font-semibold text-white transition hover:bg-[#a6104b] shadow-sm">
                        <i class="bi bi-wallet2 mr-1"></i> Bayar sekarang
                    </a>
                @endif
                <p class="no-print mt-3 text-[11px] text-slate-400 dark:text-slate-500">Unduh berkas PDF agar dapat dibuka atau ditunjukkan tanpa jaringan internet.</p>
            </section>

            {{-- Rincian Pembayaran --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Rincian pembayaran</h3>
                    @if ($lunas)
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                            <i class="bi bi-check2"></i> Lunas
                        </span>
                    @elseif ($menunggu)
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
                            <i class="bi bi-clock"></i> Belum lunas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                            Dibatalkan
                        </span>
                    @endif
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                        <div>
                            <span>Tiket dewasa × {{ $dewasaCount }}</span>
                            <span class="block text-[11px] text-slate-400">{{ rupiah($hargaDewasa) }} / penumpang</span>
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ rupiah($subtotalDewasa) }}</span>
                    </div>

                    @if ($anakCount > 0)
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <div>
                                <span>Tiket anak × {{ $anakCount }}</span>
                                <span class="block text-[11px] text-slate-400">{{ rupiah($hargaAnak) }} / penumpang</span>
                            </div>
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ rupiah($subtotalAnak) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600 dark:text-slate-300 border-t border-slate-100 pt-3 dark:border-slate-800">
                        <span>Biaya layanan</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $biayaLayanan > 0 ? rupiah($biayaLayanan) : 'Gratis' }}</span>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-4 dark:border-slate-800">
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Total pembayaran</span>
                        <span class="text-2xl font-bold text-[#c8175d] dark:text-pink-400">{{ rupiah($pesanan->Total_Harga) }}</span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-2 text-xs dark:border-slate-800">
                        <div class="flex justify-between">
                            <span class="text-slate-400 dark:text-slate-500">Metode pembayaran</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $pesanan->Metode_Bayar ?? ($pesanan->Metode_Pembayaran ?? 'Midtrans') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 dark:text-slate-500">Waktu pembayaran</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Belum dibayar' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Riwayat Pesanan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Riwayat pesanan</h3>
                <div class="mt-4 space-y-4">
                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">
                            <i class="bi bi-check2"></i>
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold text-slate-800 dark:text-slate-200">Pesanan dibuat</p>
                            <p class="text-slate-400">{{ $created->translatedFormat('d M Y, H.i') }} WIB</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $lunas ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }} text-xs">
                            @if ($lunas) <i class="bi bi-check2"></i> @else <i class="bi bi-circle"></i> @endif
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold {{ $lunas ? 'text-slate-800 dark:text-slate-200' : 'text-slate-500' }}">Pembayaran berhasil</p>
                            <p class="text-slate-400">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Menunggu pembayaran' }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $lunas ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-800' }} text-xs">
                            @if ($lunas) <i class="bi bi-check2"></i> @else <i class="bi bi-circle"></i> @endif
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold {{ $lunas ? 'text-slate-800 dark:text-slate-200' : 'text-slate-500' }}">E-tiket diterbitkan</p>
                            <p class="text-slate-400">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Setelah pembayaran' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Bantuan Pesanan --}}
            <section class="rounded-2xl border border-pink-100 bg-pink-50/70 p-6 dark:border-slate-800 dark:bg-pink-950/20">
                <h3 class="font-semibold text-[#c8175d] dark:text-pink-400">Perlu bantuan pesanan?</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                    Sertakan kode booking <span class="font-bold text-slate-800 dark:text-slate-200">#{{ $kode }}</span> agar tim bantuan kami dapat melayani Anda lebih cepat.
                </p>
                <a href="/informasi"
                    class="mt-4 block rounded-lg border border-slate-200 bg-white py-2.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    Hubungi bantuan
                </a>
            </section>
        </aside>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('qrcode');
            if (el) {
                new QRCode(el, {
                    text: @json((string) $kode),
                    width: 176,
                    height: 176,
                    colorDark: '#1d1d1f',
                    colorLight: '#ffffff',
                });
            }
        });
    </script>
@endsection
