<nav class="bg-[#fcc09b] shadow-2xl ">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-3">
        <a href="/dashboard_pengguna" class="flex items-center space-x-3 rtl:space-x-reverse hover:text-slate-50">
            <img src="img/logo3.png" alt="logo" class="float-left flex flex-col object-cover w-16">
            <span class="self-center text-3xl font-marko whitespace-nowrap uppercase hover:text-slate-50">Anang Ferry</span>
        </a>

        <div class="sidebar">
            <ul class="flex flex-row font-marko">
                @auth
                <li><a href="/informasi" class="flex items-center translate-y-2 px-2 mx-1 hover:text-slate-50">Informasi</a></li>
                <li><a href="/transaksi" class="flex items-center translate-y-2 px-2 mx-1 hover:text-slate-50">Transaksi</a></li>
                <li>
                    <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover" data-dropdown-trigger="hover" class="flex items-center rounded bg-[#ED9455] px-6 pb-2 pt-2.5 text-xs font-medium leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-[#F97300] hover:shadow-primary-2 focus:bg-[#ED9455]-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-[#ED9455] active:shadow-primary-2 motion-reduce:transition-none" type="button">{{ Auth::user()->name }}
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownHover" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-32">
                        <ul class="py-2 text-xs text-gray-700" aria-labelledby="dropdownHoverButton">
                            <li>
                                <a href="/profil" class="block px-4 py-2 w-full text-center hover:bg-gray-100 hover:text-[#F97300]">Profile</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    <button type="submit" class="block px-4 py-2 w-full text-center hover:bg-gray-100 hover:text-[#F97300]">
                                        @csrf
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
                @endauth

                @guest
                <li><a href="/login" class="bg-[#ffad7a] px-3 py-2 mx-1 rounded-lg hover:text-slate-50">Login</a></li>
                @endguest
            </ul>
        </div>

    </div>
</nav>
