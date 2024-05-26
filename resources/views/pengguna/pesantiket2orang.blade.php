@extends('layout.main')

@section('container')
<main>

    <!-- loc -->
    <div class="flex flex-wrap justify-between">
        <div class="flex flex-auto mb-3 items-start mx-auto">
            <img src="/img/icons/harbor.png" class="w-6 ms-2 pr-2">
            <span href="#" class="text-white  px-2 text-sm font-medium bg-neutral-400 rounded-sm">Batam</span>
            <span href="#" class="px-2 text-sm font-medium"><button
                    class="hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300"><img
                        src="/img/icons/swap.png" class="w-4"></span></button>
            <span href="#" class="text-white  px-2 text-sm font-medium bg-neutral-400 rounded-sm">Karimun</span>
        </div>
        <!-- container -->
        <div class="container booking ">
            <div class="container justify-content-center pt-3 pb-3 bg-gradient-to-tr from-[#ffebe1] to-[#DEC3B4]"
                style="max-width: 1300px; width: 100%; background-color:#c2c1c1; border-radius:30px;">
                <div class="container flex flex-nowrap space-x-12 mx-6 pt-2 items-center">
                    <img src="/img/icons/ships.png" class="w-12">
                    <div class="">
                        Nongsapura Feri Terminal, Batam <br>
                        50m Perjalanan Feri Batam Feri Cepat Kelas Ekonomi
                    </div>
                    <div class="">
                        08 : 00 - 09 : 50
                    </div>
                    <div class="pl-24">
                        Tanah Merah Feri Terminal, Singapore
                    </div>
                </div>
                <br>
                <div class="border border-b-black">
                </div>
                <br>
                <div class="container">
                    <h1 class="flex justify-center font-bold text-xl">DETAIL TIKET PENUMPANG</h1>
                    <form action="#">
                        <div class="main-user-info">
                            <div class="user-input-box">
                                <h1 class="font-bold text-l mt-6">DETAIL PENUMPANG 1</h1>
                                <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Nama Lengkap" id="Nama Lengkap"
                                                autocomplete="given-name" placeholder="Nama Lengkap"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Jenis Kelamin" id="Jenis Kelamin"
                                                autocomplete="family-name" placeholder="Jenis Kelamin"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Telepon" id="Telepon" autocomplete="given-name"
                                                placeholder="Telepon"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Alamat" id="Alamat" autocomplete="family-name"
                                                placeholder="Alamat"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Email" id="Email" autocomplete="given-name"
                                                placeholder="Email"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                </div><br>

                                <h1 class="font-bold text-l">DETAIL PENUMPANG 2</h1>
                                <div class="mt-2 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Nama Lengkap" id="Nama Lengkap"
                                                autocomplete="given-name" placeholder="Nama Lengkap"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Jenis Kelamin" id="Jenis Kelamin"
                                                autocomplete="family-name" placeholder="Jenis Kelamin"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Telepon" id="Telepon" autocomplete="given-name"
                                                placeholder="Telepon"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Alamat" id="Alamat" autocomplete="family-name"
                                                placeholder="Alamat"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <div class="mt-2">
                                            <input type="text" name="Email" id="Email" autocomplete="given-name"
                                                placeholder="Email"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end mx-5 ">
                                    <div href="/login"
                                        class="inline-flex w-auto h-auto font-sans px-5 py-3 text-sm font-medium text-center text-dark bg-[#DEC3B4] rounded-lg hover:bg-[#DEC3B4] hover:scale-110 transition duration-150 hover:ease-out focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        <a class="mx-3">Rp 540.000</a>
                                        <a class="mx-3">PESAN</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>



        <!-- /.container -->
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ketika halaman dimuat, tampilkan tampilan default
    tampilkanTampilan(1);
});

function tampilkanTampilan(tampilan) {
    var tampilan1 = document.getElementById('tampilan1');
    var tampilan2 = document.getElementById('tampilan2');

    if (tampilan === 1) {
        tampilan1.classList.add('active');
        tampilan2.classList.remove('active');
    } else if (tampilan === 2) {
        tampilan1.classList.remove('active');
        tampilan2.classList.add('active');
    }
}
</script>
</body>
@endsection