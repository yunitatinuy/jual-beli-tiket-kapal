@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        $rute = $quote['rute'];
        $items = $quote['items'];
        $dewasa = $items['dewasa']['jumlah'] ?? 0;
        $anak = $items['anak']['jumlah'] ?? 0;
        $inputClass = 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6';
    @endphp

    <div class="flex flex-wrap justify-between my-20">
        <div class="container booking">
            <div class="container justify-content-center py-8 px-5 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                style="width: 95%; background-color:#c2c1c1; border-radius:30px;">

                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Ringkasan perjalanan & harga (hanya tampilan, dihitung server) --}}
                <div class="container flex flex-wrap items-center gap-8 pt-2 mx-6">
                    <img src="/img/icons/ships.png" class="w-12">
                    <div>
                        <strong>{{ $rute->kapal->Nama_Kapal ?? '-' }}</strong> &middot; Kelas {{ $quote['kelas'] }}<br>
                        {{ $rute->pelabuhanAsal->Nama_Pelabuhan ?? '-' }} ke {{ $rute->pelabuhanTujuan->Nama_Pelabuhan ?? '-' }}
                    </div>
                    <div>
                        Waktu <br>
                        {{ Carbon::parse($rute->Jam)->format('H:i') }}
                    </div>
                    <div>
                        Tanggal <br>
                        {{ Carbon::parse($rute->Tanggal)->format('d-m-Y') }}
                    </div>
                    @foreach (['dewasa' => 'Dewasa', 'anak' => 'Anak'] as $tipe => $label)
                        @isset($items[$tipe])
                            <div>
                                {{ $label }} ({{ $items[$tipe]['jumlah'] }} x {{ rupiah($items[$tipe]['harga']) }}) <br>
                                {{ rupiah($items[$tipe]['subtotal']) }}
                            </div>
                        @endisset
                    @endforeach
                    <div>
                        <strong>Total Harga</strong> <br>
                        <strong>{{ rupiah($quote['total']) }}</strong>
                    </div>
                </div>
                <br>
                <div class="border border-b-black"></div>
                <br>

                <div class="container">
                    <h1 class="flex justify-center text-xl font-bold">DETAIL TIKET PENUMPANG</h1>
                    <form action="{{ route('pesantiket.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rute" value="{{ $rute->ID_Rute }}">
                        <input type="hidden" name="kelas" value="{{ $quote['kelas'] }}">
                        <input type="hidden" name="dewasa" value="{{ $dewasa }}">
                        <input type="hidden" name="anak" value="{{ $anak }}">

                        @for ($i = 0; $i < $dewasa + $anak; $i++)
                            <div class="p-4 mt-6 rounded-lg bg-white/40">
                                <p class="font-semibold">
                                    Penumpang {{ $i + 1 }}
                                    <span class="text-sm font-normal">({{ $i < $dewasa ? 'Dewasa' : 'Anak' }})</span>
                                </p>
                                <div class="grid grid-cols-1 mt-2 gap-x-6 gap-y-2 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <input type="text" name="penumpang[{{ $i }}][Nama_Lengkap]"
                                            value="{{ old("penumpang.$i.Nama_Lengkap") }}" placeholder="Nama Lengkap"
                                            required maxlength="255" class="{{ $inputClass }}">
                                        @error("penumpang.$i.Nama_Lengkap")
                                            <div class="text-sm text-red-700">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-3">
                                        <input type="tel" name="penumpang[{{ $i }}][Telepon]"
                                            value="{{ old("penumpang.$i.Telepon") }}" placeholder="Telepon (08xxxxxxxxxx)"
                                            required inputmode="numeric" class="{{ $inputClass }}">
                                        @error("penumpang.$i.Telepon")
                                            <div class="text-sm text-red-700">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-3">
                                        <textarea name="penumpang[{{ $i }}][Alamat]" placeholder="Alamat" required maxlength="255"
                                            class="{{ $inputClass }}">{{ old("penumpang.$i.Alamat") }}</textarea>
                                        @error("penumpang.$i.Alamat")
                                            <div class="text-sm text-red-700">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="sm:col-span-3">
                                        <select name="penumpang[{{ $i }}][Jenis_Kelamin]" required class="{{ $inputClass }}">
                                            <option value="" disabled {{ old("penumpang.$i.Jenis_Kelamin") ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                            @foreach ($jenisKelaminOptions as $value)
                                                <option value="{{ $value }}" {{ old("penumpang.$i.Jenis_Kelamin") == $value ? 'selected' : '' }}>
                                                    {{ ucfirst($value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("penumpang.$i.Jenis_Kelamin")
                                            <div class="text-sm text-red-700">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <div class="p-4 my-4 rounded-lg bg-[#e5cabb]">
                            <p class="font-semibold">Pembayaran</p>
                            <p class="text-sm">
                                Setelah menekan tombol pesan, Anda dapat membayar lewat Midtrans
                                (Virtual Account bank, QRIS, GoPay, ShopeePay, dan lainnya).
                                Kursi ditahan selama <strong>{{ $batasBayarMenit }} menit</strong>; jika belum dibayar,
                                pesanan otomatis dibatalkan.
                            </p>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit"
                                class="py-2 w-11/12 text-lg h-auto font-sans font-medium text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-105 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">
                                P E S A N &nbsp; ({{ rupiah($quote['total']) }})
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
