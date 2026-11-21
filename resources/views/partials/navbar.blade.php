<header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto flex h-[88px] max-w-[1312px] items-center justify-between px-4 sm:px-6 lg:px-0">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-4">
            <img src="/img/logo3.png" alt="Anang Ferry" class="h-12 w-12 object-contain">
            <span class="text-xl font-bold uppercase tracking-tight text-[#c8175d]">Anang Ferry</span>
        </a>

        {{-- Menu --}}
        <nav class="hidden items-center gap-2 md:flex">
            @php
                $menu = [
                    ['Beranda', '/', request()->is('/') || request()->is('dashboard_pengguna')],
                    ['Tiket saya', '/transaksi', request()->is('transaksi*') || request()->is('pesanan*') || request()->is('invoices*')],
                ];
            @endphp
            @foreach ($menu as [$label, $url, $aktif])
                <a href="{{ $url }}"
                    class="relative px-6 py-[34px] text-sm font-medium transition
                    {{ $aktif ? 'text-[#c8175d] after:absolute after:inset-x-6 after:bottom-0 after:h-[3px] after:bg-[#c8175d]' : 'text-slate-500 hover:text-[#c8175d]' }}">
                    {{ $label }}
                </a>
            @endforeach

            {{-- Theme Switcher --}}
            <div class="mx-1">
                @include('partials.theme-switcher')
            </div>

            @auth
                @if (Auth::user()->isAdmin())
                    <a href="/admin/dashboard"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-pink-200 bg-pink-50 px-3 py-1.5 text-xs font-semibold text-[#c8175d] transition hover:bg-[#c8175d] hover:text-white">
                        <i class="bi bi-speedometer2"></i> Panel Admin
                    </a>
                @endif

                <button class="mx-1 p-2 text-slate-500 hover:text-[#c8175d]" aria-label="Notifikasi">
                    <i class="bi bi-bell text-lg"></i>
                </button>

                {{-- Dropdown user --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 text-sm font-semibold text-slate-800">
                        <i class="bi bi-person text-xl text-[#c8175d]"></i>
                        Halo, {{ \Illuminate\Support\Str::words(Auth::user()->name, 1, '') }}
                        <i class="bi bi-chevron-down text-xs text-slate-500"></i>
                    </button>
                    <div x-cloak x-show="open" x-transition
                        class="absolute right-0 mt-3 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg z-50">
                        @if (Auth::user()->isAdmin())
                            <a href="/admin/dashboard" class="block px-4 py-2.5 text-sm font-semibold text-[#c8175d] hover:bg-pink-50">
                                <i class="bi bi-speedometer2 mr-1.5"></i> Panel Admin
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                        @endif
                        <a href="/profil" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-pink-50 hover:text-[#c8175d]">Profil</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-pink-50 hover:text-[#c8175d]">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <a href="/login" class="ml-4 rounded-lg bg-[#c8175d] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#a6104b]">Login</a>
            @endguest
        </nav>
    </div>
</header>