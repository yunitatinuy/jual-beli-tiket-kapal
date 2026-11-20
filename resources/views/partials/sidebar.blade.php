    <!-- SIDEBAR -->

    <div class="flex w-full">
        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
        </button>

        <aside id="default-sidebar" class="top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">

            {{-- Photos --}}
            <div class="h-full px-3 w-64 py-4 overflow-y-auto bg-gradient-to-b from-[#fff4c6] to-white">
                <div class=" h-auto w-full mb-3 pb-3 rounded-xl bg-[#FFBF78]">
                    <div class="p-3 pt-5 mx-auto flex justify-center items-center">
                        <img src="/img/photos/dhaput.jpg" class="w-24 h-24 bg-cover rounded-full">
                    </div>
                    <span class="font-inter font-medium text-lg text-black flex justify-center items-center text-center">{{ Auth::user()->name }}</span>
                </div>

                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="dashboard" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/dashboard.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-4 whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="kapal" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/ships.png" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-3 whitespace-nowrap">Kapal</span>
                        </a>
                    </li>
                    <li>
                        <a href="pelabuhan" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/harbor.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-4 whitespace-nowrap">Pelabuhan</span>
                        </a>
                    </li>
                    <li>
                        <a href="rute" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/route.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-4 whitespace-nowrap">Rute Perjalanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/jadwal-berulang" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
                            <span class="flex-1 ms-4 whitespace-nowrap">Jadwal Berulang</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/checkin" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="10" height="10" rx="1"/></svg>
                            <span class="flex-1 ms-4 whitespace-nowrap">Check-in</span>
                        </a>
                    </li>
                    <li>
                        <button class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                            <img src="/img/icons/ticket.png" class="flex-shrink-0 w-6 h-7 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex w-full ms-3 whitespace-nowrap" sidebar-toggle-item>Tiket</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-example" class="hidden py-2 space-y-2">
                            <li>
                                <a href="tiket" class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-[#ffea95] pl-11">
                                    <img src="/img/icons/sphere.png" class="flex-shrink-0 w-2 h-2 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                                    <span class="flex-1 ms-4 whitespace-nowrap">Tiket Perjalanan</span>
                                </a>
                            </li>
                            <li>
                                <a href="harga" class="flex items-center w-full p-2 text-base font-normal text-gray-900 transition duration-75 rounded-lg group hover:bg-[#ffea95] pl-11">
                                    <img src="/img/icons/sphere.png" class="flex-shrink-0 w-2 h-2 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                                    <span class="flex-1 ms-4 whitespace-nowrap">Harga</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="pengguna" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/pengguna.png" class="flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-3 whitespace-nowrap">Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="pesanan" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/shopping-cart.png" class="flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-3 whitespace-nowrap">Pesanan</span>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>