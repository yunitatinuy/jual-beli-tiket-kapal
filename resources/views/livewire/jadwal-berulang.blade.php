@php
    use Carbon\Carbon;
    $input = 'block w-full p-2.5 text-sm bg-white border rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500';
    $err = 'block mt-1 text-xs text-rose-600';
@endphp
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-2xl text-[#c8175d]">
            <i class="bi bi-arrow-repeat"></i>
        </span>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">Jadwal Berulang</h1>
            <p class="text-xs sm:text-sm text-slate-500">Buat banyak jadwal sekaligus secara otomatis, lengkap dengan harga dan kuota tiket.</p>
        </div>
    </div>

    @if ($hasil)
        <div class="p-4 mt-4 text-sm border rounded-xl bg-emerald-50 border-emerald-200 text-emerald-800">
            <strong>{{ $hasil['dibuat'] }} jadwal dibuat.</strong>
            @if ($hasil['dilewati'])
                {{ $hasil['dilewati'] }} jadwal dilewati karena sudah ada.
            @endif
            Lihat di menu <a href="/admin/rute" class="font-semibold underline">Rute Perjalanan</a>.
        </div>
    @endif

    <form wire:submit="simpan" class="grid gap-6 mt-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            {{-- Lintasan --}}
            <section class="p-5 bg-white border rounded-xl border-slate-200">
                <h2 class="mb-4 font-semibold text-slate-900">1. Lintasan & kapal</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Pelabuhan asal</label>
                        <select wire:model.live="asal" class="{{ $input }}">
                            <option value="">Pilih</option>
                            @foreach ($pelabuhanList as $p)
                                <option value="{{ $p->ID_Pelabuhan }}">{{ $p->Nama_Pelabuhan }}</option>
                            @endforeach
                        </select>
                        @error('asal') <span class="{{ $err }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Pelabuhan tujuan</label>
                        <select wire:model.live="tujuan" class="{{ $input }}">
                            <option value="">Pilih</option>
                            @foreach ($pelabuhanList as $p)
                                <option value="{{ $p->ID_Pelabuhan }}">{{ $p->Nama_Pelabuhan }}</option>
                            @endforeach
                        </select>
                        @error('tujuan') <span class="{{ $err }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Kapal</label>
                        <select wire:model.live="kapal" class="{{ $input }}">
                            <option value="">Pilih</option>
                            @foreach ($kapalList as $k)
                                <option value="{{ $k->ID_Kapal }}">{{ $k->Nama_Kapal }} ({{ $k->Jumlah_Kursi }} kursi)</option>
                            @endforeach
                        </select>
                        @error('kapal') <span class="{{ $err }}">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            {{-- Waktu --}}
            <section class="p-5 bg-white border rounded-xl border-slate-200">
                <h2 class="mb-4 font-semibold text-slate-900">2. Tanggal, hari & jam</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Mulai</label>
                        <input type="date" wire:model.live="mulai" min="{{ now()->toDateString() }}" class="{{ $input }}">
                        @error('mulai') <span class="{{ $err }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Sampai</label>
                        <input type="date" wire:model.live="selesai" min="{{ now()->toDateString() }}" class="{{ $input }}">
                        @error('selesai') <span class="{{ $err }}">{{ $message }}</span> @enderror
                    </div>
                </div>

                <label class="block mt-4 mb-2 text-sm font-medium text-slate-700">Berlaku di hari</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($namaHari as $nilai => $nama)
                        <label class="cursor-pointer">
                            <input type="checkbox" wire:model.live="hari" value="{{ $nilai }}" class="sr-only peer">
                            <span class="inline-block px-3 py-1.5 text-sm font-medium border rounded-lg border-slate-300 text-slate-600 peer-checked:bg-sky-600 peer-checked:border-sky-600 peer-checked:text-white">{{ $nama }}</span>
                        </label>
                    @endforeach
                </div>
                @error('hari') <span class="{{ $err }}">{{ $message }}</span> @enderror

                <label class="block mt-4 mb-2 text-sm font-medium text-slate-700">Jam keberangkatan</label>
                <div class="flex flex-wrap items-start gap-3">
                    @foreach ($jam as $i => $j)
                        <div wire:key="jam-{{ $i }}">
                            <div class="flex items-center gap-1">
                                <input type="time" wire:model.live.debounce.400ms="jam.{{ $i }}" class="p-2 text-sm border rounded-lg w-28 border-slate-300">
                                @if (count($jam) > 1)
                                    <button type="button" wire:click="hapusJam({{ $i }})" class="p-1.5 text-slate-400 hover:text-rose-600" title="Hapus jam">&times;</button>
                                @endif
                            </div>
                            @error("jam.$i") <span class="{{ $err }}">{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                    <button type="button" wire:click="tambahJam" class="px-3 py-2 text-sm font-medium border border-dashed rounded-lg text-sky-700 border-sky-300 hover:bg-sky-50">+ Tambah jam</button>
                </div>
                @error('jam') <span class="{{ $err }}">{{ $message }}</span> @enderror
            </section>

            {{-- Tarif --}}
            <section class="p-5 bg-white border rounded-xl border-slate-200">
                <h2 class="mb-1 font-semibold text-slate-900">3. Harga & kuota per jadwal</h2>
                <p class="mb-4 text-xs text-slate-500">Matikan kelas yang tidak dijual di lintasan ini.</p>
                @error('tarif') <span class="{{ $err }} mb-3">{{ $message }}</span> @enderror
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($kelasList as $kelas)
                        <div wire:key="kelas-{{ $kelas }}" class="p-4 border rounded-lg {{ $tarif[$kelas]['aktif'] ? 'border-slate-200' : 'border-dashed border-slate-200 opacity-60' }}">
                            <label class="flex items-center justify-between mb-3">
                                <span class="font-semibold text-slate-900">Kelas {{ $kelas }}</span>
                                <span class="flex items-center gap-2 text-xs text-slate-500">
                                    Dijual
                                    <input type="checkbox" wire:model.live="tarif.{{ $kelas }}.aktif" class="rounded border-slate-300 text-sky-600">
                                </span>
                            </label>
                            @if ($tarif[$kelas]['aktif'])
                                <table class="w-full text-sm">
                                    <thead class="text-xs text-left text-slate-500">
                                        <tr><th class="pb-1 font-medium">Tipe</th><th class="pb-1 font-medium">Harga (Rp)</th><th class="pb-1 font-medium">Kuota</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach (['dewasa' => 'Dewasa', 'anak' => 'Anak'] as $tipe => $label)
                                            <tr>
                                                <td class="py-1 pr-2 text-slate-700">{{ $label }}</td>
                                                <td class="py-1 pr-2">
                                                    <input type="number" min="1000" step="500" wire:model="tarif.{{ $kelas }}.{{ $tipe }}.harga" placeholder="75000" class="w-full p-2 text-sm border rounded-lg border-slate-300">
                                                    @error("tarif.$kelas.$tipe.harga") <span class="{{ $err }}">{{ $message }}</span> @enderror
                                                </td>
                                                <td class="py-1">
                                                    <input type="number" min="0" step="1" wire:model="tarif.{{ $kelas }}.{{ $tipe }}.kuota" placeholder="50" class="w-full p-2 text-sm border rounded-lg border-slate-300">
                                                    @error("tarif.$kelas.$tipe.kuota") <span class="{{ $err }}">{{ $message }}</span> @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Ringkasan --}}
        <aside class="xl:sticky xl:top-6 h-fit">
            <div class="p-5 bg-white border rounded-xl border-slate-200">
                <h2 class="font-semibold text-slate-900">Ringkasan</h2>
                <p class="mt-3 text-4xl font-bold {{ $jumlahRencana > \App\Services\JadwalGenerator::MAKS_JADWAL ? 'text-rose-600' : 'text-slate-900' }}">{{ $jumlahRencana }}</p>
                <p class="text-sm text-slate-500">jadwal akan dibuat
                    @if ($jumlahRencana > \App\Services\JadwalGenerator::MAKS_JADWAL)
                        <span class="block text-rose-600">Melebihi batas {{ \App\Services\JadwalGenerator::MAKS_JADWAL }}. Persempit rentang tanggal.</span>
                    @endif
                </p>

                @if ($contohRencana)
                    <ul class="mt-4 space-y-1 text-sm text-slate-700">
                        @foreach ($contohRencana as $r)
                            <li class="flex justify-between"><span>{{ Carbon::parse($r['tanggal'])->translatedFormat('D, d M Y') }}</span><span class="font-mono">{{ $r['jam'] }}</span></li>
                        @endforeach
                        @if ($jumlahRencana > count($contohRencana))
                            <li class="text-slate-400">... dan {{ $jumlahRencana - count($contohRencana) }} lainnya</li>
                        @endif
                    </ul>
                @endif

                <p class="mt-4 text-xs text-slate-500">Jadwal dengan kapal, lintasan, tanggal dan jam yang sama tidak akan dibuat dua kali. Jam yang sudah lewat hari ini dilewati.</p>

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full px-4 py-2.5 mt-5 text-sm font-semibold text-white rounded-lg bg-sky-600 hover:bg-sky-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="simpan">Buat {{ $jumlahRencana }} jadwal</span>
                    <span wire:loading wire:target="simpan">Membuat jadwal...</span>
                </button>
            </div>
        </aside>
    </form>
</div>
