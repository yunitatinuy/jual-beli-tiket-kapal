<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-2xl text-[#c8175d]">
            <i class="bi bi-ticket-perforated-fill"></i>
        </span>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Data Tiket Perjalanan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola ketersediaan kuota tiket, rute pelayaran, dan tipe penumpang.</p>
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

    {{-- Formulir Tiket --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm max-w-2xl">
        <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm sm:text-base flex items-center gap-2">
                <i class="bi bi-pencil-square text-[#c8175d]"></i>
                {{ $updateData ? 'Perbarui Kuota Tiket' : 'Formulir Tambah Tiket Perjalanan' }}
            </h2>
            @if ($updateData)
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">Mode Edit</span>
            @endif
        </div>

        <form wire:submit="{{ $updateData ? 'update' : 'store' }}" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Rute Perjalanan</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input id="tanggalRute" type="date" wire:model.live="tanggalRute" title="Filter tanggal rute"
                        class="px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition sm:w-44" />
                    <select wire:model.live="ID_Rute"
                        class="flex-1 px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition" required>
                        <option value="">{{ $dataRute->isEmpty() ? '- Tidak ada jadwal di tanggal ini -' : '- Pilih Rute Jadwal -' }}</option>
                        @foreach($dataRute as $rute)
                            <option value="{{ $rute->ID_Rute }}">{{ $rute->label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('ID_Rute') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Penumpang & Kelas</label>
                <select wire:model="ID_Harga" wire:key="harga-{{ $ID_Rute }}" @disabled(!$ID_Rute)
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition disabled:opacity-50" required>
                    <option value="">{{ $ID_Rute ? ($dataHarga->isEmpty() ? '- Semua harga rute ini sudah punya tiket / belum ada harga -' : '- Pilih Tipe Penumpang & Kelas -') : '- Pilih rute terlebih dahulu -' }}</option>
                    @foreach($dataHarga as $harga)
                        <option value="{{ $harga->ID_Harga }}">{{ ucfirst($harga->Tipe_Penumpang) }} - {{ $harga->Kelas }} - {{ rupiah($harga->Harga) }}</option>
                    @endforeach
                </select>
                @error('ID_Harga') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Kuota Tiket (Kursi)</label>
                <input type="number" min="0" step="1" wire:model="Jumlah_Tiket" placeholder="Contoh: 50"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#c8175d] focus:ring-2 focus:ring-[#c8175d]/20 transition" required />
                @error('Jumlah_Tiket') <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3 pt-3">
                @if ($updateData == false)
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b] focus:outline-none">
                        <i class="bi bi-plus-lg"></i> Tambah Kuota Tiket
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
    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
        <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[280px]">
            {{-- Pencarian --}}
            <div class="relative w-full sm:w-64">
                <i class="bi bi-search absolute left-3.5 top-3 text-slate-400"></i>
                <input wire:model.live.debounce.500ms="search" type="search" placeholder="Cari rute / kelas tiket..."
                    class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d] shadow-sm" />
            </div>

            {{-- Filter Rute Perjalanan --}}
            <div class="w-full sm:w-56">
                <select wire:model.live="filterRute"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d] shadow-sm text-slate-700">
                    <option value="">Semua Rute Perjalanan</option>
                    @foreach ($semuaRute as $r)
                        <option value="{{ $r->ID_Rute }}">#{{ $r->ID_Rute }} {{ $r->label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div class="w-full sm:w-40">
                <select wire:model.live="filterKelas"
                    class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-[#c8175d]/20 focus:border-[#c8175d] shadow-sm text-slate-700">
                    <option value="">Semua Kelas</option>
                    @foreach ($semuaKelas as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Reset Filter --}}
            @if ($search || $filterRute || $filterKelas)
                <button type="button" wire:click="resetFilter"
                    class="inline-flex items-center gap-1.5 px-3 py-2.5 text-xs font-semibold text-[#c8175d] bg-pink-50 hover:bg-pink-100 rounded-xl transition"
                    title="Reset semua filter">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </button>
            @endif
        </div>

        <div class="text-xs text-slate-500">
            {{ $dataTiket->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    {{-- Tabel Tiket --}}
    <div class="overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50 uppercase text-[10px] tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">#</th>
                        <th class="px-4 py-3 font-semibold">Kode Tiket</th>
                        <th class="px-4 py-3 font-semibold">Rute Perjalanan</th>
                        <th class="px-4 py-3 font-semibold">Tipe Penumpang</th>
                        <th class="px-4 py-3 font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-center font-semibold">Sisa Tiket</th>
                        <th class="px-4 py-3 font-semibold">Tarif Tiket</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($dataTiket as $tiket)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $dataTiket->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-slate-800 whitespace-nowrap">#{{ $tiket->ID_Tiket }}</td>
                            <td class="px-4 py-3 text-slate-700 font-medium">
                                {{ $tiket->rute?->label ?? 'Rute tidak ditemukan' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                    {{ ucfirst($tiket->harga?->Tipe_Penumpang ?? '-') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-600 font-medium">{{ $tiket->harga?->Kelas ?? '-' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $tiket->Jumlah_Tiket > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $tiket->Jumlah_Tiket }} kursi
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-bold text-slate-800">{{ rupiah($tiket->harga?->Harga ?? 0) }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button wire:click="edit({{ $tiket->ID_Tiket }})"
                                        class="p-2 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition" title="Edit Tiket">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="return confirm('Apakah Anda yakin ingin menghapus data tiket ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $tiket->ID_Tiket }})"
                                        class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition" title="Hapus Tiket">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <i class="bi bi-ticket-perforated text-3xl text-slate-300"></i>
                                <p class="mt-2 text-xs">Belum ada kuota tiket yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
