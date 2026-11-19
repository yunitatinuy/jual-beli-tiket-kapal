@php
    use App\Models\Pesanan;
    $badge = fn ($status) => match ($status) {
        Pesanan::STATUS_LUNAS => 'bg-green-100 text-green-800',
        Pesanan::STATUS_DIBATALKAN => 'bg-red-100 text-red-800',
        default => 'bg-yellow-100 text-yellow-800',
    };
@endphp
<div class="flex flex-col flex-grow mt-10 mb-10 ms-10 me-10 font-inter">
    <span class="flex items-center p-2 text-gray-900 rounded-lg">
        <img src="/img/icons/dashboard.png" class="flex-shrink-0 w-8 h-8" alt="">
        <span class="flex-1 text-3xl font-bold ms-4 whitespace-nowrap">Dashboard</span>
        <span class="text-sm text-gray-500">{{ now()->format('d-m-Y') }}</span>
    </span>

    {{-- Kartu ringkasan --}}
    <div class="grid gap-4 mt-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-5 text-white rounded-xl bg-emerald-500">
            <p class="text-sm font-medium opacity-90">Pendapatan bulan ini</p>
            <p class="mt-2 text-2xl font-bold">{{ rupiah($ringkasan['pendapatanBulanIni']) }}</p>
            <p class="mt-1 text-xs opacity-90">Total sepanjang waktu: {{ rupiah($ringkasan['pendapatanTotal']) }}</p>
        </div>
        <div class="p-5 text-white rounded-xl bg-sky-500">
            <p class="text-sm font-medium opacity-90">Pesanan hari ini</p>
            <p class="mt-2 text-4xl font-bold">{{ $ringkasan['pesananHariIni'] }}</p>
            <p class="mt-1 text-xs opacity-90">{{ $ringkasan['lunasHariIni'] }} pembayaran masuk hari ini</p>
        </div>
        <a href="/admin/pesanan?status={{ Pesanan::STATUS_MENUNGGU_PEMBAYARAN }}" class="p-5 text-white rounded-xl bg-amber-500 hover:bg-amber-600">
            <p class="text-sm font-medium opacity-90">Menunggu pembayaran</p>
            <p class="mt-2 text-4xl font-bold">{{ $ringkasan['menungguBayar'] }}</p>
            <p class="mt-1 text-xs opacity-90">Lihat daftar &rarr;</p>
        </a>
        <a href="/admin/pengguna" class="p-5 text-white rounded-xl bg-indigo-500 hover:bg-indigo-600">
            <p class="text-sm font-medium opacity-90">Pengguna terdaftar</p>
            <p class="mt-2 text-4xl font-bold">{{ $ringkasan['totalPengguna'] }}</p>
            <p class="mt-1 text-xs opacity-90">Lihat pengguna &rarr;</p>
        </a>
    </div>

    {{-- Keberangkatan hari ini --}}
    <h2 class="mt-10 mb-3 text-xl font-bold">Keberangkatan hari ini</h2>
    <div class="overflow-x-auto bg-white border rounded-xl">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-[#ff9853be]">
                <tr>
                    <th class="px-4 py-3">Jam</th>
                    <th class="px-4 py-3">Rute</th>
                    <th class="px-4 py-3">Kapal</th>
                    <th class="px-4 py-3 text-center">Penumpang lunas</th>
                    <th class="px-4 py-3 text-center">Sisa kursi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($keberangkatan as $rute)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ substr($rute->Jam, 0, 5) }}</td>
                        <td class="px-4 py-3">{{ $rute->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $rute->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $rute->kapal?->Nama_Kapal ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">{{ (int) $rute->penumpang_lunas }}</td>
                        <td class="px-4 py-3 text-center">{{ (int) $rute->sisa_kursi }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada keberangkatan hari ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pesanan terbaru --}}
    <div class="flex items-center justify-between mt-10 mb-3">
        <h2 class="text-xl font-bold">Pesanan terbaru</h2>
        <a href="/admin/pesanan" class="text-sm font-medium text-sky-700 hover:underline">Semua pesanan &rarr;</a>
    </div>
    <div class="overflow-x-auto bg-white border rounded-xl">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-[#ff9853be]">
                <tr>
                    <th class="px-4 py-3">Kode Booking</th>
                    <th class="px-4 py-3">Pemesan</th>
                    <th class="px-4 py-3">Rute</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pesananTerbaru as $p)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $p->Kode_Booking ?? $p->ID_Pesanan }}</td>
                        <td class="px-4 py-3">{{ $p->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $p->rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $p->rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ rupiah($p->Total_Harga) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded {{ $badge($p->Status) }}">{{ $p->status_label }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
