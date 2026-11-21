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
        $berangkat = $rute ? Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam))->locale('id') : null;
        $jamTiba = !empty($rute?->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
        $tglTiba = !empty($rute?->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('D, d M Y') : '';
        $jumlah = $pesanan->penumpangs->count();
        $user = Auth::user();

        // Langkah: 3 = pembayaran, 4 = e-tiket terbit
        $langkah = $lunas ? 4 : 3;
        $steps = ['Pilih tiket', 'Data penumpang', 'Pembayaran', 'E-tiket terbit'];
    @endphp

    <style>
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>

    {{-- Breadcrumb & judul --}}
    <nav class="no-print text-xs text-slate-500 dark:text-slate-400">
        <a href="/" class="hover:text-[#c8175d] dark:hover:text-pink-400">Beranda</a> <span class="mx-1">/</span>
        <a href="/transaksi" class="hover:text-[#c8175d] dark:hover:text-pink-400">Tiket saya</a> <span class="mx-1">/</span>
        <span>{{ $lunas ? 'E-tiket terbit' : 'Pembayaran' }}</span>
    </nav>

    <div class="no-print mt-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-slate-800 dark:text-slate-100">
                {{ $lunas ? 'E-tiket berhasil diterbitkan' : ($batal ? 'Pesanan ' . Str::lower($pesanan->status_label) : 'Selesaikan pembayaran') }}
            </h1>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                @if ($lunas) Pembayaran berhasil. Simpan e-tiket dan siapkan identitas asli untuk perjalananmu.
                @elseif ($batal) Pesanan ini tidak dapat dibayar lagi. Silakan buat pesanan baru.
                @else Selesaikan pembayaran sebelum batas waktu agar tiketmu dapat diterbitkan. @endif
            </p>
        </div>
        @if ($lunas)
            <a href="/transaksi" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">
                Lihat tiket saya <i class="bi bi-arrow-right"></i>
            </a>
        @else
            <div class="flex flex-wrap items-center gap-2">
                @if ($menunggu)
                    <a href="{{ route('pesantiket', ['rute' => $pesanan->ID_Rute, 'kelas' => $pesanan->Kelas, 'dewasa' => $pesanan->penumpangs->where('Tipe_Penumpang', 'dewasa')->count() ?: 1, 'anak' => $pesanan->penumpangs->where('Tipe_Penumpang', 'anak')->count() ?: 0]) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        <i class="bi bi-arrow-left"></i> Kembali ke 2 data penumpang
                    </a>
                @endif
                <a href="/transaksi" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">
                    <i class="bi bi-ticket-perforated"></i> Tiket saya
                </a>
            </div>
        @endif
    </div>

    {{-- Langkah --}}
    <ol class="no-print mt-6 grid grid-cols-2 gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-sm md:grid-cols-4 dark:border-slate-800 dark:bg-slate-900">
        @foreach ($steps as $n => $teks)
            @php
                $no = $n + 1;
                $selesai = $no < $langkah || ($lunas && $no === 4);
                $sekarang = $no === $langkah;
                $urlLangkah = null;
                if ($menunggu && $no === 2) {
                    $urlLangkah = route('pesantiket', [
                        'rute' => $pesanan->ID_Rute,
                        'kelas' => $pesanan->Kelas,
                        'dewasa' => $pesanan->penumpangs->where('Tipe_Penumpang', 'dewasa')->count() ?: 1,
                        'anak' => $pesanan->penumpangs->where('Tipe_Penumpang', 'anak')->count() ?: 0,
                    ]);
                }
            @endphp
            <li class="flex items-center gap-3">
                @if ($urlLangkah)
                    <a href="{{ $urlLangkah }}" class="group flex items-center gap-3" title="Kembali ke langkah 2 data penumpang">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-100 text-xs font-semibold text-[#c8175d] transition group-hover:bg-[#c8175d] group-hover:text-white dark:bg-pink-950/60 dark:text-pink-300">
                            <i class="bi bi-check2"></i>
                        </span>
                        <span class="font-medium text-slate-700 transition group-hover:text-[#c8175d] dark:text-slate-300 dark:group-hover:text-pink-400">{{ $teks }}</span>
                    </a>
                @else
                    <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold {{ $sekarang ? 'bg-[#c8175d] text-white' : 'bg-pink-50 text-[#c8175d] dark:bg-slate-800 dark:text-pink-400' }}">
                        @if ($selesai) <i class="bi bi-check2"></i> @else {{ $no }} @endif
                    </span>
                    <span class="{{ $sekarang ? 'font-semibold text-[#c8175d] dark:text-pink-400' : 'text-slate-600 dark:text-slate-400' }}">{{ $teks }}</span>
                @endif
            </li>
        @endforeach
    </ol>

    @if (session('error') || session('success') || (isset($errors) && $errors->any()))
        <div class="no-print mt-5 rounded-lg p-4 text-sm {{ session('success') ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}" role="alert">
            {{ session('success') ?? (session('error') ?? ($errors?->first() ?? 'Terjadi kesalahan')) }}
        </div>
    @endif

    @if ($batal)
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <i class="bi bi-x-circle text-4xl text-red-400"></i>
            <p class="mt-4 text-lg font-semibold text-slate-800">Pesanan {{ $kode }} {{ Str::lower($pesanan->status_label) }}</p>
            <a href="/dashboard_pengguna" class="mt-5 inline-block rounded-lg bg-[#c8175d] px-6 py-3 text-xs font-semibold text-white hover:bg-[#a6104b]">Pesan tiket baru</a>
        </div>

    @elseif ($menunggu)
        {{-- ================= HALAMAN PEMBAYARAN ================= --}}
        @include('pengguna.bayar')

    @else
        {{-- ================= E-TIKET TERBIT ================= --}}
        @php
            $dibayar = !empty($pesanan->Dibayar_Pada) ? Carbon::parse($pesanan->Dibayar_Pada)->locale('id') : null;
            $batasCheckin = $berangkat?->copy()->subHours(2);
        @endphp

        <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">
            <div class="flex flex-col gap-5">

                <div class="no-print flex items-center gap-4 rounded-2xl bg-green-50 p-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-700 text-xl text-white"><i class="bi bi-check2"></i></span>
                    <div>
                        <p class="text-lg font-semibold text-green-700">Pembayaran berhasil, perjalananmu siap!</p>
                        <p class="mt-1 text-xs text-green-700">E-tiket dapat dilihat di halaman ini{{ $user?->email ? ' dan dikirim ke ' . $user->email : '' }}.</p>
                    </div>
                </div>

                {{-- Kartu e-tiket --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="flex items-start justify-between border-b border-slate-100 pb-5">
                        <div>
                            <p class="text-sm font-bold uppercase text-[#c8175d]">Anang Ferry</p>
                            <h2 class="mt-1 text-2xl font-semibold text-slate-800">E-tiket perjalanan</h2>
                        </div>
                        <span class="rounded-md bg-green-50 px-3 py-2 text-xs font-medium text-green-700">Tiket aktif</span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div><p class="text-xs text-slate-500">Kode booking</p><p class="mt-1 text-3xl font-bold tracking-wide text-[#c8175d]">{{ $kode }}</p></div>
                        <div><p class="text-xs text-slate-500">Nomor pesanan</p><p class="mt-2 text-base font-semibold text-slate-800">{{ $pesanan->ID_Pesanan }}</p></div>
                    </div>

                    <div class="mt-5 rounded-xl bg-slate-50 p-5">
                        <p class="text-sm font-semibold text-slate-800">{{ $rute?->kapal?->Nama_Kapal ?? '-' }} · {{ $pesanan->Kelas ?? '-' }}</p>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[11px] uppercase text-slate-500">{{ $berangkat?->translatedFormat('D, d M Y') }}</p>
                                <p class="mt-1 text-3xl font-semibold text-slate-800">{{ $berangkat?->format('H.i') }} WIB</p>
                                <p class="mt-2 text-lg font-semibold text-[#c8175d]">{{ $asal }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] uppercase text-slate-500">{{ $tglTiba }}</p>
                                <p class="mt-1 text-3xl font-semibold text-slate-800">{{ $jamTiba }}</p>
                                <p class="mt-2 text-lg font-semibold text-[#c8175d]">{{ $tujuan }}</p>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-slate-500">{{ $rute->Durasi ?? '' }}{{ !empty($rute->Durasi) ? ' · ' : '' }}Langsung · Seluruh waktu dalam WIB</p>
                    </div>

                    <h3 class="mt-7 text-lg font-semibold text-slate-800">Daftar penumpang</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($pesanan->penumpangs as $i => $pen)
                            <div class="flex items-start justify-between rounded-xl bg-slate-50 px-5 py-4">
                                <div class="flex gap-4">
                                    <span class="text-lg font-semibold text-[#c8175d]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $pen->Nama_Lengkap }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $pen->Tipe ?? ucfirst($pen->Jenis_Kelamin) }} · {{ $pen->Telepon }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $pesanan->Kelas ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-xl bg-pink-100/70 p-5">
                        <p class="font-semibold text-[#c8175d]">Hadir paling lambat pukul {{ $batasCheckin?->format('H.i') }} WIB</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $berangkat?->translatedFormat('l, d F Y') }} · Pelabuhan {{ $asal }}</p>
                        <p class="mt-1 text-xs text-slate-500">Bawa e-tiket ini dan identitas asli seluruh penumpang saat check-in.</p>
                    </div>
                </section>

                <div class="no-print grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <a href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}"
                        class="block w-full rounded-lg bg-[#c8175d] py-3.5 text-center text-sm font-semibold text-white hover:bg-[#a6104b]">Unduh e-tiket (PDF)</a>
                    @if (\Illuminate\Support\Facades\Route::has('invoices.resend'))
                        <form action="{{ route('invoices.resend', $pesanan->ID_Pesanan) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-slate-200 bg-white py-3.5 text-sm font-semibold text-[#c8175d] hover:bg-pink-50">Kirim ulang ke email</button>
                        </form>
                    @else
                        <a href="{{ route('invoices.eticket', $pesanan->ID_Pesanan) }}" class="rounded-lg border border-slate-200 bg-white py-3.5 text-center text-sm font-semibold text-[#c8175d] hover:bg-pink-50">Lihat halaman e-tiket</a>
                    @endif
                </div>

                {{-- Ringkasan pembayaran --}}
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-800">Ringkasan pembayaran</h3>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-500">Status pembayaran</dt><dd class="font-semibold text-green-700">Lunas</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Metode pembayaran</dt><dd class="font-semibold text-slate-800">{{ $pesanan->Metode_Pembayaran ?? 'Midtrans' }}</dd></div>
                        @if ($dibayar)
                            <div class="flex justify-between"><dt class="text-slate-500">Waktu pembayaran</dt><dd class="font-semibold text-slate-800">{{ $dibayar->translatedFormat('d F Y · H.i') }} WIB</dd></div>
                        @endif
                    </dl>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5">
                        <span class="text-slate-500">Total dibayar</span>
                        <span class="text-2xl font-bold text-[#c8175d]">{{ rupiah($pesanan->Total_Harga) }}</span>
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="flex flex-col gap-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-800">Kode check-in</h3>
                    <p class="mt-4 text-xs text-slate-500">Tunjukkan kode ini kepada petugas di pelabuhan.</p>
                    <div id="qrcode" class="my-6 flex justify-center"></div>
                    <p class="text-center text-2xl font-bold tracking-wide text-[#c8175d]">{{ $kode }}</p>
                </section>

                <section class="no-print rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-800">Sebelum berangkat</h3>
                    <ol class="mt-5 space-y-4 text-sm">
                        <li><p class="font-semibold text-slate-800">1. Simpan e-tiket</p><p class="mt-1 text-xs text-slate-500">Unduh PDF agar tetap dapat dibuka tanpa koneksi internet.</p></li>
                        <li><p class="font-semibold text-slate-800">2. Siapkan identitas asli</p><p class="mt-1 text-xs text-slate-500">Nama dan identitas harus sesuai dengan data pada tiket.</p></li>
                        <li><p class="font-semibold text-slate-800">3. Datang 2 jam lebih awal</p><p class="mt-1 text-xs text-slate-500">Lakukan check-in di pelabuhan sebelum keberangkatan.</p></li>
                    </ol>
                </section>

                <section class="no-print rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-800">Ada kendala dengan tiketmu?</h3>
                    <p class="mt-3 text-xs text-slate-500">Lihat ketentuan perjalanan atau hubungi tim bantuan kami.</p>
                    <a href="/informasi" class="mt-4 block rounded-lg border border-slate-200 py-3 text-center text-xs font-semibold text-[#c8175d] hover:bg-pink-50">Hubungi bantuan</a>
                </section>
            </aside>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            new QRCode(document.getElementById('qrcode'), {
                text: @json((string) $kode), width: 176, height: 176, colorDark: '#1d1d1f', colorLight: '#ffffff',
            });
        </script>
    @endif
@endsection