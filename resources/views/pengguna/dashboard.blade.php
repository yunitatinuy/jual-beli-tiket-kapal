@extends('layout.main')

@section('container')
<!-- carousel -->
<div x-data="{            
    // Sets the time between each slides in milliseconds
    autoplayIntervalTime: 4000,
    slides: [                
        {
            imgSrc: '/img/pexels.jpg',
            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',  
            title: 'Anang Ferry',
            description: 'Perjalanan Laut yang Nyaman Dimulai di Sini: Temukan Tiket Kapal Terbaik untuk Petualangan Anda.',           
        },                
        {                    
            imgSrc: '/img/pexels2.jpg',                    
            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
            title: 'Anang Ferry',
            description: 'Liburan Asyik Dimulai di Sini: Pesan Tiket Kapal Praktis dan Nikmati Waktu Bersama Keluarga.',            
        },                
        {                    
            imgSrc: '/img/pexels3.jpg',                    
            imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',    
            title: 'Anang Ferry',
            description: 'Dari Pulau ke Pulau Tanpa Hambatan: Tiket Kapal dalam Genggamanmu, Mudah dan Cepat.',       
        },            
    ],            
    currentSlideIndex: 1,
    isPaused: false,
    autoplayInterval: null,
    previous() {                
        if (this.currentSlideIndex > 1) {                    
            this.currentSlideIndex = this.currentSlideIndex - 1                
        } else {   
            // If it's the first slide, go to the last slide           
            this.currentSlideIndex = this.slides.length                
        }            
    },            
    next() {                
        if (this.currentSlideIndex < this.slides.length) {                    
            this.currentSlideIndex = this.currentSlideIndex + 1                
        } else {                 
            // If it's the last slide, go to the first slide    
            this.currentSlideIndex = 1                
        }            
    },    
    autoplay() {
        this.autoplayInterval = setInterval(() => {
            if (! this.isPaused) {
                this.next()
            }
        }, this.autoplayIntervalTime)
    },
    // Updates interval time   
    setAutoplayInterval(newIntervalTime) {
        clearInterval(this.autoplayInterval)
        this.autoplayIntervalTime = newIntervalTime
        this.autoplay()
    },    
}" x-init="autoplay" class="relative w-full overflow-hidden">

    <!-- slides -->
    <!-- Change min-h-[50svh] to your preferred height size -->
    <div class="relative min-h-[70svh] w-full -z-10">
        <template x-for="(slide, index) in slides">
            <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>

                <!-- Title and description -->
                <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-gradient-to-t from-slate-900/85 to-transparent px-16 py-12 text-center">
                    <h3 class="w-full lg:w-[80%] text-balance text-3xl lg:text-3xl font-bold text-white" x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h3>
                    <p class="lg:w-1/2 w-full text-pretty text-lg text-slate-300" x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                </div>

                <img class="absolute w-full h-full inset-0 object-cover text-slate-700 dark:text-slate-300" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>

    <!-- Pause/Play Button -->
    <button type="button" class="absolute bottom-5 right-5 z-20 rounded-full text-slate-300 opacity-50 transition hover:opacity-80 focus-visible:opacity-80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 active:outline-offset-0" aria-label="pause carousel" x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)" x-bind:aria-pressed="isPaused">
        <svg x-cloak x-show="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z" clip-rule="evenodd">
        </svg>
        <svg x-cloak x-show="!isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
            <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm5-2.25A.75.75 0 0 1 7.75 7h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Zm4 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd">
        </svg>
    </button>

    <!-- indicators -->
    <div class="absolute rounded-xl bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides">
        <template x-for="(slide, index) in slides">
            <button class="size-2 cursor-pointer rounded-full transition" x-on:click="(currentSlideIndex = index + 1), setAutoplayInterval(autoplayIntervalTime)" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-slate-300' : 'bg-slate-300/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
        </template>
    </div>
</div>

<!-- carousel end -->

    <!-- container -->
    <div class="container my-10 justify-content-center bg-[#fec7a4] border-slate-300 border-2 shadow-2xl p-8 w-5/6 font-serif rounded-3xl">
        <h2 class="flex justify-center mx-2 mb-2 text-xl font-semibold">Atur Jadwal Pelayaran Anda</h2>
        <div class="flex flex-row mx-4 mb-4 space-x-5">
            <button onclick="tampilkanTampilan(1)"
                class="p-2 rounded-xl hover:bg-[#c1a99d] active:bg-[#c1a99d] focus:outline-none focus:ring focus:ring-slate-50">Sekali
                Pergi</button>
            <button onclick="tampilkanTampilan(2)"
                class="p-2 rounded-xl hover:bg-[#c1a99d] active:bg-[#c1a99d] focus:outline-none focus:ring focus:ring-slate-50">Pulang
                Pergi</button>
        </div>

        <!-- Form untuk tampilan sekali pergi -->
        <div id="tampilan1" class="tampilan">
            <form action="{{ route('search') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 mx-6 my-3 md:grid-cols-3">
                    <div class="flex flex-col">
                        <label for="routeFrom" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pelabuhan Asal:</label>
                        <input type="text" name="routeFrom" placeholder="Pelabuhan Asal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex flex-col">
                        <label for="routeTo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pelabuhan Tujuan:</label>
                        <input type="text" name="routeTo" placeholder="Pelabuhan Tujuan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex flex-col">
                        <label for="tanggal_keberangkatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal:</label>
                        <input type="date" id="tanggal_keberangkatan" name="tanggal_keberangkatan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex flex-col">
                        <label for="dewasa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dewasa:</label>
                        <input type="number" name="dewasa" id="dewasa" placeholder="Dewasa"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="flex flex-col">
                        <label for="anak" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anak - anak:</label>
                        <input type="number" name="anak" id="anak" placeholder="Anak - anak"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                </div>
                <div class="flex justify-center mt-4">
                    <button type="submit" class="px-4 py-2 text-white bg-[#f9a273] hover:bg-[#eb9f77] text-sm rounded-lg">Cari Ferry</button>
                </div>
            </form>
        </div>
        <!-- End Form untuk tampilan sekali pergi -->
    </div>

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
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                        keberangkatan
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
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                        kepulangan
                        :</label>
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
    </div>

<!-- /.container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
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
    <script>
        function calculateTotal() {
            // Get values from inputs
            var dewasa = document.getElementById('dewasa').value;
            var anak = document.getElementById('anak').value;

            // Parse values as numbers, default to 0 if empty
            dewasa = parseFloat(dewasa) || 0;
            anak = parseFloat(anak) || 0;

            // Fetch the base prices (you can adjust this based on your backend logic)
            var hargaDewasa = {!! json_encode(config('app.harga_dewasa')) !!};
            var hargaAnak = {!! json_encode(config('app.harga_anak')) !!};

            // Calculate total prices
            var totalDewasa = dewasa * hargaDewasa;
            var totalAnak = anak * hargaAnak;

            // Display the calculated prices (optional)
            console.log('Total Dewasa:', totalDewasa);
            console.log('Total Anak:', totalAnak);
        }
    </script>
@endsection