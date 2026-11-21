{{-- Partial: dipanggil dari invoices/show (invoice.blade.php) saat pesanan belum dibayar.
     Memakai variabel dari view induk: $pesanan, $rute, $kode, $asal, $tujuan, $berangkat, $jamTiba, $tglTiba, $jumlah --}}
@php
    use Carbon\Carbon;

    $batasBayar = !empty($pesanan->Batas_Bayar)
        ? Carbon::parse($pesanan->Batas_Bayar)
        : (!empty($pesanan->created_at) ? Carbon::parse($pesanan->created_at)->addMinutes($batasBayarMenit ?? 30) : now()->addMinutes(30));
    $batasBayar = $batasBayar->copy()->locale('id');

    // Opsional: jika Anda menyimpan nomor VA dari Midtrans
    $vaNumber = $pesanan->Va_Number ?? null;
    $vaBank = $pesanan->Va_Bank ?? 'Virtual Account';

    $clientKey = $midtransClientKey ?? config('services.midtrans.client_key');
    $produksi = (bool) config('services.midtrans.is_production', false);

    $dewasaCount = $pesanan->penumpangs->where('Tipe_Penumpang', 'dewasa')->count() ?: 1;
    $anakCount = $pesanan->penumpangs->where('Tipe_Penumpang', 'anak')->count() ?: 0;
    $urlKembaliDataPenumpang = route('pesantiket', [
        'rute' => $pesanan->ID_Rute,
        'kelas' => $pesanan->Kelas,
        'dewasa' => $dewasaCount,
        'anak' => $anakCount,
    ]);
@endphp

<div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]"
    x-data="{
        sisa: Math.max(0, Math.floor((new Date('{{ $batasBayar->toIso8601String() }}') - new Date()) / 1000)),
        memuat: false,
        salin(teks, el) { navigator.clipboard?.writeText(teks); el.dataset.t = el.textContent; el.textContent = 'Tersalin'; setTimeout(() => el.textContent = el.dataset.t, 1500) },
        fmt(n) { return String(n).padStart(2, '0') },
        get hh() { return this.fmt(Math.floor(this.sisa / 3600)) },
        get mm() { return this.fmt(Math.floor((this.sisa % 3600) / 60)) },
        get ss() { return this.fmt(this.sisa % 60) },
        bayar() {
            if (this.memuat) return;
            const submitCekStatus = () => {
                const f = document.getElementById('form-cek-status');
                if (f) { f.submit(); } else { location.reload(); }
            };

            @if (!empty($snapToken))
                if (typeof window.snap === 'undefined') {
                    alert('Midtrans Snap SDK sedang dimuat. Silakan tunggu sebentar lalu coba lagi.');
                    return;
                }
                window.snap.pay(@js($snapToken), {
                    onSuccess: (result) => { submitCekStatus(); },
                    onPending: (result) => { submitCekStatus(); },
                    onError: (result) => { alert('Pembayaran gagal atau dibatalkan.'); },
                    onClose: () => { submitCekStatus(); }
                });
            @else
                this.memuat = true;
                fetch(@js(route('pesanan.bayar', $pesanan->ID_Pesanan)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.memuat = false;
                    if (data.token && typeof window.snap !== 'undefined') {
                        window.snap.pay(data.token, {
                            onSuccess: () => submitCekStatus(),
                            onPending: () => submitCekStatus(),
                            onError: () => alert('Pembayaran gagal atau dibatalkan.'),
                            onClose: () => submitCekStatus()
                        });
                    } else {
                        alert(data.message || 'Gagal menyiapkan pembayaran. Silakan coba kembali.');
                    }
                })
                .catch(() => {
                    this.memuat = false;
                    submitCekStatus();
                });
            @endif
        },
        init() {
            const t = setInterval(() => { if (this.sisa > 0) this.sisa--; else { clearInterval(t); location.reload(); } }, 1000);
        }
    }">

    <div class="flex flex-col gap-5">

        {{-- Banner menunggu + hitung mundur --}}
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-pink-100/70 p-6 dark:bg-pink-950/40">
            <div>
                <p class="text-lg font-semibold text-[#c8175d] dark:text-pink-400">Menunggu pembayaran</p>
                <p class="mt-3 text-sm text-slate-700 dark:text-slate-300">Bayar sebelum {{ $batasBayar->translatedFormat('l, d F Y') }} · {{ $batasBayar->format('H.i') }} WIB</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-[#c8175d] dark:text-pink-400">Sisa waktu</p>
                <p class="mt-1 text-3xl font-bold tracking-wide text-[#c8175d] dark:text-pink-400"><span x-text="hh"></span> : <span x-text="mm"></span> : <span x-text="ss"></span></p>
            </div>
        </div>

        {{-- Data Penumpang (Read-only / tidak dapat diubah) - Menggantikan card metode pembayaran --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
                <div>
                    <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-100">Data penumpang</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Data penumpang yang telah diisi untuk pesanan tiket ini (tidak dapat diubah).</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <i class="bi bi-lock-fill text-slate-400 dark:text-slate-500"></i> Terkunci
                    </span>
                    <a href="{{ $urlKembaliDataPenumpang }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700"
                        title="Kembali ke langkah 2 untuk mengubah data penumpang">
                        <i class="bi bi-arrow-left"></i> Kembali ke data penumpang
                    </a>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @foreach ($pesanan->penumpangs as $i => $pen)
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition dark:border-slate-800 dark:bg-slate-800/50">
                        <div class="flex items-center justify-between border-b border-slate-200/70 pb-3 dark:border-slate-700/60">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#c8175d] text-xs font-bold text-white">
                                    {{ $i + 1 }}
                                </span>
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    Penumpang {{ $i + 1 }}
                                </span>
                                <span class="rounded-md bg-pink-100 px-2 py-0.5 text-[11px] font-medium text-[#c8175d] dark:bg-pink-950/60 dark:text-pink-300">
                                    {{ ucfirst($pen->Tipe_Penumpang ?? 'Dewasa') }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-500 font-medium dark:text-slate-400">
                                {{ ucfirst((string) ($pen->Jenis_Kelamin?->description ?? ($pen->Jenis_Kelamin?->value ?? ($pen->Jenis_Kelamin ?? '-')))) }}
                            </span>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3 text-xs">
                            <div>
                                <span class="block text-slate-400 dark:text-slate-500">Nama lengkap</span>
                                <span class="mt-0.5 block text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $pen->Nama_Lengkap }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 dark:text-slate-500">Nomor telepon</span>
                                <span class="mt-0.5 block font-medium text-slate-700 dark:text-slate-300">{{ $pen->Telepon ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 dark:text-slate-500">Alamat</span>
                                <span class="mt-0.5 block font-medium text-slate-700 truncate dark:text-slate-300" title="{{ $pen->Alamat }}">{{ $pen->Alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Detail virtual account (tampil jika nomor VA tersimpan) --}}
        @if ($vaNumber)
            <section class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <p class="text-lg text-slate-500 dark:text-slate-400">Bank tujuan</p>
                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $vaBank }}</p>
                </div>
                <p class="mt-5 text-xs text-slate-500 dark:text-slate-400">Nomor virtual account</p>
                <div class="mt-2 flex items-center justify-between rounded-xl bg-slate-50 px-5 py-4 dark:bg-slate-800">
                    <p class="text-2xl font-semibold tracking-wide text-slate-800 dark:text-slate-100">{{ trim(chunk_split($vaNumber, 4, ' ')) }}</p>
                    <button type="button" @click="salin(@js($vaNumber), $el)" class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">Salin nomor</button>
                </div>
                <div class="mt-5 flex justify-between border-b border-slate-100 pb-5 text-xs dark:border-slate-800">
                    <span class="text-slate-500 dark:text-slate-400">Atas nama</span>
                    <span class="font-semibold uppercase text-slate-800 dark:text-slate-200">Anang Ferry · {{ Auth::user()->name ?? '' }}</span>
                </div>
                <p class="mt-5 text-xs text-slate-500 dark:text-slate-400">Total yang harus dibayar</p>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-4xl font-bold text-[#c8175d] dark:text-pink-400">{{ rupiah($pesanan->Total_Harga) }}</p>
                    <button type="button" @click="salin(@js((string) (int) $pesanan->Total_Harga), $el)" class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">Salin nominal</button>
                </div>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Transfer sesuai nominal di atas. Tidak ada biaya tambahan.</p>

                <div class="mt-5 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <p class="font-semibold text-slate-800 dark:text-slate-100">Cara pembayaran melalui mobile banking</p>
                    <ol class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li><span class="mr-2 font-semibold text-[#c8175d] dark:text-pink-400">1.</span>Buka aplikasi mobile banking, lalu pilih menu transfer.</li>
                        <li><span class="mr-2 font-semibold text-[#c8175d] dark:text-pink-400">2.</span>Pilih virtual account dan masukkan nomor di atas.</li>
                        <li><span class="mr-2 font-semibold text-[#c8175d] dark:text-pink-400">3.</span>Pastikan nama penerima dan nominal {{ rupiah($pesanan->Total_Harga) }} sudah benar.</li>
                        <li><span class="mr-2 font-semibold text-[#c8175d] dark:text-pink-400">4.</span>Masukkan PIN dan simpan bukti pembayaranmu.</li>
                    </ol>
                </div>
            </section>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs text-slate-500 dark:text-slate-400">Total yang harus dibayar</p>
                <p class="mt-2 text-4xl font-bold text-[#c8175d] dark:text-pink-400">{{ rupiah($pesanan->Total_Harga) }}</p>
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Tekan tombol "Bayar sekarang" untuk memilih metode pembayaran (QRIS, Virtual Account, Kartu Kredit, atau dompet digital) pada jendela pembayaran Midtrans.</p>
            </section>
        @endif

        <div class="rounded-xl bg-green-50 px-6 py-5 dark:bg-emerald-950/40">
            <p class="text-sm font-semibold text-green-700 dark:text-emerald-400">Pembayaran dikonfirmasi otomatis</p>
            <p class="mt-1 text-xs text-green-700 dark:text-emerald-300/90">E-tiket akan diterbitkan otomatis setelah pembayaran selesai. Kamu tidak perlu mengirimkan bukti transfer secara manual.</p>
        </div>

        {{-- Tombol aksi pembayaran & Navigasi --}}
        <div class="flex flex-col gap-3">
            <button type="button" @click="bayar()"
                class="w-full rounded-xl bg-[#c8175d] py-4 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-[#a6104b] focus:outline-none focus:ring-2 focus:ring-[#c8175d]/20">
                <span x-show="!memuat"><i class="bi bi-wallet2 mr-1.5"></i> Bayar sekarang</span>
                <span x-cloak x-show="memuat"><i class="bi bi-arrow-repeat mr-1.5 animate-spin"></i> Menyiapkan pembayaran...</span>
            </button>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <a href="{{ $urlKembaliDataPenumpang }}"
                    class="rounded-xl border border-slate-200 bg-white py-3.5 text-center text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    <i class="bi bi-arrow-left mr-1"></i> Kembali ke 2 data penumpang
                </a>

                <form method="POST" action="{{ route('pesanan.cek-status', $pesanan->ID_Pesanan) }}" id="form-cek-status">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white py-3.5 text-center text-xs font-semibold text-[#c8175d] transition hover:bg-pink-50 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">
                        <i class="bi bi-arrow-clockwise mr-1"></i> Cek status pembayaran
                    </button>
                </form>
            </div>
        </div>

        <p class="text-xs text-slate-500 dark:text-slate-400">Belum terkonfirmasi? Tunggu beberapa saat lalu tekan tombol "Cek status pembayaran".</p>
    </div>

    {{-- Sidebar --}}
    <aside class="flex flex-col gap-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Pesanan kamu</h3>
            <div class="mt-5 flex items-center justify-between text-xs">
                <span class="text-slate-500 dark:text-slate-400">Nomor pesanan</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</span>
            </div>
            <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">{{ $jumlah }} penumpang · Sekali jalan</p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Ringkasan perjalanan</h3>
            <p class="mt-5 flex items-center gap-2 text-xl font-semibold text-[#c8175d] dark:text-pink-400">{{ $asal }} <i class="bi bi-arrow-right"></i> {{ $tujuan }}</p>
            <p class="mt-2 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $rute?->kapal?->Nama_Kapal ?? '-' }} · {{ $pesanan->Kelas ?? '-' }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Sekali jalan · {{ $jumlah }} penumpang</p>
            <div class="mt-5 rounded-xl bg-slate-50 p-4 dark:bg-slate-800/60">
                <p class="text-[10px] font-medium uppercase text-slate-500 dark:text-slate-400">Berangkat · {{ $berangkat?->translatedFormat('D, d M Y') }}</p>
                <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $berangkat?->format('H.i') }} WIB · {{ $asal }}</p>
                @if (!empty($rute->Durasi))
                    <p class="my-3 flex items-center gap-2 text-xs text-[#c8175d] dark:text-pink-400"><i class="bi bi-arrow-down"></i> {{ $rute->Durasi }} · Langsung</p>
                @else
                    <div class="my-3 border-t border-dashed border-slate-300 dark:border-slate-700"></div>
                @endif
                <p class="text-[10px] font-medium uppercase text-slate-500 dark:text-slate-400">Tiba · {{ $tglTiba }}</p>
                <p class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $jamTiba }} · {{ $tujuan }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Rincian harga</h3>
            <div class="mt-5 flex justify-between text-sm text-slate-600 dark:text-slate-300">
                <span>Tiket × {{ $jumlah }}</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ rupiah($pesanan->Total_Harga) }}</span>
            </div>
            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5 dark:border-slate-800">
                <span class="text-xs text-slate-500 dark:text-slate-400">Total pembayaran</span>
                <span class="text-2xl font-bold text-[#c8175d] dark:text-pink-400">{{ rupiah($pesanan->Total_Harga) }}</span>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Butuh bantuan pembayaran?</h3>
            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Jangan berikan PIN, OTP, atau kata sandi kepada siapa pun.</p>
            <a href="/informasi" class="mt-4 block rounded-lg border border-slate-200 py-3 text-center text-xs font-semibold text-[#c8175d] hover:bg-pink-50 dark:border-slate-700 dark:bg-slate-800 dark:text-pink-400 dark:hover:bg-slate-700">Hubungi bantuan</a>
        </section>
    </aside>
</div>

@if ($clientKey)
    <script src="{{ $produksi ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
@endif
