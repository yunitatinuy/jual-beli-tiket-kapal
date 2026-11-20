@extends('layout.main')

@section('container')
    @php
        use App\Models\Pesanan;
        use Carbon\Carbon;

        $rute = $pesanan->rute;
        $asal = $rute?->pelabuhanAsal;
        $tujuan = $rute?->pelabuhanTujuan;
        $berangkat = $rute ? Carbon::parse($rute->Tanggal . ' ' . $rute->Jam) : null;
        $kode = $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan;

        $statusStyle = match ($pesanan->Status) {
            Pesanan::STATUS_LUNAS => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            Pesanan::STATUS_DIBATALKAN => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            default => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        };
    @endphp

    <style>
        @media print {
            nav, footer, .no-print { display: none !important; }
            body { background: #fff !important; }
            .print-flat { box-shadow: none !important; border: 0 !important; }
            @page { margin: 12mm; }
        }
    </style>

    <div class="min-h-screen px-4 py-8 bg-slate-50 sm:py-12 font-inter">
        <div class="max-w-4xl mx-auto">

            {{-- Notifikasi --}}
            @foreach ([
                'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                'info' => 'bg-sky-50 border-sky-200 text-sky-800',
                'error' => 'bg-rose-50 border-rose-200 text-rose-800',
            ] as $key => $gaya)
                @if (session($key))
                    <div class="no-print flex items-start gap-3 p-4 mb-4 text-sm rounded-xl border {{ $gaya }}" role="alert">
                        <span class="font-medium">{{ session($key) }}</span>
                    </div>
                @endif
            @endforeach

            @include('invoices.partials.pembayaran')

            {{-- Kartu invoice --}}
            <article class="relative overflow-hidden bg-white border shadow-sm print-flat rounded-2xl border-slate-200">

                {{-- Kepala --}}
                <header class="flex flex-col gap-6 p-6 border-b sm:flex-row sm:items-start sm:justify-between sm:p-8 border-slate-100">
                    <div class="flex items-center gap-4">
                        <img src="/img/logo3.png" alt="Anang Ferry" class="w-14 h-14">
                        <div>
                            <p class="text-xl text-slate-900 font-marko">Anang Ferry</p>
                            <p class="text-xs leading-relaxed text-slate-500">anangferry@gmail.com &middot; 0896-6969-696<br>Batam, Kepulauan Riau</p>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs font-semibold tracking-widest uppercase text-slate-400">Invoice / E-Tiket</p>
                        <p class="mt-1 font-mono text-2xl font-bold tracking-wide text-slate-900">{{ $kode }}</p>
                        <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 ring-inset {{ $statusStyle }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $pesanan->status_label }}
                        </span>
                    </div>
                </header>

                {{-- Info pemesan & tanggal --}}
                <section class="grid gap-6 p-6 text-sm sm:grid-cols-3 sm:p-8">
                    <div>
                        <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Ditagihkan kepada</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $pesanan->user->name }}</p>
                        <p class="break-all text-slate-500">{{ $pesanan->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Tanggal pemesanan</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ Carbon::parse($pesanan->Tanggal_Pesanan)->translatedFormat('d M Y') }}</p>
                        <p class="text-slate-500">Pukul {{ substr((string) $pesanan->Waktu, 0, 5) }} WIB</p>
                    </div>
                    <div>
                        @if ($pesanan->Status === Pesanan::STATUS_LUNAS)
                            <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Dibayar</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $pesanan->Dibayar_Pada?->translatedFormat('d M Y, H:i') }} WIB</p>
                            <p class="text-slate-500">{{ $pesanan->Metode_Bayar }}</p>
                        @elseif ($pesanan->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
                            <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Batas pembayaran</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $pesanan->Batas_Bayar?->translatedFormat('d M Y, H:i') ?? '-' }} WIB</p>
                            <p class="text-slate-500">Melalui Midtrans</p>
                        @else
                            <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Status</p>
                            <p class="mt-1 font-semibold text-rose-700">Dibatalkan</p>
                            <p class="text-slate-500">Tiket tidak berlaku</p>
                        @endif
                    </div>
                </section>

                {{-- Perjalanan --}}
                <section class="px-6 sm:px-8">
                    <div class="overflow-hidden border rounded-xl border-slate-200">
                        <div class="flex items-center justify-between px-5 py-3 text-xs font-semibold tracking-wide uppercase bg-slate-50 text-slate-500">
                            <span>Detail perjalanan</span>
                            <span>Kelas {{ $pesanan->Kelas ?? '-' }}</span>
                        </div>
                        <div class="grid items-center grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] gap-2 sm:gap-3 px-4 sm:px-5 py-6 [overflow-wrap:anywhere]">
                            <div>
                                <p class="text-xs text-slate-400">Dari</p>
                                <p class="text-lg font-bold leading-tight text-slate-900">{{ $asal?->Nama_Pelabuhan ?? '-' }}</p>
                                <p class="text-sm text-slate-500">{{ $asal?->Nama_Kota }}</p>
                            </div>
                            <div class="flex flex-col items-center px-1 sm:px-2 text-slate-400">
                                <svg class="w-7 h-7 text-[#f49855]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 20c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 4.5 0 3-1 4.5 0"/>
                                    <path d="M4 17l-1-5h18l-2 5"/>
                                    <path d="M12 12V4l5 3-5 2"/>
                                </svg>
                                <span class="w-8 mt-1 border-t border-dashed sm:w-28 border-slate-300"></span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400">Ke</p>
                                <p class="text-lg font-bold leading-tight text-slate-900">{{ $tujuan?->Nama_Pelabuhan ?? '-' }}</p>
                                <p class="text-sm text-slate-500">{{ $tujuan?->Nama_Kota }}</p>
                            </div>
                        </div>
                        <dl class="grid grid-cols-2 text-sm border-t sm:grid-cols-4 border-slate-200 divide-slate-200 sm:divide-x">
                            <div class="px-5 py-3">
                                <dt class="text-xs text-slate-400">Tanggal</dt>
                                <dd class="font-semibold text-slate-900">{{ $berangkat?->translatedFormat('D, d M Y') ?? '-' }}</dd>
                            </div>
                            <div class="px-5 py-3">
                                <dt class="text-xs text-slate-400">Jam berangkat</dt>
                                <dd class="font-semibold text-slate-900">{{ $berangkat?->format('H:i') ?? '-' }} WIB</dd>
                            </div>
                            <div class="px-5 py-3">
                                <dt class="text-xs text-slate-400">Kapal</dt>
                                <dd class="font-semibold text-slate-900">{{ $rute?->kapal?->Nama_Kapal ?? '-' }}</dd>
                            </div>
                            <div class="px-5 py-3">
                                <dt class="text-xs text-slate-400">Penumpang</dt>
                                <dd class="font-semibold text-slate-900">{{ $pesanan->penumpangs->count() }} orang</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                {{-- QR boarding --}}
                @if ($qr)
                    <section class="px-6 mt-6 sm:px-8">
                        <div class="flex flex-col items-center gap-5 p-5 border sm:flex-row rounded-xl border-emerald-200 bg-emerald-50/50">
                            <img src="{{ $qr }}" alt="QR code check-in {{ $kode }}" class="w-40 h-40 p-2 bg-white border rounded-lg border-slate-200">
                            <div class="text-center sm:text-left">
                                <p class="text-xs font-semibold tracking-wide uppercase text-emerald-700">Boarding pass</p>
                                <p class="mt-1 text-lg font-bold text-slate-900">Tunjukkan QR ini kepada petugas saat naik kapal</p>
                                <p class="mt-1 text-sm text-slate-600">Satu QR berlaku untuk semua penumpang dalam pesanan ini. Simpan halaman ini atau unduh PDF agar bisa dibuka tanpa internet.</p>
                                <p class="mt-2 font-mono text-sm font-semibold tracking-wider text-slate-700">{{ $kode }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Penumpang --}}
                <section class="px-6 mt-8 sm:px-8">
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">Data penumpang</h2>
                    <div class="overflow-x-auto border rounded-xl border-slate-200">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs tracking-wide uppercase bg-slate-50 text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">No</th>
                                    <th class="px-4 py-3 font-semibold">Nama lengkap</th>
                                    <th class="px-4 py-3 font-semibold">Tipe</th>
                                    <th class="px-4 py-3 font-semibold">Jenis kelamin</th>
                                    <th class="px-4 py-3 font-semibold">No. HP</th>
                                    @if ($qr)
                                        <th class="px-4 py-3 font-semibold">Check-in</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pesanan->penumpangs as $p)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 font-medium text-slate-900">{{ $p->Nama_Lengkap }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md {{ $p->Tipe_Penumpang === 'anak' ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($p->Tipe_Penumpang) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700">{{ ucfirst($p->Jenis_Kelamin) }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $p->Telepon }}</td>
                                        @if ($qr)
                                            <td class="px-4 py-3">
                                                @if ($p->Checkin_Pada)
                                                    <span class="text-xs font-medium text-emerald-700">Sudah naik {{ $p->Checkin_Pada->format('H:i') }}</span>
                                                @else
                                                    <span class="text-xs text-slate-400">Belum</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- Rincian biaya --}}
                <section class="relative px-6 mt-8 sm:px-8">
                    @if ($pesanan->Status === Pesanan::STATUS_LUNAS)
                        <div class="absolute hidden pointer-events-none select-none sm:block left-14 bottom-9 -rotate-12">
                            <span class="block px-4 py-1 text-2xl font-extrabold tracking-[0.3em] text-emerald-600/30 border-4 border-emerald-600/30 rounded-lg">LUNAS</span>
                        </div>
                    @endif
                    <h2 class="mb-3 text-sm font-semibold text-slate-900">Rincian biaya</h2>
                    <div class="overflow-x-auto border rounded-xl border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="text-xs tracking-wide uppercase bg-slate-50 text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-left">Deskripsi</th>
                                    <th class="px-4 py-3 font-semibold text-center">Jumlah</th>
                                    <th class="px-4 py-3 font-semibold text-right">Harga satuan</th>
                                    <th class="px-4 py-3 font-semibold text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($rincian as $r)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-900">Tiket {{ $r['tipe'] }} &middot; Kelas {{ $pesanan->Kelas }}</td>
                                        <td class="px-4 py-3 text-center text-slate-700">{{ $r['jumlah'] }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700">{{ rupiah($r['harga']) }}</td>
                                        <td class="px-4 py-3 font-medium text-right text-slate-900">{{ rupiah($r['subtotal']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-slate-200 bg-slate-50">
                                    <td colspan="3" class="px-4 py-4 font-semibold text-right text-slate-900">Total pembayaran</td>
                                    <td class="px-4 py-4 text-lg font-bold text-right text-slate-900">{{ rupiah($pesanan->Total_Harga) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p class="mt-2 text-xs text-right text-slate-500">Metode pembayaran: <span class="font-medium text-slate-700">{{ $pesanan->Metode_Bayar }}</span></p>
                </section>

                {{-- Catatan --}}
                <footer class="p-6 mt-8 border-t sm:p-8 border-slate-100 bg-slate-50/60">
                    <div class="grid gap-6 text-sm sm:grid-cols-2">
                        <div>
                            <p class="font-semibold text-slate-900">Informasi keberangkatan</p>
                            <ul class="mt-2 space-y-1 list-disc list-inside text-slate-600">
                                <li>Tunjukkan kode booking <span class="font-mono font-semibold text-slate-900">{{ $kode }}</span> saat check-in.</li>
                                <li>Datang paling lambat 60 menit sebelum jam berangkat.</li>
                                <li>Bawa identitas (KTP/kartu pelajar) sesuai nama penumpang.</li>
                            </ul>
                        </div>
                        <div class="sm:text-right">
                            <p class="font-semibold text-slate-900">Terima kasih telah memilih Anang Ferry</p>
                            <p class="mt-2 text-slate-600">Ada pertanyaan tentang pesanan ini? Hubungi kami di anangferry@gmail.com atau 0896-6969-696.</p>
                            <p class="mt-3 text-xs text-slate-400">Dokumen ini dibuat otomatis dan sah tanpa tanda tangan.</p>
                        </div>
                    </div>
                </footer>
            </article>

            {{-- Aksi --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mt-6 no-print">
                <a href="/transaksi" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    Kembali ke transaksi
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold bg-white border rounded-lg shadow-sm text-slate-700 border-slate-300 hover:bg-slate-50">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Unduh PDF
                    </a>
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm bg-slate-900 hover:bg-slate-800">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
