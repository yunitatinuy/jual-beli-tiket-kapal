@extends('layout.main')

@section('container')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex flex-wrap justify-between my-20">
        <div class="container booking">
            <div class="container justify-content-center py-8 px-5 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                style="width: 95%; background-color:#c2c1c1; border-radius:30px;">
                <div class="container flex items-center pt-2 mx-6 space-x-12 flex-nowrap">
                    <img src="/img/icons/ships.png" class="w-12">
                    <div class="">
                        {{ $kapal }} <br>
                        {{ $asal }} ke {{ $tujuan }}
                    </div>
                    <div class="">
                        Waktu <br>
                        {{ $jam }}
                    </div>
                    <div class="">
                        Tanggal <br>
                        {{ $tanggal }}
                    </div>
                    @if (isset($harga_vip_dewasa))
                        <div class="">
                            Harga VIP Dewasa : <br>
                            Rp{{ number_format($harga_vip_dewasa, 0, ',', '.') }}.000
                        </div>
                    @endif
                    @if (isset($harga_vip_anak))
                        <div class="">
                            Harga VIP Anak : <br>
                            Rp{{ number_format($harga_vip_anak, 0, ',', '.') }}.000
                        </div>
                    @endif
                    @if (isset($harga_ekonomi_dewasa))
                        <div class="">
                            Harga Ekonomi Dewasa : <br>
                            Rp{{ number_format($harga_ekonomi_dewasa, 0, ',', '.') }}.000
                        </div>
                    @endif
                    @if (isset($harga_ekonomi_anak))
                        <div class="">
                            Harga Ekonomi Anak : <br>
                            Rp{{ number_format($harga_ekonomi_anak, 0, ',', '.') }}.000
                        </div>
                    @endif
                    <div class="">
                        Total Harga <br>
                        Rp{{ number_format(($harga_vip_dewasa ?? 0) + ($harga_vip_anak ?? 0) + ($harga_ekonomi_dewasa ?? 0) + ($harga_ekonomi_anak ?? 0), 0, ',', '.') }}.000
                    </div>
                </div>
                <br>
                <div class="border border-b-black"></div>
                <br>
                <div class="container">
                    <h1 class="flex justify-center text-xl font-bold">DETAIL TIKET PENUMPANG</h1>
                    <form action="{{ route('pesantiket.store') }}" method="POST">
                        @csrf
                        <div class="main-user-info">
                            <div class="user-input-box" id="passenger-details">
                                <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-2 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" class="form-control" name="Nama_Lengkap[]"
                                                value="{{ old('Nama_Lengkap.0') }}" autocomplete="given-name"
                                                placeholder="Nama Lengkap"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('Nama_Lengkap.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="number" class="form-control" name="Telepon[]"
                                                value="{{ old('Telepon.0') }}" autocomplete="given-name"
                                                placeholder="Telepon"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            @error('Telepon.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <textarea class="form-control" name="Alamat[]" autocomplete="family-name" placeholder="Alamat"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('Alamat.0') }}</textarea>
                                            @error('Alamat.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <select class="form-control" name="Jenis_Kelamin[]" autocomplete="family-name"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                @foreach ($jenisKelaminOptions as $value)
                                                    <option value="{{ $value }}"
                                                        {{ old('Jenis_Kelamin.0') == $value ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('Jenis_Kelamin.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="harga_ekonomi_dewasa" value="{{ $harga_ekonomi_dewasa }}">
                        <input type="hidden" name="harga_ekonomi_anak" value="{{ $harga_ekonomi_anak }}">
                        <input type="hidden" name="harga_vip_dewasa" value="{{ $harga_vip_dewasa }}">
                        <input type="hidden" name="harga_vip_anak" value="{{ $harga_vip_anak }}">
                            <div class="grid grid-cols-2 gap-2 my-4">
                                <div class="w-full md:w-1/2 px-3 py-2 rounded-lg bg-[#e5cabb]">
                                    <div
                                        class="flex-warp px-2 py-2 mt-2 rounded-lg bg-gradient-to-tr from-[#ffebe1] to-[#efd5c8]">
                                        <p class="mb-2 font-semibold">Pilih Metode Pembayaran</p>
                                        @foreach (\App\Models\Pesanan::METODE_BAYAR as $metode)
                                            <label class="flex p-2 cursor-pointer radio">
                                                <input class="my-auto transform scale-120" type="radio"
                                                    name="metode_bayar" value="{{ $metode }}" required />
                                                <div class="px-2 title">{{ $metode }}</div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" id="add-passenger"
                                        class="py-2 px-8 max-h-12 font-sans text-base font-medium text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">Tambah
                                        Data Penumpang</button>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <button type="submit"
                                    class="py-2 px-96 w-11/12 text-lg h-auto font-sans font-medium text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">P
                                    E S A N</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-passenger').addEventListener('click', function() {
            const passengerDetails = document.querySelector('.user-input-box');
            const newPassengerDetails = passengerDetails.cloneNode(true);
            const formFields = newPassengerDetails.querySelectorAll('input, textarea, select');
            formFields.forEach((field, index) => {
                field.value = '';
                field.name = field.name.replace(/\[\d+\]/,
                    `[${document.querySelectorAll('.user-input-box').length}]`);
            });
            document.getElementById('passenger-details').appendChild(newPassengerDetails);
        });
    </script>
@endsection
