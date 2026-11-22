@php
    use Carbon\Carbon;
@endphp
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-2xl text-[#c8175d]">
            <i class="bi bi-people-fill"></i>
        </span>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Data Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola akun pengguna, hak akses, dan tanggal pendaftaran akun.</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="relative w-full max-w-xs">
            <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
            <input wire:model.live.debounce.500ms="search" type="search" placeholder="Cari nama / email pengguna..."
                class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d] shadow-sm" />
        </div>
        <div class="text-xs text-slate-500">
            {{ $dataPengguna->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Nama Pengguna</th>
                        <th class="px-4 py-3 font-semibold">Alamat Email</th>
                        <th class="px-4 py-3 font-semibold">Role Akun</th>
                        <th class="px-4 py-3 font-semibold">Tanggal Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dataPengguna as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-800 whitespace-nowrap">#{{ $user->id }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-pink-50 px-2.5 py-0.5 text-[11px] font-semibold text-[#c8175d] border border-pink-100">
                                        <i class="bi bi-shield-check"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700 border border-emerald-100">
                                        <i class="bi bi-person"></i> Pengguna
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <i class="bi bi-people text-3xl text-slate-300"></i>
                                <p class="mt-2 text-xs">Belum ada data pengguna yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
