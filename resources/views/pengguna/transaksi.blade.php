@extends('layout.main')

@section('container')
    <div class="w-full h-full bg-scroll bg-no-repeat bg-cover bg-bground1">
        <main class="flex justify-center">
            <div class="overflow-x-auto">
                @isset($pesanans)
                    <table class="w-full max-w-5xl mx-auto my-10 text-center border border-gray-300 rounded-lg shadow-md bg-white table-auto">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Kode Booking</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Rute</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Keberangkatan</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Penumpang</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $pesanan)
                                @php $rute = $pesanan->rute; @endphp
                                <tr class="transition-all hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanans->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4 font-mono whitespace-nowrap">{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $rute ? \Carbon\Carbon::parse($rute->Tanggal)->format('d-m-Y') . ' ' . \Carbon\Carbon::parse($rute->Jam)->format('H:i') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanan->penumpangs_count }} ({{ $pesanan->Kelas ?? '-' }})</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ rupiah($pesanan->Total_Harga) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanan->status_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a class="text-blue-600 hover:underline" href="{{ route('invoices.show', $pesanan->ID_Pesanan) }}">Lihat Invoice</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Tampilkan link pagination --}}
                    {{ $pesanans->links() }}

                @else
                    <p>Tidak ada data pesanan tersedia.</p>
                @endisset
            </div>
        </main>
    </div>
@endsection
