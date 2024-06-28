@extends('layout.main')

@section('container')
<!-- Invoice -->
<div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
    <div class="sm:w-11/12 lg:w-3/4 mx-auto mb-5">
        <!-- Card -->
        <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl">
            <!-- Grid -->
            <div class="flex justify-between">
                <div>
                    <img src="/img/logo3.png" alt="logo" class="w-20 h-20 ms-4">

                    <h1 class="mt-2 text-lg md:text-xl font-marko text-blue-600">Anang Ferry</h1>
                </div>
                <!-- Col -->

                <div class="text-end">
                    <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">Invoice #</h2>
                    <span class="mt-1 block text-gray-500"> kode tiket </span>
                </div>
                <!-- Col -->
            </div>
            <!-- End Grid -->

            <!-- Grid -->
            <div class="mt-8 grid sm:grid-cols-2 gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Nama:</h3>
                    <h3 class="text-lg font-semibold text-gray-800">Muhammad Dafa Putra</h3>
                    <address class="mt-2 not-italic text-gray-500">
                        Perumahan Muka Kuning Indah 1<br>
                        0888 yang lain kapan2<br>
                        mizu@gmail.com<br>
                    </address>
                </div>
                <!-- Col -->

                <div class="sm:text-end space-y-2">
                    <!-- Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800">Tanggal Invoice:</dt>
                            <dd class="col-span-2 text-gray-500">30/6/2024</dd>
                        </dl>
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800">Waktu Pemesanan:</dt>
                            <dd class="col-span-2 text-gray-500">08:15:00</dd>
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
                <div class="border border-gray-200 p-4 rounded-lg space-y-4">
                    <div class="hidden sm:grid sm:grid-cols-5">
                        <div class="sm:col-span-2 text-xs font-medium text-gray-500 uppercase">Tanggal</div>
                        <div class="text-start text-xs font-medium text-gray-500 uppercase">No. Kapal</div>
                        <div class="text-start text-xs font-medium text-gray-500 uppercase">Nama Kapal</div>
                        <div class="text-end text-xs font-medium text-gray-500 uppercase">Jadwal Keberangkatan</div>
                    </div>

                    <div class="hidden sm:block border-b border-gray-200"></div>

                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <div class="col-span-full sm:col-span-2">
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Tanggal</h5>
                            <p class="font-medium text-gray-800">1/7/2024</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">No. Kapal</h5>
                            <p class="text-gray-800">111</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Nama Kapal</h5>
                            <p class="text-gray-800">Titanic</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Jadwal Keberangkatan</h5>
                            <p class="sm:text-end text-gray-800">1/7/2024, 08:00 WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Data Pembayaran -->
                <div class="border border-gray-200 p-4 rounded-lg space-y-4 mt-4">
                    <div class="hidden sm:grid sm:grid-cols-5">
                        <div class="sm:col-span-2 text-xs font-medium text-gray-500 uppercase">Jenis Penumpang</div>
                        <div class="text-start text-xs font-medium text-gray-500 uppercase">Jumlah</div>
                        <div class="text-start text-xs font-medium text-gray-500 uppercase">Harga Tiket</div>
                        <div class="text-end text-xs font-medium text-gray-500 uppercase">Total</div>
                    </div>

                    <div class="hidden sm:block border-b border-gray-200"></div>

                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        <div class="col-span-full sm:col-span-2">
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Jenis Penumpang</h5>
                            <p class="font-medium text-gray-800">Dewasa</p>
                            <p class="font-medium text-gray-800">Anak</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Jumlah</h5>
                            <p class="text-gray-800">1</p>
                            <p class="text-gray-800">2</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Harga Tiket</h5>
                            <p class="text-gray-800">Rp300.000</p>
                            <p class="text-gray-800">Rp250.000</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Total</h5>
                            <p class="sm:text-end text-gray-800">Rp300.000</p>
                            <p class="sm:text-end text-gray-800">Rp500.000</p>
                        </div>

                    </div>

                </div>
                <!-- End Table -->

                <!-- Flex -->
                <div class="mt-8 flex sm:justify-end">
                    <div class="w-full max-w-2xl sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Total Harga:</dt>
                                <dd class="col-span-2 text-gray-500">Rp800.000</dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
                <!-- End Flex -->

                <div class="mt-8 sm:mt-12">
                    <h4 class="text-lg font-semibold text-gray-800">Terima Kasih!</h4>
                    <p class="text-gray-500">Jika Anda memiliki pertanyaan mengenai Invoice ini, gunakan informasi kontak berikut:</p>
                    <div class="mt-2">
                        <p class="block text-sm font-medium text-gray-800">anangAdmin@gmail.com</p>
                        <p class="block text-sm font-medium text-gray-800">08 31 sini Kau satu2</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-gray-500">© 2024 Anang Ferry.</p>
            </div>
            <!-- End Card -->

            <!-- Buttons -->
            <div class="mt-6 flex justify-end gap-x-3">
                <a class="py-2 px-3 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm" href="#">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Invoice PDF
                </a>
                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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