@php
    use App\Models\Pesanan;
    $pesanStyle = [
        'sukses' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'gagal' => 'bg-rose-50 border-rose-200 text-rose-800',
        'info' => 'bg-sky-50 border-sky-200 text-sky-800',
    ];
@endphp
<div class="flex flex-col flex-grow mt-8 mb-10 ms-10 me-10 font-inter">
    <div class="flex items-center gap-3 p-2">
        <svg class="w-8 h-8 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Check-in Penumpang</h1>
            <p class="text-sm text-slate-500">Pindai QR di e-tiket penumpang atau ketik kode booking.</p>
        </div>
    </div>

    <div class="grid gap-6 mt-6 xl:grid-cols-5">
        {{-- Scanner & input --}}
        <div class="space-y-4 xl:col-span-2">
            <div class="p-5 bg-white border rounded-xl border-slate-200" wire:ignore>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-slate-900">Pindai QR</h2>
                    <button type="button" id="btn-kamera" class="px-3 py-1.5 text-sm font-medium text-white rounded-lg bg-sky-600 hover:bg-sky-700">Nyalakan kamera</button>
                </div>
                <div id="qr-reader" class="overflow-hidden rounded-lg bg-slate-100 min-h-[60px]"></div>
                <p id="qr-status" class="mt-2 text-xs text-slate-500">Kamera belum aktif. Izinkan akses kamera saat diminta browser.</p>
            </div>

            <form wire:submit="cari" class="p-5 bg-white border rounded-xl border-slate-200">
                <label for="kode-manual" class="block mb-2 font-semibold text-slate-900">Atau ketik kode booking</label>
                <div class="flex gap-2">
                    <input id="kode-manual" type="text" wire:model="input" placeholder="KPL261120XXXXX" autocomplete="off"
                        class="flex-1 p-2.5 font-mono text-sm uppercase border rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-slate-900 hover:bg-slate-800">Cari</button>
                </div>
            </form>

            {{-- Rekap hari ini --}}
            <div class="p-5 bg-white border rounded-xl border-slate-200">
                <h2 class="mb-3 font-semibold text-slate-900">Keberangkatan hari ini</h2>
                @forelse ($hariIni as $r)
                    @php $persen = $r->total_lunas ? round($r->total_checkin / $r->total_lunas * 100) : 0; @endphp
                    <div class="py-2 text-sm border-t first:border-t-0 border-slate-100">
                        <div class="flex justify-between">
                            <span class="font-medium text-slate-900">{{ substr($r->Jam, 0, 5) }} &middot; {{ $r->pelabuhanAsal?->Nama_Pelabuhan }} &rarr; {{ $r->pelabuhanTujuan?->Nama_Pelabuhan }}</span>
                            <span class="text-slate-600">{{ (int) $r->total_checkin }}/{{ (int) $r->total_lunas }}</span>
                        </div>
                        <div class="h-1.5 mt-1.5 rounded-full bg-slate-100"><div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ $persen }}%"></div></div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada keberangkatan hari ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Hasil --}}
        <div class="xl:col-span-3">
            @if ($pesan)
                <div class="p-4 mb-4 text-sm font-medium border rounded-xl {{ $pesanStyle[$jenisPesan] ?? $pesanStyle['info'] }}" role="alert">{{ $pesan }}</div>
            @endif

            @if ($kode === '')
                <div class="flex flex-col items-center justify-center h-full p-10 text-center border-2 border-dashed rounded-xl border-slate-200 text-slate-500">
                    <p class="font-medium">Belum ada tiket yang dipindai</p>
                    <p class="text-sm">Hasil pencarian akan tampil di sini.</p>
                </div>
            @elseif (! $pesanan)
                <div class="p-6 border rounded-xl bg-rose-50 border-rose-200">
                    <p class="font-semibold text-rose-900">Kode booking {{ $kode }} tidak ditemukan</p>
                    <p class="text-sm text-rose-800">Periksa kembali kode pada e-tiket penumpang.</p>
                </div>
            @else
                @php
                    $r = $pesanan->rute;
                    $sudah = $pesanan->penumpangs->whereNotNull('Checkin_Pada')->count();
                    $total = $pesanan->penumpangs->count();
                @endphp
                <div class="overflow-hidden bg-white border rounded-xl {{ $alasan ? 'border-rose-300' : 'border-emerald-300' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3 p-5 {{ $alasan ? 'bg-rose-50' : 'bg-emerald-50' }}">
                        <div>
                            <p class="text-xs font-semibold tracking-wide uppercase text-slate-500">Kode booking</p>
                            <p class="font-mono text-2xl font-bold text-slate-900">{{ $pesanan->Kode_Booking }}</p>
                            <p class="text-sm text-slate-600">Pemesan: {{ $pesanan->user?->name }}</p>
                        </div>
                        <div class="text-right">
                            @if ($alasan)
                                <span class="inline-block px-3 py-1 text-sm font-bold text-white rounded-full bg-rose-600">TIDAK BISA CHECK-IN</span>
                            @elseif ($sudah === $total)
                                <span class="inline-block px-3 py-1 text-sm font-bold text-white rounded-full bg-emerald-600">SEMUA SUDAH NAIK</span>
                            @else
                                <span class="inline-block px-3 py-1 text-sm font-bold text-white rounded-full bg-emerald-600">TIKET VALID</span>
                            @endif
                            <p class="mt-1 text-sm text-slate-600">{{ $sudah }} dari {{ $total }} penumpang check-in</p>
                        </div>
                    </div>

                    @if ($alasan)
                        <p class="px-5 py-3 text-sm font-medium border-t text-rose-800 border-rose-200">{{ $alasan }}</p>
                    @endif

                    <dl class="grid grid-cols-2 gap-4 p-5 text-sm border-t sm:grid-cols-4 border-slate-100">
                        <div><dt class="text-xs text-slate-400">Rute</dt><dd class="font-semibold text-slate-900">{{ $r?->pelabuhanAsal?->Nama_Pelabuhan }} &rarr; {{ $r?->pelabuhanTujuan?->Nama_Pelabuhan }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Berangkat</dt><dd class="font-semibold text-slate-900">{{ $berangkat?->translatedFormat('d M Y, H:i') ?? '-' }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Kapal</dt><dd class="font-semibold text-slate-900">{{ $r?->kapal?->Nama_Kapal ?? '-' }}</dd></div>
                        <div><dt class="text-xs text-slate-400">Kelas / Status</dt><dd class="font-semibold text-slate-900">{{ $pesanan->Kelas }} &middot; {{ $pesanan->status_label }}</dd></div>
                    </dl>

                    <table class="w-full text-sm border-t border-slate-100">
                        <thead class="text-xs text-left uppercase bg-slate-50 text-slate-500">
                            <tr><th class="px-5 py-2">Penumpang</th><th class="px-5 py-2">Tipe</th><th class="px-5 py-2">Status</th><th class="px-5 py-2 text-right">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pesanan->penumpangs as $p)
                                <tr wire:key="pnp-{{ $p->ID_Penumpang }}">
                                    <td class="px-5 py-3 font-medium text-slate-900">{{ $p->Nama_Lengkap }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ ucfirst($p->Tipe_Penumpang) }}</td>
                                    <td class="px-5 py-3">
                                        @if ($p->Checkin_Pada)
                                            <span class="text-emerald-700">Naik {{ $p->Checkin_Pada->format('H:i') }}</span>
                                        @else
                                            <span class="text-slate-400">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        @if ($p->Checkin_Pada)
                                            <button type="button" wire:click="batalCheckin({{ $p->ID_Penumpang }})" wire:confirm="Batalkan check-in {{ $p->Nama_Lengkap }}?" class="text-xs font-medium text-slate-500 hover:text-rose-600">Batalkan</button>
                                        @elseif (! $alasan)
                                            <button type="button" wire:click="checkinSatu({{ $p->ID_Penumpang }})" class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-emerald-600 hover:bg-emerald-700">Check-in</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (! $alasan && $sudah < $total)
                        <div class="p-5 border-t border-slate-100">
                            <button type="button" wire:click="checkinSemua" class="w-full px-4 py-3 font-semibold text-white rounded-lg bg-emerald-600 hover:bg-emerald-700">
                                Check-in semua ({{ $total - $sudah }} penumpang)
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        (function () {
            const btn = document.getElementById('btn-kamera');
            const status = document.getElementById('qr-status');
            let scanner = null;
            let terakhir = '';

            btn.addEventListener('click', async function () {
                if (scanner) {
                    await scanner.stop();
                    scanner = null;
                    btn.textContent = 'Nyalakan kamera';
                    status.textContent = 'Kamera dimatikan.';
                    return;
                }
                if (typeof Html5Qrcode === 'undefined') {
                    status.textContent = 'Library scanner gagal dimuat. Periksa koneksi internet, atau ketik kode booking.';
                    return;
                }
                scanner = new Html5Qrcode('qr-reader');
                try {
                    await scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: 220 }, (teks) => {
                        if (teks === terakhir) return; // hindari memproses QR yang sama berulang kali
                        terakhir = teks;
                        setTimeout(() => terakhir = '', 3000);
                        status.textContent = 'QR terbaca.';
                        @this.call('cari', teks);
                    });
                    btn.textContent = 'Matikan kamera';
                    status.textContent = 'Arahkan kamera ke QR code e-tiket.';
                } catch (e) {
                    scanner = null;
                    status.textContent = 'Kamera tidak bisa dibuka: ' + e + '. Kamera hanya bisa dipakai di https atau localhost.';
                }
            });
        })();
    </script>
@endpush
