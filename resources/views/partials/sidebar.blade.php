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
            <div class="h-full px-3 w-full py-4 overflow-y-auto bg-gradient-to-b from-[#fff4c6] to-white">
                <div class=" h-auto w-full mb-3 pb-3 rounded-xl bg-[#FFBF78]">
                    <div class="p-3 pt-5 mx-auto flex justify-center items-center">
                        <img src="/img/photos/dhaput.jpg" class="w-24 h-24 bg-cover rounded-full">
                    </div>
                    <span class="font-inter font-medium text-lg text-black flex justify-center items-center text-center">Administrator</span>
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
                        <a href="tiket" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-[#ffea95] group">
                            <img src="/img/icons/ticket.png" class="flex-shrink-0 w-6 h-7 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                            <span class="flex-1 ms-3 whitespace-nowrap">Tiket</span>
                        </a>
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