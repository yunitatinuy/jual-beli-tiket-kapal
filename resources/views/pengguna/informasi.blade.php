@extends('layout.main')

@section('container')
<main>
    <div class="container booking my-16" style="max-width: 1050px; width: 100%;">
        <div class="rounded-lg bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4] mb-6">
            <div class="py-3 font-semibold text-2xl text-center">
                <a href="">INFORMASI</a>
            </div>
        </div>
        @foreach($groupedRutes as $route => $rutes)
        <div class="container flex justify-content-center pt-3 pb-3 mb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4] rounded-lg">
            <div class="container mx-6 pt-2 pl-10 items-center">
                <div class="flex flex-col space-y-2">
                    <div class="font-bold text-lg">{{ $route }}</div>
                    @foreach($rutes as $rute)
                    <div class="flex flex-nowrap space-x-12 items-center border-t pt-2">
                        <img src="/img/icons/ship.png" class="w-14">
                        <div>
                            <strong>Tanggal:</strong> {{ $rute->Tanggal }}
                        </div>
                        <div>
                            <strong>Jam:</strong> {{ \Carbon\Carbon::parse($rute->Jam)->format('H.i') }}
                        </div>
                        <div>
                            <strong>Nama Kapal:</strong> {{ $rute->kapal->Nama_Kapal }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</main>
@endsection
