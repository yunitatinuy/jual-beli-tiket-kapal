<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-2xl text-[#c8175d]">
            <i class="bi bi-water"></i>
        </span>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Data Kapal</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola data armada kapal laut, kelas layanan, dan kapasitas kursi.</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="p-4 text-xs font-semibold text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i> {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 text-xs font-semibold text-rose-800 rounded-xl bg-rose-50 border border-rose-200 flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i> {{ session('error') }}
        </div>
    @endif
    @if (session()->has('message'))
        <div class="p-4 text-xs font-semibold text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-2">
            <i class="bi bi-info-circle-fill text-emerald-600 text-base"></i> {{ session('message') }}
        </div>
    @endif

    {{-- Formulir Kapal --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm max-w-2xl">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm sm:text-base flex items-center gap-2">
                <i class="bi bi-pencil-square text-[#c8175d]"></i>
                {{ $updateData ? 'Perbarui Data Kapal' : 'Formulir Tambah Kapal' }}
            </h2>
            @if ($updateData)
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">Mode Edit</span>
            @endif
        </div>

        <form wire:submit="{{ $updateData ? 'update' : 'store' }}" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kapal</label>
                <input type="text" wire:model="Nama_Kapal" placeholder="Contoh: KM. Kelud"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition" required />
                @error('Nama_Kapal') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas Kapal</label>
                    <input type="text" wire:model="Kelas" placeholder="Contoh: Ekonomi, Bisnis, VIP"
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition" required />
                    @error('Kelas') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Kursi (Kapasitas)</label>
                    <input type="number" min="1" step="1" wire:model="Jumlah_Kursi" placeholder="Contoh: 150"
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition" required />
                    @error('Jumlah_Kursi') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3">
                @if ($updateData == false)
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b] focus:outline-none">
                        <i class="bi bi-plus-lg"></i> Tambah Data Kapal
                    </button>
                @else
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b] focus:outline-none">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <button type="button" wire:click="cancel"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
        <div class="relative w-full max-w-xs">
            <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
            <input wire:model.live.debounce.500ms="search" type="search" placeholder="Cari nama kapal / kelas..."
                class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d] shadow-sm" />
        </div>
        <div class="text-xs text-slate-500">
            {{ $dataKapal->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    {{-- Tabel Data Kapal --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">Kode Kapal</th>
                        <th class="px-4 py-3 font-semibold">Nama Kapal</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 font-semibold">Kapasitas Kursi</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dataKapal as $key => $value)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $dataKapal->firstItem() + $key }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-800 whitespace-nowrap">#{{ $value->ID_Kapal }}</td>
                            <td class="px-4 py-3 font-bold text-slate-800 whitespace-nowrap">
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-tsunami text-[#c8175d]"></i> {{ $value->Nama_Kapal }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700">{{ $value->Kelas }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full text-[11px]">
                                    <i class="bi bi-people"></i> {{ $value->Jumlah_Kursi }} kursi
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button wire:click="edit({{ $value->ID_Kapal }})"
                                        class="p-2 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition" title="Edit Kapal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="return confirm('Apakah Anda yakin ingin menghapus kapal ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $value->ID_Kapal }})"
                                        class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition" title="Hapus Kapal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <i class="bi bi-water text-3xl text-slate-300"></i>
                                <p class="mt-2 text-xs">Belum ada data kapal yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
