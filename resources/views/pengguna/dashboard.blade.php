@extends('layout.main')

@section('container')
    <!-- carousel -->

    <div id="myCarousel" class="w-full bg-cover carousel slide" data-bs-ride="carousel">
        <div class="absolute bottom-0 left-0 right-0 flex justify-center p-4 carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0"
                class="w-4 h-4 bg-transparent rounded-full active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"
                class="w-4 h-4 bg-transparent rounded-full" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"
                class="w-4 h-4 bg-transparent rounded-full" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner h-[500px]">
            <div class="carousel-item active">
                <img src="img/1.webp" alt="" class="w-full">
                <div class="container">
                    <div class="carousel-caption text-start">
                        <h1>HAI</h1>
                        <p class="opacity-75">HELLO</p>
                        <p><a class="btn btn-lg btn-primary" href="#">Registrasi Cepat</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/2.webp" alt="" class="w-full">
                <div class="container">
                    <div class="carousel-caption">
                        <h1>HAI</h1>
                        <p>HELLO</p>
                        <p><a class="btn btn-lg btn-primary" href="#">HELOO</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/3.jpg" alt="" class="w-full">
                <div class="container">
                    <div class="carousel-caption text-end">
                        <h1>HAI</h1>
                        <p>HELLO</p>
                        <p><a class="btn btn-lg btn-primary" href="#">HELOO</a></p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev rounded-3xl hover:bg-transparent pe-10" type="button"
            data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="py-4 rounded-lg carousel-control-prev-icon bg-slate-700" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next rounded-3xl hover:bg-transparent ps-10" type="button"
            data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="py-4 rounded-lg carousel-control-next-icon bg-slate-700" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- carousel end -->

    <!-- container -->
    <div
        class="container -translate-y-12 justify-content-center bg-[#fec7a4] border-slate-300 border-2 shadow-2xl p-8 w-5/6 font-serif rounded-3xl">
        <h2 class="flex justify-center mx-2 mb-2 text-xl font-semibold">Atur Jadwal Pelayaran Anda</h2>
        <div class="flex flex-row mx-4 space-x-5">
            <button onclick="tampilkanTampilan(1)"
                class="p-2 rounded-xl hover:bg-[#c1a99d] active:bg-[#c1a99d] focus:outline-none focus:ring focus:ring-slate-50 hover:rounded-xl px-2">Sekali
                Pergi</button>
            <button onclick="tampilkanTampilan(2)"
                class="p-2 rounded-xl hover:bg-[#c1a99d] active:bg-[#c1a99d] focus:outline-none focus:ring focus:ring-slate-50 hover:rounded-xl px-2">Pulang
                Pergi</button>
        </div>

        <!-- tampilan1 -->
        <div id="tampilan1" class="tampilan">
            <div class="grid grid-cols-4 gap-4 mx-16 my-3">
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dari
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-slate-50 focus:border-slate-50 block w-full p-2.5 ">
                            <option selected>Pilih tempat asal</option>
                            <option value="US">Jakarta</option>
                            <option value="CA">Batam</option>
                            <option value="FR">Medan</option>
                            <option value="DE">Riau</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ke
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tempat tujuan</option>
                            <option value="US">Batam</option>
                            <option value="CA">Medan</option>
                            <option value="FR">Jakarta</option>
                            <option value="DE">Riau</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dewasa
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih jumlah</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">4</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anak -
                            anak :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih jumlah</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">4</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 mx-16 my-3">
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                            keberangkatan</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tanggal berangkat</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">3</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih kelas</option>
                            <option value="US">VVIP</option>
                            <option value="CA">VIP</option>
                            <option value="FR">Ekonomi</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <!-- tampilan1 end -->

        <!-- tampilan2 -->
        <div id="tampilan2" class="tampilan">
            <div class="grid grid-cols-4 gap-4 mx-16 my-3">
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dari
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tempat asal</option>
                            <option value="US">Jakarta</option>
                            <option value="CA">Batam</option>
                            <option value="FR">Medan</option>
                            <option value="DE">Riau</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ke
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tempat tujuan</option>
                            <option value="US">Batam</option>
                            <option value="CA">Medan</option>
                            <option value="FR">Jakarta</option>
                            <option value="DE">Riau</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dewasa
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih jumlah</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">4</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anak -
                            anak :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih jumlah</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">4</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 mx-16 my-3">
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal keberangkatan
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tanggal berangkat</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">3</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal kepulangan :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih tanggal pulang</option>
                            <option value="US">1</option>
                            <option value="CA">2</option>
                            <option value="FR">3</option>
                            <option value="DE">3</option>
                        </select>
                    </form>
                </div>
                <div>
                    <form class="max-w-sm mx-auto">
                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas
                            :</label>
                        <select id="countries"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih kelas</option>
                            <option value="US">VVIP</option>
                            <option value="CA">VIP</option>
                            <option value="FR">Ekonomi</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <!-- tampilan2 end -->
        <div class="flex justify-end">
            <a href="/login"
                class="inline-flex items-center px-3 py-2 font-sans text-sm font-medium text-center transition duration-150 rounded-lg shadow-lg text-dark bg-slate-50 hover:scale-110 hover:ease-out focus:ring-4">
                Cari Ferry
                <img src="/img/cari.png" alt="" class="w-4 ms-2">
            </a>
        </div>
    </div>

    <!-- /.container -->

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
@endsection
