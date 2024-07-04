@extends('layout.main')

@section('container')
    <!-- Invoice -->
    <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="mx-auto mb-5 sm:w-11/12 lg:w-3/4">
            <!-- Card -->
            <div class="flex flex-col p-4 bg-white shadow-md sm:p-10 rounded-xl">
                <!-- Grid -->
                <div class="flex justify-between">
                    <div>
                        <img src="/img/logo3.png" alt="logo" class="w-20 h-20 ms-4">

                        <h1 class="mt-2 text-lg text-blue-600 md:text-xl font-marko">Anang Ferry</h1>
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl font-semibold text-gray-800 md:text-3xl">Invoice</h2>
                        <span class="block mt-1 text-gray-500">{{ $pesanan->ID_Pesanan }}</span>
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="grid gap-3 mt-8 sm:grid-cols-2">
                    <div>
                        <address class="mt-2 not-italic text-gray-500">
                            Nama Pemesan : {{ $pesanan->user->name }}<br>
                            Email : {{ $pesanan->user->email }}
                        </address>
                        <h3 class="text-lg font-semibold text-gray-800">Biodata Penumpang :</h3>
                        <address class="mt-2 not-italic text-gray-500">
                            Nama Lengkap : {{ $penumpang->Nama_Lengkap }}<br>
                            Alamat : {{ $penumpang->Alamat }}<br>
                            No.HP : {{ $penumpang->Telepon }}<br>
                            Jenis Kelamin : {{ $penumpang->Jenis_Kelamin }}<br>
                        </address>
                    </div>
                    <!-- Col -->

                    <div class="space-y-2 sm:text-end">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <<<<<<< HEAD <dt class="col-span-3 font-semibold text-gray-800">Tanggal Pemesanan :</dt>
                                    <dd class="col-span-2 text-gray-500">{{ $pesanan->Tanggal_Pesanan }}</dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Waktu Pemesanan :</dt>
                                <dd class="col-span-2 text-gray-500">{{ $pesanan->Waktu }}</dd>
                                =======
                                <dt class="col-span-3 font-semibold text-gray-800">Total Harga :</dt>
                                <dd class="col-span-2 text-gray-500">
                                    Rp{{ number_format($pesanan->Total_Harga, 0, ',', '.') }}.000</dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Metode Bayar :</dt>
                                <dd class="col-span-2 text-gray-500">{{ $pesanan->Metode_Bayar }}</dd>
                                >>>>>>> f11849d6463f320f0ebb5cf6073db12076627f4a
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Table -->
                <div class="mt-6">
                    <!-- Data Perjalanan -->
                    <div class="p-4 space-y-4 border border-gray-200 rounded-lg">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="text-xs font-medium text-gray-500 uppercase sm:col-span-2">Tanggal</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Nama Kapal</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Jadwal Keberangkatan</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-end">Waktu</div>
                        </div>

                        <div class="hidden border-b border-gray-200 sm:block"></div>

                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                            <div class="col-span-full sm:col-span-2">
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Tanggal</h5>
                                <p class="font-medium text-gray-800">18/07/2024</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Nama Kapal</h5>
                                <p class="text-gray-800">Anang Kapal</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Jadwal Keberangkatan</h5>
                                <p class="text-gray-800">18/07/2024</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Waktu</h5>
                                <p class="text-gray-800 sm:text-end">23.30</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pembayaran -->
                    <div class="p-4 mt-4 space-y-4 border border-gray-200 rounded-lg">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="text-xs font-medium text-gray-500 uppercase sm:col-span-2">Jenis Penumpang</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Jumlah</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-start">Harga Tiket</div>
                            <div class="text-xs font-medium text-gray-500 uppercase text-end">Total</div>
                        </div>

                        <div class="hidden border-b border-gray-200 sm:block"></div>

                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                            <div class="col-span-full sm:col-span-2">
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Jenis Penumpang</h5>
                                <p class="font-medium text-gray-800">Dewasa</p>
                                <p class="font-medium text-gray-800">Anak</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Jumlah</h5>
                                <p class="text-gray-800">1</p>
                                <p class="text-gray-800">1</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Harga Tiket</h5>
                                <p class="text-gray-800">Rp145.000</p>
                                <p class="text-gray-800">Rp100.000</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden">Total</h5>
                                <p class="text-gray-800 sm:text-end">Rp145.000</p>
                                <p class="text-gray-800 sm:text-end">Rp100.000</p>
                            </div>

                        </div>

                    </div>
                    <!-- End Table -->

                    <!-- Flex -->
                    <div class="flex mt-8 sm:justify-end">
                        <div class="w-full max-w-2xl space-y-2 sm:text-end">
                            <!-- Grid -->
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                                <dl class="grid sm:grid-cols-5 gap-x-3">
                                    <dt class="col-span-3 font-semibold text-gray-800">Total Harga :</dt>
                                    <dd class="col-span-2 text-gray-500">
                                        Rp{{ number_format($pesanan->Total_Harga, 0, ',', '.') }}.000</dd>
                                </dl>
                                <dl class="grid sm:grid-cols-5 gap-x-3">
                                    <dt class="col-span-3 font-semibold text-gray-800">Metode Bayar :</dt>
                                    <dd class="col-span-2 text-gray-500">{{ $pesanan->Metode_Bayar }}</dd>
                                </dl>
                            </div>
                            <!-- End Grid -->
                        </div>
                    </div>
                    <!-- End Flex -->

                    <div class="mt-8 sm:mt-12">
                        <h4 class="text-lg font-semibold text-gray-800">Terima Kasih!</h4>
                        <p class="text-gray-500">Jika Anda memiliki pertanyaan mengenai Invoice ini, gunakan informasi
                            kontak berikut:</p>
                        <div class="mt-2">
                            <p class="block text-sm font-medium text-gray-800">anangferry@gmail.com</p>
                            <p class="block text-sm font-medium text-gray-800">0896-6969-696</p>
                        </div>
                    </div>

                    <p class="mt-5 text-sm text-gray-500">© 2024 Anang Ferry.</p>
                </div>
                <!-- End Card -->

                <!-- Buttons -->
                <div class="flex justify-end mt-6 gap-x-3">
                    <a class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 align-middle transition-all bg-white border rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600"
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
                    <a class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg gap-x-2 hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect width="12" height="8" x="6" y="14" />
                        </svg>
                        Print
                    </a>
                </div>
                <!-- End Buttons -->
            </div>
        </div>
        <!-- End Invoice -->
    </div>
@endsection
