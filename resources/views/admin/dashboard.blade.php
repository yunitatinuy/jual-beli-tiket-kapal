<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Dashboard Admin</title>

</head>


<!-- NAV BAR -->
<nav class="bg-gradient-to-br from-sky-800 to-slate-100 shadow-xl">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/img/logo.png" alt="logo" width="50" class="float-left flex flex-col object-cover">
            <span class="self-center text-3xl font-marko whitespace-nowrap uppercase">Anang Ferry</span>
        </a>

        <!-- dropdown -->
        <div class="relative" data-twe-dropdown-ref>
            <a class="flex items-center rounded bg-secondary-700 px-6 pb-2 pt-2.5 text-xs font-medium leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300 hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-primary-600 active:shadow-primary-2 motion-reduce:transition-none dark:shadow-black/30 dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong" href="#" type="button" id="dropdownMenuButton2" data-twe-dropdown-toggle-ref aria-expanded="false" data-twe-ripple-color="light">

                <span class=" w-3 mr-3">
                    <img src="/img/user.png" alt="user">
                </span>
                <span class="font-inter text-sm">Administrator</span>
            </a>
            <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-base shadow-lg data-[twe-dropdown-show]:block dark:bg-surface-dark" aria-labelledby="dropdownMenuButton2" data-twe-dropdown-menu-ref>
                <li>
                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-zinc-200/60 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline dark:bg-surface-dark dark:text-white dark:hover:bg-neutral-800/25 dark:focus:bg-neutral-800/25 dark:active:bg-neutral-800/25" href="#" data-twe-dropdown-item-ref>Pengaturan</a>
                </li>
                <li>
                    <a class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-zinc-200/60 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline dark:bg-surface-dark dark:text-white dark:hover:bg-neutral-800/25 dark:focus:bg-neutral-800/25 dark:active:bg-neutral-800/25" href="#" data-twe-dropdown-item-ref>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SIDEBAR -->

<div class="container">
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>

    <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full mt-[82px] px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
            <div class="container h-40 w-full mb-3 rounded-xl bg-sky-200">
                <div class="p-3 pt-5 ml-16 flex items-center">
                    <img src="/img/icons/user.png" width="70">
                </div>
                <span class="p-3 font-inter text-lg text-black ">Muhammad Dafa Putra</span>
            </div>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="dashboard" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/dashboard.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-4 whitespace-nowrap">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="kapal" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/ships.png" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-3 whitespace-nowrap">Kapal</span>
                    </a>
                </li>
                <li>
                    <a href="pelabuhan" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/harbor.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-4 whitespace-nowrap">Pelabuhan</span>
                    </a>
                </li>
                <li>
                    <a href="rute" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/route.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-4 whitespace-nowrap">Rute Perjalanan</span>
                    </a>
                </li>
                <li>
                    <a href="tiket" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/ticket.png" class="flex-shrink-0 w-6 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-3 whitespace-nowrap">Tiket</span>
                    </a>
                </li>
                <li>
                    <a href="pengguna" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/pengguna.png" class="flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-3 whitespace-nowrap">Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="pesanan" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-3 whitespace-nowrap">Pesanan</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
</div>

<!-- CONTENT -->

<div class="ms-72 mt-10">
    <span class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white group">
        <img src="/img/icons/dashboard.png" class="flex-shrink-0 w-8 h-8 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor">
        <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Dashboard</span>
    </span>

    <div class="container">
        <div class="flex flex-wrap">

            <!-- Pengguna Baru -->
            <div class="p-4 max-w-sm">
                <div class="flex rounded-lg h-full w-80 dark:bg-gray-800 bg-sky-400 p-6 flex-col">
                    <div class="flex items-center">
                        <img src="/img/icons/peng-baru.png" class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pengguna</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white dark:text-gray-300 text-8xl">
                            <!-- data dari data base nantinya --> 0
                        </p>
                        <a href="#" class="font-inter text-black dark:text-white hover:text-white inline-flex items-center">Selengkapnya
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Pemesanan -->
            <div class="p-4 max-w-sm">
                <div class="flex rounded-lg h-full w-80 dark:bg-gray-800 bg-sky-400 p-6 flex-col">
                    <div class="flex items-center">
                        <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pemesanan Tiket</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white dark:text-gray-300 text-8xl">
                            <!-- data dari data base nantinya --> 0
                        </p>
                        <a href="#" class="font-inter text-black dark:text-white hover:text-white inline-flex items-center">Selengkapnya
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Pemasukan -->
            <div class="p-4 max-w-sm">
                <div class="flex rounded-lg h-full w-80 dark:bg-gray-800 bg-sky-400 p-6 flex-col">
                    <div class="flex items-center">
                        <img src="/img/icons/dollar.png" class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400">
                        <span class="font-inter font-semibold mx-3 text-xl">Total Pemasukan</span>
                    </div>
                    <div class="flex flex-col justify-between flex-grow">
                        <p class="leading-relaxed text-white dark:text-gray-300 text-8xl">
                            <!-- data dari data base nantinya --> 0
                        </p>
                        <a href="#" class="font-inter text-black dark:text-white hover:text-white inline-flex items-center">Selengkapnya
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>



        </div>
    </div>

    </body>

</html>