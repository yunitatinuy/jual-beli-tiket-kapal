@php
    use App\Models\Pesanan;
    use Carbon\Carbon;
    $badge = fn ($status) => match ($status) {
        Pesanan::STATUS_LUNAS => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        Pesanan::STATUS_DIBATALKAN => 'bg-rose-50 text-rose-700 border border-rose-200',
        default => 'bg-amber-50 text-amber-700 border border-amber-200',
    };
@endphp
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-2xl text-[#c8175d]">
            <i class="bi bi-receipt-cutoff"></i>
        </span>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Data Pesanan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola dan pantau seluruh transaksi serta status tiket pemesanan kapal.</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[240px]">
                <label for="q" class="block mb-1 text-xs font-medium text-slate-600">Cari Pesanan</label>
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-3 text-slate-400"></i>
                    <input id="q" type="search" wire:model.live.debounce.400ms="search" placeholder="Kode booking, nama, email..."
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-xl bg-slate-50/60 focus:bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d]">
                </div>
            </div>
            <div>
                <label for="status" class="block mb-1 text-xs font-medium text-slate-600">Status</label>
                <select id="status" wire:model.live="status" class="py-2 px-3 text-xs border border-slate-200 rounded-xl bg-slate-50/60 focus:bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d]">
                    <option value="">Semua status</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tanggal" class="block mb-1 text-xs font-medium text-slate-600">Tanggal berangkat</label>
                <input id="tanggal" type="date" wire:model.live="tanggal" class="py-2 px-3 text-xs border border-slate-200 rounded-xl bg-slate-50/60 focus:bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d]">
            </div>
            @if ($status !== '' || $search !== '' || $tanggal !== '')
                <button type="button" wire:click="resetFilter" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                    <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
                </button>
            @endif
        </div>
    </div>

    {{-- Tabel Pesanan --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">Kode Booking</th>
                        <th class="px-4 py-3 font-semibold">Pemesan</th>
                        <th class="px-4 py-3 font-semibold">Rute</th>
                        <th class="px-4 py-3 font-semibold">Keberangkatan</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-center font-semibold">Penumpang</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dataPesanan as $pesanan)
                        @php $rute = $pesanan->rute; @endphp
                        <tr wire:key="pesanan-{{ $pesanan->ID_Pesanan }}" class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $dataPesanan->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-800 whitespace-nowrap">#{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <p class="font-semibold text-slate-800">{{ $pesanan->user?->name ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $pesanan->user?->email }}</p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-700">
                                {{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }}
                                <i class="bi bi-arrow-right mx-1 text-slate-400"></i>
                                {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                {{ $rute ? Carbon::parse($rute->Tanggal)->format('d-m-Y') . ' ' . substr($rute->Jam, 0, 5) . ' WIB' : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600 font-medium">{{ $pesanan->Kelas ?? '-' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap font-semibold text-slate-700">{{ $pesanan->penumpangs_count }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-bold text-slate-800">{{ rupiah($pesanan->Total_Harga) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badge($pesanan->Status) }}">
                                    {{ $pesanan->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <button type="button" wire:click="lihat({{ $pesanan->ID_Pesanan }})"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-[#c8175d] hover:bg-[#a6104b] transition shadow-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                <i class="bi bi-inbox text-3xl text-slate-300"></i>
                                <p class="mt-2 text-xs">Tidak ada data pesanan yang cocok.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 p-4">{{ $dataPesanan->links() }}</div>
    </div>

    {{-- Detail Pesanan Modal --}}
    @if ($detail)
        @php $r = $detail->rute; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="tutup">
            <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white shadow-2xl rounded-2xl border border-slate-200 p-6 space-y-5">
                <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Detail Pesanan</span>
                        <p class="font-mono text-2xl font-bold text-slate-800">#{{ $detail->Kode_Booking ?? $detail->ID_Pesanan }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $badge($detail->Status) }}">
                        {{ $detail->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-xs bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                    <div><span class="text-slate-400 font-medium">Pemesan:</span> <p class="font-semibold text-slate-800">{{ $detail->user?->name }} ({{ $detail->user?->email }})</p></div>
                    <div><span class="text-slate-400 font-medium">Rute:</span> <p class="font-semibold text-slate-800">{{ $r?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $r?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</p></div>
                    <div><span class="text-slate-400 font-medium">Kapal:</span> <p class="font-semibold text-slate-800">{{ $r?->kapal?->Nama_Kapal ?? '-' }}</p></div>
                    <div><span class="text-slate-400 font-medium">Berangkat:</span> <p class="font-semibold text-slate-800">{{ $r ? Carbon::parse($r->Tanggal)->format('d-m-Y') . ' ' . substr($r->Jam, 0, 5) . ' WIB' : '-' }}</p></div>
                    <div><span class="text-slate-400 font-medium">Kelas:</span> <p class="font-semibold text-slate-800">{{ $detail->Kelas }}</p></div>
                    <div><span class="text-slate-400 font-medium">Total Harga:</span> <p class="font-bold text-[#c8175d] text-sm">{{ rupiah($detail->Total_Harga) }}</p></div>
                    <div><span class="text-slate-400 font-medium">Waktu Pesan:</span> <p class="text-slate-700">{{ Carbon::parse($detail->Tanggal_Pesanan)->format('d-m-Y') }} {{ substr((string) $detail->Waktu, 0, 5) }} WIB</p></div>
                    @if ($detail->Status === Pesanan::STATUS_LUNAS)
                        <div><span class="text-slate-400 font-medium">Dibayar Pada:</span> <p class="text-emerald-700 font-semibold">{{ $detail->Dibayar_Pada?->format('d-m-Y H:i') }} WIB</p></div>
                        <div class="col-span-2"><span class="text-slate-400 font-medium">ID Transaksi Midtrans:</span> <p class="font-mono text-slate-600">{{ $detail->Midtrans_Transaction_Id ?? '-' }}</p></div>
                    @elseif ($detail->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
                        <div class="col-span-2"><span class="text-slate-400 font-medium">Batas Bayar:</span> <p class="text-amber-700 font-semibold">{{ $detail->Batas_Bayar?->format('d-m-Y H:i') ?? '-' }} WIB</p></div>
                    @endif
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">Daftar Penumpang</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-[10px] text-slate-500 uppercase">
                                <tr>
                                    <th class="px-3 py-2">No</th>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">Tipe</th>
                                    <th class="px-3 py-2">L/P</th>
                                    <th class="px-3 py-2">Telepon</th>
                                    <th class="px-3 py-2">Harga</th>
                                    <th class="px-3 py-2">Check-in</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($detail->penumpangs as $p)
                                    <tr>
                                        <td class="px-3 py-2 text-slate-400">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2 font-semibold text-slate-800">{{ $p->Nama_Lengkap }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ ucfirst($p->Tipe_Penumpang) }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ ucfirst($p->Jenis_Kelamin) }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ $p->Telepon }}</td>
                                        <td class="px-3 py-2 font-medium text-slate-800">{{ rupiah($p->Harga) }}</td>
                                        <td class="px-3 py-2">{!! $p->Checkin_Pada ? '<span class="text-emerald-700 font-semibold">' . e($p->Checkin_Pada->format('d/m H:i')) . '</span>' : '<span class="text-slate-400">-</span>' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    @if ($detail->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
                        <button type="button" wire:click="batalkan({{ $detail->ID_Pesanan }})"
                            wire:confirm="Batalkan pesanan {{ $detail->Kode_Booking }}? Kursi akan dikembalikan."
                            class="px-4 py-2 text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition">
                            Batalkan pesanan
                        </button>
                    @endif
                    <button type="button" wire:click="tutup" class="px-5 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
