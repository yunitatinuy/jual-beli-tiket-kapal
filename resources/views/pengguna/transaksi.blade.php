@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        // Klasifikasi status dari status_label (sesuaikan kata kunci jika label Anda berbeda)
        $klasifikasi = function ($pesanan) {
            $s = Str::lower($pesanan->status_label ?? '');
            if (Str::contains($s, ['batal', 'expired', 'kadaluarsa', 'gagal'])) return 'batal';
            if (Str::contains($s, ['selesai'])) return 'selesai';
            if (Str::contains($s, ['menunggu', 'pending', 'belum'])) return 'menunggu';
            return 'terkonfirmasi';
        };

        $waktuBerangkat = function ($rute) {
            return $rute ? Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam)) : null;
        };

        $items = collect($pesanans->items());
        $jumlahAktif = $items->filter(fn($p) => in_array($klasifikasi($p), ['terkonfirmasi', 'menunggu']))->count();

        // Perjalanan terdekat yang sudah terkonfirmasi, untuk kartu pengingat
        $berikutnya = $items
            ->filter(fn($p) => $klasifikasi($p) === 'terkonfirmasi' && $p->rute && $waktuBerangkat($p->rute)->isFuture())
            ->sortBy(fn($p) => $waktuBerangkat($p->rute)->timestamp)
            ->first();
    @endphp

    <div x-data="{ tab: 'aktif', q: '', sort: 'terdekat' }">

        {{-- Judul --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-[28px] font-semibold text-slate-800">Tiket saya</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola pesanan dan siapkan perjalanan lautmu dalam satu tempat.</p>
            </div>
            <a href="/"
                class="inline-flex items-center gap-2 rounded-lg bg-[#c8175d] px-6 py-3 text-sm font-semibold text-white hover:bg-[#a6104b]">
                <i class="bi bi-plus-lg text-xs"></i> Pesan tiket baru
            </a>
        </div>

        {{-- Tab --}}
        <div class="mt-8 flex gap-8 border-b border-slate-200 text-sm">
            @foreach (['aktif' => 'Aktif (' . $jumlahAktif . ')', 'selesai' => 'Selesai', 'batal' => 'Dibatalkan'] as $key => $label)
                <button type="button" @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-[#c8175d] text-[#c8175d] font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="-mb-px border-b-[3px] pb-3">{{ $label }}</button>
            @endforeach
        </div>

        {{-- Pencarian & urutan --}}
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-1 flex-wrap items-center gap-4">
                <label class="flex w-full max-w-[392px] items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                    <i class="bi bi-search text-slate-400"></i>
                    <input type="text" x-model="q" placeholder="Cari kode pesanan atau nama kapal"
                        class="w-full border-0 bg-transparent p-0 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-0">
                </label>
                <span class="text-sm text-slate-500">{{ $jumlahAktif }} pesanan aktif</span>
            </div>
            <select x-model="sort"
                class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#c8175d] focus:outline-none focus:ring-0">
                <option value="terdekat">Keberangkatan terdekat</option>
                <option value="terjauh">Keberangkatan terjauh</option>
            </select>
        </div>

        <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">

            {{-- ===== Daftar pesanan ===== --}}
            <div class="flex flex-col gap-5">
                @forelse ($pesanans as $pesanan)
                    @php
                        $rute = $pesanan->rute;
                        $st = $klasifikasi($pesanan);
                        $grup = in_array($st, ['terkonfirmasi', 'menunggu']) ? 'aktif' : $st;
                        $kode = $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan;
                        $berangkat = $waktuBerangkat($rute);
                        $kapal = $rute?->kapal?->Nama_Kapal ?? '-';
                        $asal = $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-';
                        $tujuan = $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-';
                        // Kolom opsional untuk jam/tanggal tiba & durasi (jika ada di tabel rute)
                        $jamTiba = !empty($rute?->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
                        $tglTiba = !empty($rute?->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('d M Y') : '';
                        $durasi = $rute->Durasi ?? null;
                        $batasBayar = !empty($pesanan->Batas_Bayar) ? Carbon::parse($pesanan->Batas_Bayar) : null;
                    @endphp

                    <article x-show="tab === '{{ $grup }}' && (q === '' || $el.dataset.cari.includes(q.toLowerCase()))"
                        data-cari="{{ Str::lower($kode . ' ' . $kapal) }}"
                        :style="`order: ${sort === 'terdekat' ? {{ $berangkat?->timestamp ?? 0 }} : -{{ $berangkat?->timestamp ?? 0 }}}`"
                        class="rounded-2xl border border-slate-200 bg-white p-6">

                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-xs font-medium text-slate-500">#{{ $kode }}</span>
                            @php
                                $badge = [
                                    'terkonfirmasi' => ['bg-green-50 text-green-700', 'bi-check2'],
                                    'menunggu' => ['bg-amber-50 text-amber-700', ''],
                                    'selesai' => ['bg-slate-100 text-slate-600', 'bi-flag'],
                                    'batal' => ['bg-red-50 text-red-700', 'bi-x'],
                                ][$st] ?? ['bg-slate-100 text-slate-600', ''];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-medium {{ $badge[0] }}">
                                @if ($badge[1]) <i class="bi {{ $badge[1] }}"></i> @endif
                                {{ $pesanan->status_label }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 items-center gap-5 py-5 sm:grid-cols-[1fr_1.2fr_1fr_1fr]">
                            <div>
                                <p class="flex items-center gap-2 font-semibold text-slate-800">
                                    <i class="bi bi-tsunami text-[#c8175d]"></i> {{ $kapal }}
                                </p>
                                <p class="mt-1.5 text-xs text-slate-500">{{ $pesanan->Kelas ?? '-' }} · {{ $pesanan->penumpangs_count ?? $pesanan->penumpangs?->count() ?? ($pesanan->Jumlah_Dewasa + $pesanan->Jumlah_Anak) }} penumpang</p>
                                @if ($st === 'terkonfirmasi')
                                    <p class="mt-1 text-[11px] font-medium text-green-700">E-tiket siap digunakan</p>
                                @elseif ($st === 'menunggu')
                                    <p class="mt-1 text-[11px] font-medium text-amber-700">Pesanan belum dibayar</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-2xl font-semibold text-slate-800">{{ $berangkat ? $berangkat->format('H.i') . ' WIB' : '-' }}</p>
                                <p class="mt-1 text-sm text-slate-700">{{ $asal }}</p>
                                <p class="text-xs text-slate-400">{{ $berangkat?->locale('id')->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="text-center text-[11px] text-slate-400">
                                {{ $durasi }}
                                <div class="my-1 flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full border border-slate-300"></span>
                                    <span class="flex-1 border-t border-dashed border-slate-300"></span>
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#c8175d]"></span>
                                </div>
                                Langsung
                            </div>
                            <div>
                                <p class="text-2xl font-semibold text-slate-800">{{ $jamTiba }}</p>
                                <p class="mt-1 text-sm text-slate-700">{{ $tujuan }}</p>
                                <p class="text-xs text-slate-400">{{ $tglTiba }}</p>
                            </div>
                        </div>

                        @if ($st === 'menunggu' && $batasBayar)
                            <div class="rounded-lg bg-amber-50 px-4 py-3 text-xs font-medium text-amber-800">
                                Selesaikan pembayaran sebelum {{ $batasBayar->locale('id')->translatedFormat('d M Y, H.i') }} WIB.
                            </div>
                        @endif

                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                            @if ($st === 'menunggu')
                                <div>
                                    <p class="text-[11px] text-slate-500">Total pembayaran</p>
                                    <p class="text-xl font-semibold text-slate-800">{{ rupiah($pesanan->Total_Harga) }}</p>
                                </div>
                            @else
                                <div>
                                    <p class="text-[11px] font-medium text-slate-500">Persiapan keberangkatan</p>
                                    <p class="text-[11px] text-slate-500">Tiba di pelabuhan minimal 2 jam sebelum berangkat.</p>
                                </div>
                            @endif
                            <div class="flex items-center gap-2.5">
                                <a href="{{ route('pesanan.detail', $pesanan->ID_Pesanan) }}"
                                    class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Detail pesanan
                                </a>
                                @if ($st === 'terkonfirmasi')
                                    <a href="{{ route('invoices.eticket', $pesanan->ID_Pesanan) }}"
                                        class="rounded-lg bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-[#a6104b]">
                                        <i class="bi bi-ticket-perforated mr-1"></i> Lihat e-tiket
                                    </a>
                                @elseif ($st === 'menunggu')
                                    <a href="{{ route('invoices.show', $pesanan->ID_Pesanan) }}"
                                        class="rounded-lg bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-[#a6104b]">
                                        <i class="bi bi-wallet2 mr-1"></i> Bayar sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                        <i class="bi bi-ticket-perforated text-3xl text-slate-300"></i>
                        <p class="mt-3">Belum ada pesanan.</p>
                    </div>
                @endforelse

                <div class="order-[999999999] text-center">{{ $pesanans->links() }}</div>
            </div>

            {{-- ===== Sidebar ===== --}}
            <aside class="flex flex-col gap-5">
                @if ($berikutnya)
                    @php
                        $b = $waktuBerangkat($berikutnya->rute);
                        $judul = $b->isTomorrow() ? 'Besok, waktunya berlayar!' : ($b->isToday() ? 'Hari ini, waktunya berlayar!' : 'Perjalanan berikutnya');
                    @endphp
                    <section class="rounded-2xl bg-pink-100/70 p-6 text-[#c8175d]">
                        <p class="flex items-center gap-3 text-lg font-semibold"><i class="bi bi-calendar3"></i> {{ $judul }}</p>
                        <p class="mt-4 font-semibold">
                            {{ $berikutnya->rute->pelabuhanAsal->Nama_Pelabuhan ?? '-' }}
                            <i class="bi bi-arrow-right mx-1"></i>
                            {{ $berikutnya->rute->pelabuhanTujuan->Nama_Pelabuhan ?? '-' }}
                        </p>
                        <p class="mt-3 text-sm text-slate-700">{{ $b->locale('id')->translatedFormat('d M Y') }} · {{ $b->format('H.i') }} WIB</p>
                        <p class="text-sm text-slate-700">{{ $berikutnya->rute->kapal->Nama_Kapal ?? '-' }} · {{ $berikutnya->penumpangs_count ?? $berikutnya->penumpangs?->count() ?? ($berikutnya->Jumlah_Dewasa + $berikutnya->Jumlah_Anak) }} penumpang</p>
                        <p class="mt-4 border-t border-pink-200 pt-4 text-sm">
                            Datang paling lambat pukul {{ $b->copy()->subHours(2)->format('H.i') }} WIB untuk proses check-in di pelabuhan.
                        </p>
                    </section>
                @endif

                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="text-lg font-semibold text-slate-800">Siap sebelum berlayar</h3>
                    <ol class="mt-5 space-y-5">
                        @foreach ([
                            ['Siapkan identitas asli', 'Bawa KTP atau identitas sesuai data penumpang.'],
                            ['Simpan e-tiket', 'Unduh e-tiket agar tetap bisa diakses tanpa internet.'],
                            ['Check-in di pelabuhan', 'Tunjukkan e-tiket dan identitas kepada petugas.'],
                        ] as $n => [$judul, $isi])
                            <li class="flex gap-4">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-pink-50 text-xs font-semibold text-[#c8175d]">{{ $n + 1 }}</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $judul }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $isi }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-800">Ada kendala dengan tiketmu?</h3>
                    <p class="mt-3 text-xs text-slate-500">Kami bantu menemukan solusi untuk pesanan dan perjalananmu.</p>
                    <a href="/informasi" class="mt-4 inline-block rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50">Hubungi bantuan</a>
                </section>
            </aside>
        </div>
    </div>
@endsection