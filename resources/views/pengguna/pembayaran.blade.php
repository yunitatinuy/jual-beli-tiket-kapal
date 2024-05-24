@extends('layout.main')

@section('container')
<main>
    <div class="container h-auto w-auto bg-slate-600 mb-4 mt-10">
        <div class="gap-2 columns-2 py-3 px-2 font-semibold text-xl">
            <div class="justify-center mx-60"><a href="">PEMBAYARAN</a></div>
            <a href="" class="mx-64">SELESAI</a>
        </div>
    </div>
    <div class="flex mb-4">
        <div class="w-full md:w-1/2 px-3 py-2 bg-slate-500"> Transaksi Pembayaran
            <div class="px-2 py-2 mt-2 bg-slate-50">Pilih Metode Pembayaran
                <label class="flex radio p-2 cursor-pointer">
                    <input class="my-auto transform scale-120" type="radio" name="sfg" />
                    <div class="title px-2">Transfer Bank</div>
                    <div class="pe-32"></div>
                    <input class="my-auto transform scale-120" type="radio" name="sfg" />
                    <div class="title px-2">Dana</div>
                </label>
            </div>
        </div>
        <div class="w-full md:w-1/2 md:ml-2 px-3 py-2 bg-red-500 md:mt-0">
            Pesanan
            <div class="px-2 py-2 mt-2 bg-slate-50"> 1 Tiket Dewasa </div>
        </div>
    </div>

    <div class="flex mb-4">
        <div class="w-full md:w-1/2 px-3 py-2 bg-slate-500"> Informasi Pembayaran
            <div class="px-2 py-0.5 mt-2 bg-slate-50">
                <div class="relative z-0 w-full bg-slate-50 group">
                    <input type="number"
                        class="block mt-3 py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " required /><label for=""
                        class="peer-focus:font-medium absolute font-inter text-sm text-gray-600 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4    rtl:peer-focus:left-auto peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nomor
                        Kartu</label>
                </div>
            </div>
        </div>
        <div class="w-full md:w-1/2 md:ml-2 px-3 py-2 bg-red-500 md:mt-0">
            $ Total Harga
            <div class="px-2 py-2 mt-2 bg-slate-50"> Rp 540.000</div>
        </div>
    </div>

    <div class="container1 h-auto w-auto bg-slate-600 mb-4">
        <div class="gap-6 py-2 px-2">
            <div class="flex justify-center font-semibold text-xl"><a href="">BAYAR</a></div>
        </div>
        @endsection