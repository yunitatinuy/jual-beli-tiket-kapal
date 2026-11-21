@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;

        $rute = $pesanan->rute;
        $kode = $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan;
        $berangkat = $rute ? Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam))->locale('id') : null;
        $asal = $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-';
        $tujuan = $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-';
        $jamTiba = !empty($rute?->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
        $tglTiba = !empty($rute?->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('l, d M Y') : '';
        $batasCheckin = $berangkat?->copy()->subHours(2);
    @endphp

    <style>
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>

    <nav class="no-print text-xs text-slate-500">
        <a href="/transaksi" class="hover:text-[#c8175d]">Tiket saya</a> <span class="mx-1">/</span>
        <a href="{{ route('pesanan.detail', $pesanan->ID_Pesanan) }}" class="hover:text-[#c8175d]">Detail pesanan</a> <span class="mx-1">/</span>
        <span>E-tiket</span>
    </nav>

    <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-slate-800">E-tiket perjalanan</h1>
            <p class="mt-1 text-sm text-slate-500">Simpan e-tiket dan tunjukkan saat check-in di pelabuhan.</p>
        </div>
        <a href="{{ route('pesanan.detail', $pesanan->ID_Pesanan) }}"
            class="no-print rounded-lg border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-[#c8175d] hover:bg-pink-50">Kembali ke detail pesanan</a>
    </div>

    <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">

        <div class="flex flex-col gap-5">
            <section class="rounded-2xl border border-slate-200 bg-white p-7">
                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                    <div class="flex items-center gap-4">
                        <img src="/img/logo3.png" alt="Anang Ferry" class="h-11 w-11 object-contain">
                        <div>
                            <p class="text-lg font-bold uppercase text-[#c8175d]">Anang Ferry</p>
                            <p class="text-[10px] font-medium uppercase tracking-wider text-slate-500">E-tiket kapal laut</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">
                        <i class="bi bi-check2"></i> {{ $pesanan->status_label }}
                    </span>
                </div>

                <div class="mt-5 flex flex-wrap justify-between gap-4">
                    <div><p class="text-xs text-slate-500">Kode pesanan</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $kode }}</p></div>
                    <div class="flex gap-8">
                        <div><p class="text-xs text-slate-500">Kapal</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $rute?->kapal?->Nama_Kapal ?? '-' }}</p></div>
                        <div><p class="text-xs text-slate-500">Kelas</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $pesanan->Kelas ?? '-' }}</p></div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 items-center gap-4 rounded-xl bg-slate-50 p-6 sm:grid-cols-[1fr_auto_1fr]">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ strtoupper($asal) }} ({{ strtoupper($rute?->pelabuhanAsal?->Kode_Pelabuhan ?? substr($asal, 0, 3)) }})</p>
                        <p class="mt-1 text-3xl font-semibold text-slate-800">{{ $berangkat?->format('H.i') }} WIB</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $berangkat?->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <div class="text-center text-[11px] text-slate-400">
                        {{ $rute->Durasi ?? '' }}
                        <div class="my-1 flex w-28 items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full border border-slate-300"></span>
                            <span class="flex-1 border-t border-dashed border-slate-300"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-[#c8175d]"></span>
                        </div>
                        Langsung
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ strtoupper($tujuan) }} ({{ strtoupper($rute?->pelabuhanTujuan?->Kode_Pelabuhan ?? substr($tujuan, 0, 3)) }})</p>
                        <p class="mt-1 text-3xl font-semibold text-slate-800">{{ $jamTiba }}</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $tglTiba }}</p>
                    </div>
                </div>

                <h3 class="mt-7 text-lg font-semibold text-slate-800">Daftar penumpang</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] text-slate-500">
                            <tr>
                                <th class="px-3 py-3 font-medium">Penumpang</th>
                                <th class="px-3 py-3 font-medium">Identitas</th>
                                <th class="px-3 py-3 font-medium">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->penumpangs as $pen)
                                @php
                                    $digits = preg_replace('/\D/', '', (string) $pen->Telepon);
                                    $identitas = strlen($digits) >= 4 ? 'KTP •••• ' . substr($digits, -4) : ($pen->Telepon ?: '-');
                                @endphp
                                <tr class="border-t border-slate-100">
                                    <td class="px-3 py-3 font-semibold text-slate-800">{{ $pen->Nama_Lengkap }}</td>
                                    <td class="px-3 py-3 text-slate-600">{{ $identitas }}</td>
                                    <td class="px-3 py-3 text-slate-600">{{ ucfirst($pen->Tipe_Penumpang ?? 'Dewasa') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-xs font-medium text-green-700">
                    {{ $pesanan->penumpangs->count() }} penumpang terdaftar · Bawa identitas asli sesuai nama di e-tiket.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-lg font-semibold text-slate-800">Sebelum berangkat</h3>
                <ol class="mt-4 space-y-4 text-sm text-slate-600">
                    <li class="flex gap-4"><span class="text-xs font-semibold text-[#c8175d]">01</span>
                        Tiba di pelabuhan paling lambat pukul {{ $batasCheckin?->format('H.i') }} WIB (2 jam sebelum berangkat).</li>
                    <li class="flex gap-4"><span class="text-xs font-semibold text-[#c8175d]">02</span>
                        Tunjukkan e-tiket dan identitas asli kepada petugas untuk proses check-in.</li>
                    <li class="flex gap-4"><span class="text-xs font-semibold text-[#c8175d]">03</span>
                        E-tiket bukan boarding pass. Ikuti arahan petugas untuk proses naik kapal.</li>
                </ol>
            </section>
        </div>

        <aside class="flex flex-col gap-5">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
                <h3 class="text-lg font-semibold text-slate-800">Kode pemesanan</h3>
                <p class="mt-4 text-xs text-slate-500">Tunjukkan kepada petugas check-in.</p>
                <div id="qrcode" class="mx-auto my-6 flex justify-center"></div>
                <p class="text-xl font-semibold tracking-wide text-slate-800">{{ $kode }}</p>
                <a href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}"
                    class="no-print mt-6 block w-full rounded-lg bg-[#c8175d] py-3 text-xs font-semibold text-white hover:bg-[#a6104b]">Unduh e-tiket (PDF)</a>
                <p class="no-print mt-3 text-[11px] text-slate-500">Unduh berkas PDF agar dapat dibuka atau ditunjukkan tanpa internet.</p>
            </section>

            <section class="no-print rounded-2xl bg-pink-100/70 p-6">
                <h3 class="font-semibold text-[#c8175d]">Check-in di pelabuhan</h3>
                <p class="mt-3 text-sm text-slate-700">{{ $berangkat?->translatedFormat('l, d M Y') }}</p>
                <p class="mt-3 text-lg font-semibold text-[#c8175d]">Paling lambat {{ $batasCheckin?->format('H.i') }} WIB</p>
                <p class="mt-3 text-sm text-slate-500">Siapkan e-tiket dan identitas {{ $pesanan->penumpangs->count() > 1 ? 'semua penumpang' : 'penumpang' }} sebelum menuju loket.</p>
            </section>

            <section class="no-print rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="font-semibold text-slate-800">Butuh bantuan?</h3>
                <p class="mt-3 text-sm text-slate-500">Hubungi bantuan jika data tiket tidak sesuai dengan pesananmu.</p>
                <a href="/informasi" class="mt-4 block rounded-lg border border-slate-200 py-3 text-center text-xs font-semibold text-[#c8175d] hover:bg-pink-50">Hubungi bantuan</a>
            </section>
        </aside>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById('qrcode'), {
            text: @json((string) $kode),
            width: 176,
            height: 176,
            colorDark: '#1d1d1f',
            colorLight: '#ffffff',
        });
    </script>
@endsection