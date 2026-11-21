<!-- TOP NAVBAR -->
<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6">
    <div class="flex items-center gap-3">
        <button type="button" @click="sidebarOpen = !sidebarOpen"
            class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#c8175d]/20 lg:hidden">
            <span class="sr-only">Toggle Sidebar</span>
            <i class="bi bi-list text-2xl"></i>
        </button>

        <nav class="hidden sm:flex items-center gap-2 text-xs text-slate-400">
            <a href="/admin/dashboard" class="hover:text-slate-600">Admin</a>
            <span>/</span>
            <span class="font-semibold text-slate-700 capitalize">
                {{ str_replace(['admin/', '-'], ['', ' '], request()->path()) ?: 'Dashboard' }}
            </span>
        </nav>
    </div>

    <div class="flex items-center gap-4">
        {{-- Status Hari & Tanggal --}}
        <div class="hidden md:flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-xs text-slate-600 border border-slate-200">
            <i class="bi bi-calendar3 text-[#c8175d]"></i>
            <span>{{ now()->locale('id')->translatedFormat('l, d M Y') }}</span>
        </div>

        {{-- Shortcut ke Web Pengguna --}}
        <a href="{{ route('home') }}"
            class="hidden sm:inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-[#c8175d]">
            <i class="bi bi-eye"></i> Web Pengguna
        </a>

        {{-- Theme Switcher (Terang / Gelap / Auto) --}}
        @include('partials.theme-switcher')

        {{-- User Menu --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-2.5 rounded-lg p-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#c8175d] text-xs font-bold text-white">
                    {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                </div>
                <span class="hidden sm:inline-block">{{ Auth::user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down text-xs text-slate-400"></i>
            </button>

            <div x-cloak x-show="open" x-transition
                class="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg z-50">
                <div class="border-b border-slate-100 px-4 py-2.5">
                    <p class="text-xs font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="truncate text-[11px] text-slate-400">{{ Auth::user()->email }}</p>
                </div>
                <a href="/profil" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-pink-50 hover:text-[#c8175d]">
                    <i class="bi bi-person mr-2"></i> Profil Akun
                </a>
                <a href="/admin/dashboard" class="block px-4 py-2 text-xs font-medium text-slate-700 hover:bg-pink-50 hover:text-[#c8175d]">
                    <i class="bi bi-speedometer2 mr-2"></i> Dashboard
                </a>
                <div class="border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-xs font-semibold text-rose-600 hover:bg-rose-50">
                            <i class="bi bi-box-arrow-right mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
