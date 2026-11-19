@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        $rute = $pesanan->rute;
    @endphp
    <!-- Invoice -->
    <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="mx-auto mb-5 sm:w-11/12 lg:w-3/4">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">{{ session('info') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">{{ session('error') }}</div>
            @endif

            @include('invoices.partials.pembayaran')

            <!-- Card -->
            <div class="flex flex-col p-4 bg-white shadow-md sm:p-10 rounded-xl">
                <div class="flex justify-between">
                    <div>
                        <img src="/img/logo3.png" alt="logo" class="w-20 h-20 ms-4">
                        <h1 class="mt-2 text-lg text-blue-600 md:text-xl font-marko">Anang Ferry</h1>
                    </div>
                    <div class="text-end">
                        <h2 class="text-2xl font-semibold text-gray-800 md:text-3xl">Invoice</h2>
                        <span class="block mt-1 font-mono text-gray-700">{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</span>
                        <span class="inline-block px-2 py-1 mt-2 text-xs font-semibold rounded
                            {{ $pesanan->Status === \App\Models\Pesanan::STATUS_LUNAS ? 'bg-green-100 text-green-800' : ($pesanan->Status === \App\Models\Pesanan::STATUS_DIBATALKAN ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $pesanan->status_label }}
                        </span>
                    </div>
                </div>

                <div class="grid gap-3 mt-8 sm:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pemesan</h3>
                        <address class="mt-2 not-italic text-gray-500">
                            Nama : {{ $pesanan->user->name }}<br>
                            Email : {{ $pesanan->user->email }}
                        </address>
                    </div>
                    <div class="space-y-2 sm:text-end">
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800">Tanggal Pemesanan :</dt>
                            <dd class="col-span-2 text-gray-500">{{ Carbon::parse($pesanan->Tanggal_Pesanan)->format('d-m-Y') }}</dd>
                        </dl>
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800">Waktu Pemesanan :</dt>
                            <dd class="col-span-2 text-gray-500">{{ substr($pesanan->Waktu, 0, 5) }}</dd>
                        </dl>
                    </div>
                </div>

                <div class="mt-6">
                    <!-- Data Perjalanan -->
                    <div class="p-4 space-y-4 border border-gray-200 rounded-lg">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="text-xs font-medium text-gray-500 uppercase sm:col-span-2">Rute</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Nama Kapal</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Keberangkatan</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-end">Kelas</div>
                        </div>
                        <div class="hidden border-b border-gray-200 sm:block"></div>
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                            <div class="col-span-full sm:col-span-2">
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Rute</h5>
                                <p class="font-medium text-gray-800">
                                    {{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} &rarr; {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Nama Kapal</h5>
                                <p class="text-gray-800">{{ $rute?->kapal?->Nama_Kapal ?? '-' }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Keberangkatan</h5>
                                <p class="text-gray-800">
                                    @if ($rute)
                                        {{ Carbon::parse($rute->Tanggal)->format('d-m-Y') }} {{ Carbon::parse($rute->Jam)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Kelas</h5>
                                <p class="text-gray-800 sm:text-end">{{ $pesanan->Kelas ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Penumpang -->
                    <div class="p-4 mt-4 border border-gray-200 rounded-lg">
                        <h3 class="mb-2 text-sm font-semibold text-gray-800 uppercase">Penumpang</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="text-xs text-gray-500 uppercase">
                                    <tr>
                                        <th class="py-1 pr-4">No</th>
                                        <th class="py-1 pr-4">Nama Lengkap</th>
                                        <th class="py-1 pr-4">Tipe</th>
                                        <th class="py-1 pr-4">Jenis Kelamin</th>
                                        <th class="py-1 pr-4">No. HP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pesanan->penumpangs as $p)
                                        <tr class="border-t">
                                            <td class="py-1 pr-4">{{ $loop->iteration }}</td>
                                            <td class="py-1 pr-4">{{ $p->Nama_Lengkap }}</td>
                                            <td class="py-1 pr-4">{{ ucfirst($p->Tipe_Penumpang) }}</td>
                                            <td class="py-1 pr-4">{{ ucfirst($p->Jenis_Kelamin) }}</td>
                                            <td class="py-1 pr-4">{{ $p->Telepon }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Rincian Harga -->
                    <div class="p-4 mt-4 space-y-4 border border-gray-200 rounded-lg">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="text-xs font-medium text-gray-500 uppercase sm:col-span-2">Jenis Penumpang</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Jumlah</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Harga Tiket</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-end">Total</div>
                        </div>
                        <div class="hidden border-b border-gray-200 sm:block"></div>
                        @foreach ($rincian as $r)
                            <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                                <div class="col-span-full sm:col-span-2">
                                    <p class="font-medium text-gray-800">{{ $r['tipe'] }}</p>
                                </div>
                                <div><p class="text-gray-800">{{ $r['jumlah'] }}</p></div>
                                <div><p class="text-gray-800">{{ rupiah($r['harga']) }}</p></div>
                                <div><p class="text-gray-800 sm:text-end">{{ rupiah($r['subtotal']) }}</p></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex mt-8 sm:justify-end">
                        <div class="w-full max-w-2xl space-y-2 sm:text-end">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Total Harga :</dt>
                                <dd class="col-span-2 font-semibold text-gray-800">{{ rupiah($pesanan->Total_Harga) }}</dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Metode Bayar :</dt>
                                <dd class="col-span-2 text-gray-500">{{ $pesanan->Metode_Bayar }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 sm:mt-12">
                        <h4 class="text-lg font-semibold text-gray-800">Terima Kasih!</h4>
                        <p class="text-gray-500">Jika Anda memiliki pertanyaan mengenai Invoice ini, gunakan informasi kontak berikut:</p>
                        <div class="mt-2">
                            <p class="block text-sm font-medium text-gray-800">anangferry@gmail.com</p>
                            <p class="block text-sm font-medium text-gray-800">0896-6969-696</p>
                        </div>
                    </div>

                    <p class="mt-5 text-sm text-gray-500">© {{ date('Y') }} Anang Ferry.</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end mt-6 gap-x-3 print:hidden">
                    <a class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 align-middle transition-all bg-white border rounded-lg shadow-sm hover:bg-gray-50"
                        href="{{ route('invoice.pdf', $pesanan->ID_Pesanan) }}">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" x2="12" y1="15" y2="3" />
                        </svg>
                        Invoice PDF
                    </a>
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg gap-x-2 hover:bg-blue-700">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect width="12" height="8" x="6" y="14" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
