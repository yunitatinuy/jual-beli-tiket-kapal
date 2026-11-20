@php
    use App\Models\Pesanan;
    use Carbon\Carbon;
    $badge = fn ($status) => match ($status) {
        Pesanan::STATUS_LUNAS => 'bg-green-500',
        Pesanan::STATUS_DIBATALKAN => 'bg-red-500',
        default => 'bg-yellow-500',
    };
@endphp
<div class="flex flex-col flex-grow mt-8 ms-10 me-10 font-inter">
    <span class="flex items-center p-2 text-gray-900 rounded-lg">
        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-9 h-9" alt="">
        <span class="flex-1 text-3xl font-bold ms-4 whitespace-nowrap">Data Pesanan</span>
    </span>

    {{-- Filter --}}
    <div class="flex flex-wrap items-end gap-3 mt-5">
        <div>
            <label for="q" class="block mb-1 text-xs font-medium text-gray-700">Cari</label>
            <input id="q" type="search" wire:model.live.debounce.400ms="search" placeholder="Kode booking, nama, email..."
                class="w-72 p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="status" class="block mb-1 text-xs font-medium text-gray-700">Status</label>
            <select id="status" wire:model.live="status" class="p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50">
                <option value="">Semua status</option>
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tanggal" class="block mb-1 text-xs font-medium text-gray-700">Tanggal berangkat</label>
            <input id="tanggal" type="date" wire:model.live="tanggal" class="p-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50">
        </div>
        @if ($status !== '' || $search !== '' || $tanggal !== '')
            <button type="button" wire:click="resetFilter" class="px-4 py-2.5 text-sm font-medium bg-white border rounded-lg hover:bg-gray-50">Reset filter</button>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="mt-5 mb-10 overflow-x-auto bg-white border rounded-xl">
        <table class="min-w-full text-sm text-center divide-y divide-neutral-200">
            <thead class="bg-[#ff9853be] font-medium text-gray-900">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Kode Booking</th>
                    <th class="px-4 py-3">Pemesan</th>
                    <th class="px-4 py-3">Rute</th>
                    <th class="px-4 py-3">Keberangkatan</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Penumpang</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($dataPesanan as $pesanan)
                    @php $rute = $pesanan->rute; @endphp
                    <tr wire:key="pesanan-{{ $pesanan->ID_Pesanan }}">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $dataPesanan->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-mono whitespace-nowrap">{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $pesanan->user?->name ?? '-' }}<br><span class="text-xs text-gray-500">{{ $pesanan->user?->email }}</span></td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $rute ? Carbon::parse($rute->Tanggal)->format('d-m-Y') . ' ' . substr($rute->Jam, 0, 5) : '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $pesanan->Kelas ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $pesanan->penumpangs_count }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ rupiah($pesanan->Total_Harga) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap"><span class="px-2 py-1 rounded {{ $badge($pesanan->Status) }} text-xs font-medium uppercase text-white">{{ $pesanan->status_label }}</span></td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <button type="button" wire:click="lihat({{ $pesanan->ID_Pesanan }})" class="px-3 py-1.5 text-xs font-medium text-white rounded-lg bg-sky-500 hover:bg-sky-600">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="px-4 py-4 text-gray-500">Tidak ada pesanan yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $dataPesanan->links() }}</div>
    </div>

    {{-- Detail pesanan --}}
    @if ($detail)
        @php $r = $detail->rute; @endphp
        <div class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto bg-black/50" wire:click.self="tutup">
            <div class="w-full max-w-2xl p-6 mt-10 bg-white shadow-xl rounded-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Kode Booking</p>
                        <p class="font-mono text-2xl font-bold">{{ $detail->Kode_Booking ?? $detail->ID_Pesanan }}</p>
                    </div>
                    <span class="px-2 py-1 rounded {{ $badge($detail->Status) }} text-xs font-medium uppercase text-white">{{ $detail->status_label }}</span>
                </div>

                <dl class="grid grid-cols-2 mt-4 text-sm gap-x-4 gap-y-2">
                    <dt class="text-gray-500">Pemesan</dt><dd>{{ $detail->user?->name }} ({{ $detail->user?->email }})</dd>
                    <dt class="text-gray-500">Rute</dt><dd>{{ $r?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $r?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</dd>
                    <dt class="text-gray-500">Kapal</dt><dd>{{ $r?->kapal?->Nama_Kapal ?? '-' }}</dd>
                    <dt class="text-gray-500">Berangkat</dt><dd>{{ $r ? Carbon::parse($r->Tanggal)->format('d-m-Y') . ' ' . substr($r->Jam, 0, 5) : '-' }}</dd>
                    <dt class="text-gray-500">Kelas</dt><dd>{{ $detail->Kelas }}</dd>
                    <dt class="text-gray-500">Total</dt><dd class="font-semibold">{{ rupiah($detail->Total_Harga) }}</dd>
                    <dt class="text-gray-500">Dipesan</dt><dd>{{ Carbon::parse($detail->Tanggal_Pesanan)->format('d-m-Y') }} {{ substr((string) $detail->Waktu, 0, 5) }}</dd>
                    @if ($detail->Status === Pesanan::STATUS_LUNAS)
                        <dt class="text-gray-500">Dibayar</dt><dd>{{ $detail->Dibayar_Pada?->format('d-m-Y H:i') }} via {{ $detail->Metode_Bayar }}</dd>
                        <dt class="text-gray-500">ID Transaksi</dt><dd class="font-mono text-xs">{{ $detail->Midtrans_Transaction_Id ?? '-' }}</dd>
                    @elseif ($detail->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
                        <dt class="text-gray-500">Batas bayar</dt><dd>{{ $detail->Batas_Bayar?->format('d-m-Y H:i') ?? '-' }}</dd>
                    @endif
                </dl>

                <h3 class="mt-5 mb-2 text-sm font-semibold uppercase">Penumpang</h3>
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase">
                        <tr><th class="py-1">No</th><th class="py-1">Nama</th><th class="py-1">Tipe</th><th class="py-1">L/P</th><th class="py-1">No. HP</th><th class="py-1">Harga</th><th class="py-1">Check-in</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($detail->penumpangs as $p)
                            <tr class="border-t">
                                <td class="py-1">{{ $loop->iteration }}</td>
                                <td class="py-1">{{ $p->Nama_Lengkap }}</td>
                                <td class="py-1">{{ ucfirst($p->Tipe_Penumpang) }}</td>
                                <td class="py-1">{{ ucfirst($p->Jenis_Kelamin) }}</td>
                                <td class="py-1">{{ $p->Telepon }}</td>
                                <td class="py-1">{{ rupiah($p->Harga) }}</td>
                                <td class="py-1">{!! $p->Checkin_Pada ? '<span class="text-emerald-700">' . e($p->Checkin_Pada->format('d/m H:i')) . '</span>' : '<span class="text-slate-400">-</span>' !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end gap-2 mt-6">
                    @if ($detail->Status === Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
                        <button type="button" wire:click="batalkan({{ $detail->ID_Pesanan }})"
                            wire:confirm="Batalkan pesanan {{ $detail->Kode_Booking }}? Kursi akan dikembalikan."
                            class="px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50">Batalkan pesanan</button>
                    @endif
                    <button type="button" wire:click="tutup" class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">Tutup</button>
                </div>
            </div>
        </div>
    @endif
</div>
