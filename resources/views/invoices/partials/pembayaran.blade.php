{{-- Kotak status & aksi pembayaran di halaman invoice --}}
@php
    use App\Models\Pesanan;
@endphp

@if ($pesanan->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
    <section class="mb-6 overflow-hidden bg-white border shadow-sm no-print rounded-2xl border-amber-200">
        <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex items-start gap-4">
                <span class="flex items-center justify-center flex-none w-11 h-11 rounded-full bg-amber-100 text-amber-600">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-500">Menunggu pembayaran</p>
                    <p class="text-2xl font-bold text-slate-900">{{ rupiah($pesanan->Total_Harga) }}</p>
                    @if ($pesanan->Batas_Bayar)
                        <p class="mt-1 text-sm text-slate-600">
                            Bayar sebelum <span class="font-semibold text-slate-900">{{ $pesanan->Batas_Bayar->translatedFormat('d M Y, H:i') }} WIB</span>
                        </p>
                    @endif
                </div>
            </div>

            @if ($pesanan->Batas_Bayar)
                <div class="text-center sm:text-right">
                    <p class="text-xs font-semibold tracking-wide uppercase text-slate-400">Sisa waktu</p>
                    <p id="countdown" data-deadline="{{ $pesanan->Batas_Bayar->toIso8601String() }}"
                        class="font-mono text-3xl font-bold tabular-nums text-amber-600">--:--</p>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 px-5 py-4 border-t lg:flex-row lg:items-center lg:justify-between sm:px-6 bg-amber-50/60 border-amber-100">
            <p class="text-xs text-slate-600 lg:max-w-xs">
                @if ($midtransSiap)
                    Bayar lewat Virtual Account, QRIS, GoPay, ShopeePay, dan lainnya. Pesanan dibatalkan otomatis jika melewati batas waktu.
                @else
                    <span class="font-medium text-rose-700">Pembayaran online belum dikonfigurasi. Hubungi admin.</span>
                @endif
            </p>
            <div class="flex flex-wrap gap-2 lg:flex-nowrap lg:flex-none">
                <form method="POST" action="{{ route('pesanan.batal', $pesanan->ID_Pesanan) }}"
                    onsubmit="return confirm('Batalkan pesanan ini? Kursi akan dilepas.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-white border rounded-lg text-rose-700 border-rose-200 hover:bg-rose-50">
                        Batalkan
                    </button>
                </form>
                <form method="POST" action="{{ route('pesanan.cek-status', $pesanan->ID_Pesanan) }}" id="form-cek-status">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-white border rounded-lg text-slate-700 border-slate-300 hover:bg-slate-50">
                        Saya sudah bayar
                    </button>
                </form>
                @if ($midtransSiap)
                    <button type="button" id="btn-bayar"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg shadow-sm bg-[#f07c2c] hover:bg-[#de6d1f] disabled:opacity-50 disabled:cursor-wait">
                        Bayar Sekarang
                    </button>
                @endif
            </div>
        </div>
        <p id="bayar-error" class="hidden px-6 pb-4 text-sm text-rose-700 bg-amber-50/60"></p>
    </section>

    @if ($midtransSiap)
        <script src="{{ $midtransSnapJs }}" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            (function () {
                const btn = document.getElementById('btn-bayar');
                const errorBox = document.getElementById('bayar-error');
                const cekStatus = () => document.getElementById('form-cek-status').submit();
                const tampilkanError = (pesan) => {
                    errorBox.textContent = pesan;
                    errorBox.classList.remove('hidden');
                    btn.disabled = false;
                    btn.textContent = 'Bayar Sekarang';
                };

                btn.addEventListener('click', async function () {
                    btn.disabled = true;
                    btn.textContent = 'Memuat...';
                    errorBox.classList.add('hidden');
                    try {
                        const res = await fetch(@json(route('pesanan.bayar', $pesanan->ID_Pesanan)), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json',
                            },
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Gagal memulai pembayaran.');

                        window.snap.pay(data.token, {
                            onSuccess: cekStatus,
                            onPending: cekStatus,
                            onError: () => tampilkanError('Pembayaran gagal. Silakan coba lagi.'),
                            onClose: () => { btn.disabled = false; btn.textContent = 'Bayar Sekarang'; },
                        });
                    } catch (e) {
                        tampilkanError(e.message);
                    }
                });
            })();
        </script>
    @endif

    <script>
        (function () {
            const el = document.getElementById('countdown');
            if (!el) return;
            const deadline = new Date(el.dataset.deadline).getTime();
            const pad = (n) => String(n).padStart(2, '0');
            const tick = () => {
                const s = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
                const j = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), d = s % 60;
                el.textContent = (j ? pad(j) + ':' : '') + pad(m) + ':' + pad(d);
                if (s < 300) el.classList.replace('text-amber-600', 'text-rose-600');
                if (s === 0) { clearInterval(timer); setTimeout(() => location.reload(), 2000); }
            };
            const timer = setInterval(tick, 1000);
            tick();
        })();
    </script>
@elseif ($pesanan->Status === Pesanan::STATUS_LUNAS)
    <section class="flex items-start gap-4 p-5 mb-6 border no-print rounded-2xl border-emerald-200 bg-emerald-50">
        <span class="flex items-center justify-center flex-none rounded-full w-11 h-11 bg-emerald-100 text-emerald-600">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
        </span>
        <div>
            <p class="font-semibold text-emerald-900">Pembayaran berhasil &mdash; tiket Anda aktif</p>
            <p class="mt-0.5 text-sm text-emerald-800">
                Dibayar {{ $pesanan->Dibayar_Pada?->translatedFormat('d M Y, H:i') }} WIB via {{ $pesanan->Metode_Bayar }}.
                Tunjukkan kode booking <span class="font-mono font-semibold">{{ $pesanan->Kode_Booking }}</span> saat check-in.
            </p>
        </div>
    </section>
@elseif ($pesanan->Status === Pesanan::STATUS_DIBATALKAN)
    <section class="flex items-start gap-4 p-5 mb-6 border no-print rounded-2xl border-rose-200 bg-rose-50">
        <span class="flex items-center justify-center flex-none rounded-full w-11 h-11 bg-rose-100 text-rose-600">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </span>
        <div>
            <p class="font-semibold text-rose-900">Pesanan dibatalkan</p>
            <p class="mt-0.5 text-sm text-rose-800">Pesanan ini tidak dibayar sampai batas waktu atau dibatalkan. Silakan pesan ulang jika masih ingin berangkat.</p>
        </div>
    </section>
@endif
