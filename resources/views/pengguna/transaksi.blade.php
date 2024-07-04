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
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Nama Pengguna</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Tanggal Pesanan</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Metode Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanans as $index => $pesanan)
                                <tr class="transition-all hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanan->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanan->Tanggal_Pesanan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ substr($pesanan->Waktu, 0, 5) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($pesanan->Total_Harga, 0, ',', '.') }}.000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pesanan->Metode_Bayar }}</td>
                                </tr>
                            @endforeach
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
