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

        $asal = $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-';
        $tujuan = $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-';
        $kodeAsal = strtoupper($rute?->pelabuhanAsal?->Kode_Pelabuhan ?? substr($asal, 0, 3));
        $kodeTujuan = strtoupper($rute?->pelabuhanTujuan?->Kode_Pelabuhan ?? substr($tujuan, 0, 3));

        $berangkat = $rute ? Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam))->locale('id') : null;
        $jamTiba = !empty($rute?->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
        $tglTiba = !empty($rute?->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('l, d M Y') : ($berangkat ? $berangkat->translatedFormat('l, d M Y') : '-');
        $durasi = $rute->Durasi ?? '23 jam';

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
        $dewasaItem = $rincian->firstWhere('tipe', 'Dewasa');
        $anakItem = $rincian->firstWhere('tipe', 'Anak');
        $hargaDewasa = $dewasaItem['harga'] ?? ($pesanan->Total_Harga / max(1, $jumlahPenumpang));
        $subtotalDewasa = $dewasaItem['subtotal'] ?? ($hargaDewasa * $dewasaCount);
        $hargaAnak = $anakItem['harga'] ?? 0;
        $subtotalAnak = $anakItem['subtotal'] ?? 0;
        $biayaLayanan = 0;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-xs text-slate-500">
        <a href="{{ route('transaksi') }}" class="hover:text-[#c8175d]">Tiket saya</a> <span class="mx-1">/</span>
        <span class="text-slate-800">Detail pesanan</span>
    </nav>

    {{-- Judul Halaman & Navigasi Kembali --}}
    <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-slate-800">Detail pesanan</h1>
            <p class="mt-1 text-sm text-slate-500">#{{ $kode }} · Dipesan pada {{ $created->translatedFormat('d M Y, H.i') }} WIB</p>
        </div>
        <a href="{{ route('transaksi') }}"
            class="rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-[#c8175d] transition hover:bg-pink-50">
            Kembali ke tiket saya
        </a>
    </div>

    {{-- Konten Utama Grid 2 Kolom --}}
    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">

        {{-- Kolom Kiri --}}
        <div class="flex flex-col gap-5">

            {{-- 1. Status Banner --}}
            @if ($lunas)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-green-200 bg-green-50/80 p-5">
                    <div class="flex items-center gap-3.5">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white">
                            <i class="bi bi-check2"></i>
                        </span>
                        <div>
                            <p class="text-base font-semibold text-green-800">Pesanan terkonfirmasi</p>
                            <p class="mt-0.5 text-xs text-green-700">Pembayaran berhasil. E-tiket untuk {{ $jumlahPenumpang }} penumpang sudah terbit.</p>
                        </div>
                    </div>
                    <a href="{{ route('invoices.eticket', $pesanan->ID_Pesanan) }}"
                        class="rounded-lg bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-[#a6104b]">
                        Lihat e-tiket
                    </a>
                </div>
            @elseif ($menunggu)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50/80 p-5">
                    <div class="flex items-center gap-3.5">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-white">
                            <i class="bi bi-clock"></i>
                        </span>
                        <div>
                            <p class="text-base font-semibold text-amber-800">Menunggu pembayaran</p>
                            <p class="mt-0.5 text-xs text-amber-700">Selesaikan pembayaran sebelum batas waktu agar e-tiket dapat diterbitkan.</p>
                        </div>
                    </div>
                    <a href="{{ route('invoices.show', $pesanan->ID_Pesanan) }}"
                        class="rounded-lg bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-[#a6104b]">
                        Bayar sekarang
                    </a>
                </div>
            @else
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-red-200 bg-red-50 p-5">
                    <div class="flex items-center gap-3.5">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white">
                            <i class="bi bi-x"></i>
                        </span>
                        <div>
                            <p class="text-base font-semibold text-red-800">Pesanan dibatalkan</p>
                            <p class="mt-0.5 text-xs text-red-700">Pesanan ini telah kedaluwarsa atau dibatalkan.</p>
                        </div>
                    </div>
                    <a href="/" class="rounded-lg bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white hover:bg-[#a6104b]">Pesan baru</a>
                </div>
            @endif

            {{-- 2. Card Rute & Kapal --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="flex items-center gap-2 text-base font-bold text-slate-800">
                            <i class="bi bi-tsunami text-lg text-[#c8175d]"></i> {{ $rute?->kapal?->Nama_Kapal ?? '-' }}
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $asal }} → {{ $tujuan }} · Langsung</p>
                    </div>
                    <span class="rounded-md bg-pink-50 px-3 py-1 text-xs font-semibold text-[#c8175d]">
                        {{ $pesanan->Kelas ?? 'Ekonomi' }} · {{ $jumlahPenumpang }} penumpang
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-1 items-center gap-4 rounded-xl bg-slate-50 p-6 sm:grid-cols-[1fr_auto_1fr]">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ strtoupper($asal) }} ({{ $kodeAsal }})</p>
                        <p class="mt-1 text-2xl font-bold text-slate-800">{{ $berangkat?->format('H.i') }} WIB</p>
                        <p class="mt-1 text-xs text-slate-600">{{ $berangkat?->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <div class="text-center text-[11px] text-slate-400">
                        {{ $durasi }}
                        <div class="my-1.5 flex w-28 items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full border border-slate-300"></span>
                            <span class="flex-1 border-t border-dashed border-slate-300"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-[#c8175d]"></span>
                        </div>
                        Langsung
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ strtoupper($tujuan) }} ({{ $kodeTujuan }})</p>
                        <p class="mt-1 text-2xl font-bold text-slate-800">{{ $jamTiba }}</p>
                        <p class="mt-1 text-xs text-slate-600">{{ $tglTiba }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                    <i class="bi bi-clock text-slate-400"></i>
                    Tiba di pelabuhan paling lambat {{ $berangkat?->translatedFormat('d M Y') }}, pukul {{ $batasCheckin?->format('H.i') }} WIB.
                </div>
            </section>

            {{-- 3. Data Penumpang --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-semibold text-slate-800">Data penumpang</h3>
                    <span class="text-xs text-slate-500 font-medium">
                        {{ $dewasaCount }} dewasa{{ $anakCount > 0 ? ', ' . $anakCount . ' anak' : '' }}
                    </span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-3 py-3 font-medium">Penumpang</th>
                                <th class="px-3 py-3 font-medium">Identitas</th>
                                <th class="px-3 py-3 font-medium">Kategori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pesanan->penumpangs as $pen)
                                <tr>
                                    <td class="px-3 py-3.5 font-semibold text-slate-800">{{ $pen->Nama_Lengkap }}</td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ $maskIdentitas($pen->Telepon) }}</td>
                                    <td class="px-3 py-3.5 text-slate-600">{{ ucfirst($pen->Tipe_Penumpang ?? 'Dewasa') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="mt-4 text-xs text-slate-400">
                    Nama penumpang harus sesuai dengan identitas asli yang dibawa saat check-in.
                </p>
            </section>

            {{-- 4. Informasi Pemesan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">Informasi pemesan</h3>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3 text-xs">
                    <div>
                        <span class="block text-slate-400 font-medium">Nama pemesan</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800">
                            {{ $pesanan->user->name ?? $pesanan->penumpangs->first()?->Nama_Lengkap }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-medium">Email</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800">
                            {{ $maskEmail($pesanan->user->email ?? '') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 font-medium">Nomor telepon</span>
                        <span class="mt-1 block text-sm font-semibold text-slate-800">
                            {{ $maskPhone($pesanan->penumpangs->first()?->Telepon ?? '') }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- 5. Perubahan atau Pembatalan Perjalanan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">Perubahan atau pembatalan perjalanan</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">
                    Ketentuan mengikuti kebijakan operator kapal. Hubungi bantuan untuk mengetahui opsi yang tersedia pada pesanan ini.
                </p>
            </section>
        </div>

        {{-- Kolom Kanan (Sidebar) --}}
        <aside class="flex flex-col gap-5">

            {{-- Rincian Pembayaran --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-base font-semibold text-slate-800">Rincian pembayaran</h3>
                    @if ($lunas)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                            <i class="bi bi-check2"></i> Lunas
                        </span>
                    @elseif ($menunggu)
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                            <i class="bi bi-clock"></i> Belum lunas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            Dibatalkan
                        </span>
                    @endif
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <div>
                            <span>Tiket dewasa × {{ $dewasaCount }}</span>
                            <span class="block text-[11px] text-slate-400">{{ rupiah($hargaDewasa) }} / penumpang</span>
                        </div>
                        <span class="font-semibold text-slate-800">{{ rupiah($subtotalDewasa) }}</span>
                    </div>

                    @if ($anakCount > 0)
                        <div class="flex justify-between text-slate-600">
                            <div>
                                <span>Tiket anak × {{ $anakCount }}</span>
                                <span class="block text-[11px] text-slate-400">{{ rupiah($hargaAnak) }} / penumpang</span>
                            </div>
                            <span class="font-semibold text-slate-800">{{ rupiah($subtotalAnak) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600 border-t border-slate-100 pt-3">
                        <span>Biaya layanan</span>
                        <span class="font-semibold text-slate-800">{{ $biayaLayanan > 0 ? rupiah($biayaLayanan) : 'Gratis' }}</span>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="text-xs font-medium text-slate-500">Total pembayaran</span>
                        <span class="text-2xl font-bold text-[#c8175d]">{{ rupiah($pesanan->Total_Harga) }}</span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Metode pembayaran</span>
                            <span class="font-semibold text-slate-700">{{ $pesanan->Metode_Bayar ?? ($pesanan->Metode_Pembayaran ?? 'Transfer bank / Midtrans') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Waktu pembayaran</span>
                            <span class="font-semibold text-slate-700">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Belum dibayar' }}</span>
                        </div>
                    </div>
                </div>

                @if ($lunas)
                    <a href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}"
                        class="mt-5 block w-full rounded-lg border border-slate-200 bg-white py-3 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                        <i class="bi bi-file-earmark-pdf mr-1 text-slate-400"></i> Unduh bukti pembayaran
                    </a>
                @endif
            </section>

            {{-- Riwayat Pesanan --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-800">Riwayat pesanan</h3>
                <div class="mt-4 space-y-4">
                    {{-- 1. Dibuat --}}
                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-green-100 text-xs text-green-700">
                            <i class="bi bi-check2"></i>
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold text-slate-800">Pesanan dibuat</p>
                            <p class="text-slate-400">{{ $created->translatedFormat('d M Y, H.i') }} WIB</p>
                        </div>
                    </div>

                    {{-- 2. Bayar --}}
                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $lunas ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }} text-xs">
                            @if ($lunas) <i class="bi bi-check2"></i> @else <i class="bi bi-circle"></i> @endif
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold {{ $lunas ? 'text-slate-800' : 'text-slate-500' }}">Pembayaran berhasil</p>
                            <p class="text-slate-400">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Menunggu pembayaran' }}</p>
                        </div>
                    </div>

                    {{-- 3. E-tiket --}}
                    <div class="flex gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $lunas ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }} text-xs">
                            @if ($lunas) <i class="bi bi-check2"></i> @else <i class="bi bi-circle"></i> @endif
                        </span>
                        <div class="text-xs">
                            <p class="font-semibold {{ $lunas ? 'text-slate-800' : 'text-slate-500' }}">E-tiket diterbitkan</p>
                            <p class="text-slate-400">{{ $dibayar ? $dibayar->translatedFormat('d M Y, H.i') . ' WIB' : 'Setelah pembayaran' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Perlu Bantuan Pesanan --}}
            <section class="rounded-2xl border border-pink-100 bg-pink-50/70 p-6">
                <h3 class="font-semibold text-[#c8175d]">Perlu bantuan pesanan?</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-600">
                    Sertakan kode <span class="font-bold text-slate-800">#{{ $kode }}</span> agar tim bantuan dapat membantu lebih cepat.
                </p>
                <a href="/informasi"
                    class="mt-4 block rounded-lg border border-slate-200 bg-white py-2.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                    Hubungi bantuan
                </a>
            </section>
        </aside>
    </div>
@endsection
