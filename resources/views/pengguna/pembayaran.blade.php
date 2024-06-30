@extends('layout.main')

@section('container')
<main>
    <div class="container my-10">
        <div class="container justify-content-center rounded-lg bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4] mb-6 mx-auto my-20">
            <div class="gap-2 px-2 py-3 text-xl font-semibold columns-2">
                <div class="justify-center mx-60"><a href="">PEMBAYARAN</a></div>
                <a href="" class="mx-64">SELESAI</a>
            </div>
        </div>

        <form action="{{ route('pembayaran.process') }}" method="POST">
            @csrf
            <input type="hidden" name="ID_Tiket" value="{{ $tiket->ID_Tiket ?? '' }}">
            <div class="flex mb-4">
                <div class="w-full md:w-1/2 px-3 py-2 rounded-lg bg-[#e5cabb]">
                    <div class="px-2 py-2 mt-2 rounded-lg bg-gradient-to-tr from-[#ffebe1] to-[#efd5c8]">
                        <p class="mb-2 font-semibold">Pilih Metode Pembayaran</p>
                        @foreach (\App\Models\Pesanan::METODE_BAYAR as $metode)
                            <label class="flex p-2 cursor-pointer radio">
                                <input class="my-auto transform scale-120" type="radio" name="metode_bayar" value="{{ $metode }}" required />
                                <div class="px-2 title">{{ $metode }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="w-full md:w-1/2 md:ml-2 px-3 py-2 rounded-lg bg-[#e5cabb]">
                    <div class="px-2 py-2 mt-2 rounded-lg bg-gradient-to-tr from-[#ffebe1] to-[#efd5c8]">
                        <p class="mb-2 font-semibold">Total Harga</p>
                        <div>
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}.000
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex justify-center mb-4">
                <div class="w-full md:w-1/2 px-3 py-2 rounded-lg bg-[#e5cabb]">
                    <div class="gap-6 px-2 py-3">
                        <div class="flex justify-center text-xl font-semibold">
                            <button type="submit">BAYAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection
