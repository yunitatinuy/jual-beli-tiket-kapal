<!-- SIDEBAR -->
<div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
</div>

<aside id="default-sidebar"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shrink-0"
    aria-label="Sidebar">

    {{-- Brand Header --}}
    <div class="flex h-16 items-center justify-between border-b border-slate-100 px-6">
        <a href="/admin/dashboard" class="flex items-center gap-3">
            <img src="/img/logo3.png" alt="Anang Ferry" class="h-9 w-9 object-contain">
            <div>
                <span class="block text-base font-bold uppercase tracking-tight text-[#c8175d]">Anang Ferry</span>
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Admin Portal</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 lg:hidden">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    {{-- Admin Profile Card --}}
    <div class="m-4 rounded-xl border border-pink-100 bg-pink-50/50 p-3.5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#c8175d] text-sm font-bold text-white shadow-sm">
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-medium text-slate-500">Administrator</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex-1 overflow-y-auto px-4 py-2 space-y-6">
        @php
            $menuGroups = [
                'Utama' => [
                    ['label' => 'Dashboard', 'url' => '/admin/dashboard', 'icon' => 'bi-grid-1x2-fill', 'active' => request()->is('admin/dashboard')],
                ],
                'Master Data' => [
                    ['label' => 'Kapal', 'url' => '/admin/kapal', 'icon' => 'bi-water', 'active' => request()->is('admin/kapal*')],
                    ['label' => 'Pelabuhan', 'url' => '/admin/pelabuhan', 'icon' => 'bi-geo-alt-fill', 'active' => request()->is('admin/pelabuhan*')],
                    ['label' => 'Rute Perjalanan', 'url' => '/admin/rute', 'icon' => 'bi-signpost-split-fill', 'active' => request()->is('admin/rute*')],
                    ['label' => 'Jadwal Berulang', 'url' => '/admin/jadwal-berulang', 'icon' => 'bi-arrow-repeat', 'active' => request()->is('admin/jadwal-berulang*')],
                ],
                'Tiket & Operasional' => [
                    ['label' => 'Check-in & QR', 'url' => '/admin/checkin', 'icon' => 'bi-qr-code-scan', 'active' => request()->is('admin/checkin*')],
                    ['label' => 'Tiket Perjalanan', 'url' => '/admin/tiket', 'icon' => 'bi-ticket-perforated-fill', 'active' => request()->is('admin/tiket*')],
                    ['label' => 'Kelola Harga', 'url' => '/admin/harga', 'icon' => 'bi-cash-stack', 'active' => request()->is('admin/harga*')],
                    ['label' => 'Data Pesanan', 'url' => '/admin/pesanan', 'icon' => 'bi-receipt-cutoff', 'active' => request()->is('admin/pesanan*')],
                ],
                'Pengguna' => [
                    ['label' => 'Data Pengguna', 'url' => '/admin/pengguna', 'icon' => 'bi-people-fill', 'active' => request()->is('admin/pengguna*')],
                ],
            ];
        @endphp

        @foreach ($menuGroups as $group => $items)
            <div>
                <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $group }}</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($items as $item)
                        <li>
                            <a href="{{ $item['url'] }}"
                                class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition {{ $item['active'] ? 'bg-[#c8175d] text-white shadow-sm' : 'text-slate-600 hover:bg-pink-50 hover:text-[#c8175d]' }}">
                                <i class="bi {{ $item['icon'] }} text-base"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    {{-- Bottom Action (Logout) --}}
    <div class="border-t border-slate-100 p-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-2.5 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                <i class="bi bi-box-arrow-right text-base"></i>
                Keluar (Logout)
            </button>
        </form>
    </div>
</aside>
