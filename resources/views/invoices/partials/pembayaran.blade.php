{{-- Kotak status & aksi pembayaran di halaman invoice --}}
@php
    use App\Models\Pesanan;
@endphp

@if ($pesanan->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
    <div class="p-5 mb-5 border border-yellow-300 rounded-xl bg-yellow-50 print:hidden">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-yellow-900">Menunggu pembayaran: {{ rupiah($pesanan->Total_Harga) }}</p>
                @if ($pesanan->Batas_Bayar)
                    <p class="text-sm text-yellow-900">
                        Bayar sebelum <strong>{{ $pesanan->Batas_Bayar->format('d-m-Y H:i') }} WIB</strong>
                        (sisa <span id="countdown" data-deadline="{{ $pesanan->Batas_Bayar->toIso8601String() }}">-</span>).
                        Setelah itu pesanan dibatalkan otomatis.
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap gap-2">
                @if ($midtransSiap)
                    <button type="button" id="btn-bayar"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        Bayar Sekarang
                    </button>
                @endif
                <form method="POST" action="{{ route('pesanan.cek-status', $pesanan->ID_Pesanan) }}" id="form-cek-status">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-white border rounded-lg hover:bg-gray-50">
                        Saya sudah bayar
                    </button>
                </form>
                <form method="POST" action="{{ route('pesanan.batal', $pesanan->ID_Pesanan) }}"
                    onsubmit="return confirm('Batalkan pesanan ini? Kursi akan dilepas.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
            </div>
        </div>

        @unless ($midtransSiap)
            <p class="mt-3 text-sm text-red-700">
                Pembayaran online belum dikonfigurasi (MIDTRANS_SERVER_KEY / MIDTRANS_CLIENT_KEY kosong). Hubungi admin.
            </p>
        @endunless
        <p id="bayar-error" class="hidden mt-3 text-sm text-red-700"></p>
    </div>

    @if ($midtransSiap)
        <script src="{{ $midtransSnapJs }}" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            (function () {
                const btn = document.getElementById('btn-bayar');
                const errorBox = document.getElementById('bayar-error');
                const cekStatus = () => document.getElementById('form-cek-status').submit();

                btn.addEventListener('click', async function () {
                    btn.disabled = true;
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
                            onError: function () {
                                errorBox.textContent = 'Pembayaran gagal. Silakan coba lagi.';
                                errorBox.classList.remove('hidden');
                                btn.disabled = false;
                            },
                            onClose: function () { btn.disabled = false; },
                        });
                    } catch (e) {
                        errorBox.textContent = e.message;
                        errorBox.classList.remove('hidden');
                        btn.disabled = false;
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
            const tick = () => {
                const s = Math.max(0, Math.floor((deadline - Date.now()) / 1000));
                el.textContent = Math.floor(s / 60) + ' menit ' + String(s % 60).padStart(2, '0') + ' detik';
                if (s === 0) { clearInterval(timer); setTimeout(() => location.reload(), 2000); }
            };
            const timer = setInterval(tick, 1000);
            tick();
        })();
    </script>
@elseif ($pesanan->Status === Pesanan::STATUS_LUNAS)
    <div class="p-5 mb-5 border border-green-300 rounded-xl bg-green-50">
        <p class="font-semibold text-green-900">Lunas &mdash; tiket aktif</p>
        <p class="text-sm text-green-900">
            Dibayar {{ $pesanan->Dibayar_Pada?->format('d-m-Y H:i') }} WIB via {{ $pesanan->Metode_Bayar }}.
            Tunjukkan kode booking <strong class="font-mono">{{ $pesanan->Kode_Booking }}</strong> saat check-in.
        </p>
    </div>
@elseif ($pesanan->Status === Pesanan::STATUS_DIBATALKAN)
    <div class="p-5 mb-5 border border-red-300 rounded-xl bg-red-50">
        <p class="font-semibold text-red-900">Pesanan dibatalkan</p>
        <p class="text-sm text-red-900">Pesanan ini tidak dibayar sampai batas waktu atau dibatalkan. Silakan pesan ulang jika masih ingin berangkat.</p>
    </div>
@endif
