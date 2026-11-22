@extends('layout.main')

@section('container')
    {{-- ===== Sapaan ===== --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold leading-tight text-slate-800">
                Mau berlayar ke mana{{ Auth::check() ? ', ' . \Illuminate\Support\Str::words(Auth::user()->name, 1, '') : '' }}?
            </h1>
            <p class="mt-1 text-sm text-slate-500">Temukan tiket pilihan dan siapkan perjalanan berikutnya.</p>
        </div>
        @auth
            <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-4 py-2 text-xs font-medium text-green-700">
                <i class="bi bi-shield-check"></i> Akun terverifikasi
            </span>
        @endauth
    </div>

    {{-- ===== Hero ===== --}}
    <section class="relative mt-7 overflow-hidden rounded-3xl">
        <img src="/img/pexels.jpg" alt="Kapal penumpang di laut" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/50 to-transparent"></div>
        <div class="relative flex min-h-[260px] flex-col justify-center px-6 py-10 text-white sm:px-10">
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/80">Jelajahi Nusantara bersama Anang Ferry</p>
            <h2 class="mt-4 text-3xl font-bold leading-tight sm:text-4xl">
                Laut menghubungkan.<br>Perjalanan mendekatkan.
            </h2>
            <p class="mt-4 text-sm text-white/90">Pesan tiket kapal lebih mudah, dari mana saja.</p>
            <div class="mt-4 flex flex-wrap gap-5 text-xs">
                <span class="inline-flex items-center gap-1.5"><i class="bi bi-shield-check"></i> E-tiket langsung terbit</span>
                <span class="inline-flex items-center gap-1.5"><i class="bi bi-shield-check"></i> Pembayaran aman</span>
            </div>
        </div>
    </section>

    {{-- ===== Cari tiket ===== --}}
    <section class="mt-7 rounded-2xl border border-slate-200 bg-white p-6 sm:p-6"
        x-data="{
            tipe: 'sekali_jalan', // 'sekali_jalan' | 'pulang_pergi'
            tglBerangkat: '{{ now()->addDay()->toDateString() }}',
            tglPulang: '{{ now()->addDays(3)->toDateString() }}',
            bukaAsal: false,
            bukaTujuan: false,
            asal: '',
            tujuan: '',
            cariAsal: '',
            cariTujuan: '',
            daftar: @js($namaPelabuhan),
            get filterAsal() {
                if (!this.cariAsal.trim()) return this.daftar;
                return this.daftar.filter(p => p.toLowerCase().includes(this.cariAsal.toLowerCase()));
            },
            get filterTujuan() {
                if (!this.cariTujuan.trim()) return this.daftar;
                return this.daftar.filter(p => p.toLowerCase().includes(this.cariTujuan.toLowerCase()));
            },
            pilihAsal(val) {
                this.asal = val;
                this.bukaAsal = false;
                this.cariAsal = '';
                if (this.tujuan === val) this.tujuan = '';
            },
            pilihTujuan(val) {
                this.tujuan = val;
                this.bukaTujuan = false;
                this.cariTujuan = '';
                if (this.asal === val) this.asal = '';
            },
            tukar() {
                [this.asal, this.tujuan] = [this.tujuan, this.asal];
            }
        }">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-lg font-semibold text-slate-800">Cari tiket kapal</h3>
            <div class="flex items-center gap-5 text-xs">
                <label class="flex items-center gap-2 font-medium cursor-pointer transition"
                    :class="tipe === 'sekali_jalan' ? 'text-[#c8175d] font-semibold' : 'text-slate-600'">
                    <input type="radio" name="tipe_opsi" value="sekali_jalan" x-model="tipe" class="accent-[#c8175d]"> Sekali jalan
                </label>
                <label class="flex items-center gap-2 font-medium cursor-pointer transition"
                    :class="tipe === 'pulang_pergi' ? 'text-[#c8175d] font-semibold' : 'text-slate-600'">
                    <input type="radio" name="tipe_opsi" value="pulang_pergi" x-model="tipe" class="accent-[#c8175d]"> Pulang pergi
                </label>
            </div>
        </div>

        <form action="{{ route('informasi') }}" method="GET" class="mt-5 grid grid-cols-1 items-center gap-3 md:grid-cols-2"
            :class="tipe === 'pulang_pergi' ? 'lg:grid-cols-[1fr_auto_1fr_0.9fr_0.9fr_1fr_auto]' : 'lg:grid-cols-[1fr_auto_1fr_1fr_1fr_auto]'">
            
            <input type="hidden" name="tipe_perjalanan" :value="tipe">

            {{-- Pelabuhan Asal Dropdown Friendly --}}
            <div class="relative" @click.outside="bukaAsal = false">
                <input type="hidden" name="routeFrom" :value="asal" required>
                <button type="button" @click="bukaAsal = !bukaAsal; bukaTujuan = false; $nextTick(() => { if (bukaAsal) $refs.inputCariAsal?.focus() })"
                    class="group flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-left transition hover:border-[#c8175d] focus:border-[#c8175d] focus:outline-none">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <i class="bi bi-geo-alt-fill text-lg text-[#c8175d]"></i>
                        <div class="truncate">
                            <span class="block text-[11px] font-medium text-slate-400">Pelabuhan asal</span>
                            <span class="block truncate text-sm font-semibold" :class="asal ? 'text-slate-800' : 'text-slate-400'"
                                x-text="asal ? asal : 'Pilih pelabuhan asal'"></span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="bukaAsal ? 'rotate-180 text-[#c8175d]' : ''"></i>
                </button>

                {{-- Popover list pelabuhan asal --}}
                <div x-cloak x-show="bukaAsal" x-transition.opacity.duration.150ms
                    class="absolute left-0 top-full z-50 mt-2 w-full sm:w-80 rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl">
                    <div class="relative mb-2">
                        <i class="bi bi-search absolute left-3 top-2.5 text-xs text-slate-400"></i>
                        <input type="text" x-ref="inputCariAsal" x-model="cariAsal" placeholder="Cari nama pelabuhan..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-8 pr-3 text-xs text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:bg-white focus:outline-none">
                    </div>
                    <div class="max-h-56 overflow-y-auto space-y-1 pr-1 text-xs">
                        <template x-for="p in filterAsal" :key="p">
                            <button type="button" @click="pilihAsal(p)"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left transition"
                                :class="asal === p ? 'bg-pink-50 font-semibold text-[#c8175d]' : 'text-slate-700 hover:bg-slate-50'">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i class="bi bi-water text-xs text-slate-400" :class="asal === p ? 'text-[#c8175d]' : ''"></i>
                                    <span class="truncate" x-text="p"></span>
                                </div>
                                <span x-show="asal === p"><i class="bi bi-check2 text-sm text-[#c8175d]"></i></span>
                            </button>
                        </template>
                        <div x-show="filterAsal.length === 0" class="py-4 text-center text-xs text-slate-400">
                            Pelabuhan tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Tukar --}}
            <button type="button" @click="tukar()" aria-label="Tukar pelabuhan"
                class="mx-auto my-auto hidden h-10 w-10 items-center justify-center rounded-full bg-pink-50 text-[#c8175d] hover:bg-[#c8175d] hover:text-white transition-all shadow-sm lg:flex"
                title="Tukar pelabuhan asal & tujuan">
                <i class="bi bi-arrow-left-right transition-transform hover:rotate-180 duration-300"></i>
            </button>

            {{-- Pelabuhan Tujuan Dropdown Friendly --}}
            <div class="relative" @click.outside="bukaTujuan = false">
                <input type="hidden" name="routeTo" :value="tujuan" required>
                <button type="button" @click="bukaTujuan = !bukaTujuan; bukaAsal = false; $nextTick(() => { if (bukaTujuan) $refs.inputCariTujuan?.focus() })"
                    class="group flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-left transition hover:border-[#c8175d] focus:border-[#c8175d] focus:outline-none">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <i class="bi bi-geo-alt-fill text-lg text-[#c8175d]"></i>
                        <div class="truncate">
                            <span class="block text-[11px] font-medium text-slate-400">Pelabuhan tujuan</span>
                            <span class="block truncate text-sm font-semibold" :class="tujuan ? 'text-slate-800' : 'text-slate-400'"
                                x-text="tujuan ? tujuan : 'Pilih pelabuhan tujuan'"></span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="bukaTujuan ? 'rotate-180 text-[#c8175d]' : ''"></i>
                </button>

                {{-- Popover list pelabuhan tujuan --}}
                <div x-cloak x-show="bukaTujuan" x-transition.opacity.duration.150ms
                    class="absolute left-0 top-full z-50 mt-2 w-full sm:w-80 rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl">
                    <div class="relative mb-2">
                        <i class="bi bi-search absolute left-3 top-2.5 text-xs text-slate-400"></i>
                        <input type="text" x-ref="inputCariTujuan" x-model="cariTujuan" placeholder="Cari nama pelabuhan..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-8 pr-3 text-xs text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:bg-white focus:outline-none">
                    </div>
                    <div class="max-h-56 overflow-y-auto space-y-1 pr-1 text-xs">
                        <template x-for="p in filterTujuan" :key="p">
                            <button type="button" @click="pilihTujuan(p)"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left transition"
                                :class="tujuan === p ? 'bg-pink-50 font-semibold text-[#c8175d]' : 'text-slate-700 hover:bg-slate-50'">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i class="bi bi-water text-xs text-slate-400" :class="tujuan === p ? 'text-[#c8175d]' : ''"></i>
                                    <span class="truncate" x-text="p"></span>
                                </div>
                                <span x-show="tujuan === p"><i class="bi bi-check2 text-sm text-[#c8175d]"></i></span>
                            </button>
                        </template>
                        <div x-show="filterTujuan.length === 0" class="py-4 text-center text-xs text-slate-400">
                            Pelabuhan tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tanggal Berangkat --}}
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-2.5 focus-within:border-[#c8175d]">
                <i class="bi bi-calendar3 text-lg text-slate-400"></i>
                <span class="flex-1">
                    <span class="block text-[11px] text-slate-400">Tanggal berangkat</span>
                    <input type="date" name="tanggal_keberangkatan" x-model="tglBerangkat" min="{{ now()->toDateString() }}" required
                        class="w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-800 focus:outline-none focus:ring-0">
                </span>
            </label>

            {{-- Tanggal Pulang (Aktif saat mode Pulang Pergi) --}}
            <label x-cloak x-show="tipe === 'pulang_pergi'" x-transition class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-2.5 focus-within:border-[#c8175d]">
                <i class="bi bi-calendar3-event text-lg text-[#c8175d]"></i>
                <span class="flex-1">
                    <span class="block text-[11px] font-medium text-[#c8175d]">Tanggal pulang</span>
                    <input type="date" name="tanggal_pulang" x-model="tglPulang" :min="tglBerangkat"
                        :required="tipe === 'pulang_pergi'"
                        class="w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-800 focus:outline-none focus:ring-0">
                </span>
            </label>

            {{-- Penumpang --}}
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-2.5 focus-within:border-[#c8175d]">
                <i class="bi bi-people text-lg text-slate-400"></i>
                <div class="flex flex-1 gap-3">
                    <label class="flex-1">
                        <span class="block text-[11px] text-slate-400">Dewasa</span>
                        <input type="number" name="dewasa" min="0" max="10" value="1"
                            class="w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-800 focus:outline-none focus:ring-0">
                    </label>
                    <label class="flex-1">
                        <span class="block text-[11px] text-slate-400">Anak</span>
                        <input type="number" name="anak" min="0" max="10" value="0"
                            class="w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-800 focus:outline-none focus:ring-0">
                    </label>
                </div>
            </div>

            <button type="submit"
                class="flex items-center justify-center gap-2 rounded-xl bg-[#c8175d] px-8 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#a6104b] md:col-span-2 lg:col-span-1">
                <i class="bi bi-search"></i> Cari tiket
            </button>
        </form>
    </section>

    {{-- ===== Perjalanan mendatang (Tampil Rapi Penuh Tanpa Banner Promo) ===== --}}
    <div class="mt-7">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-800">Perjalanan mendatang</h3>
                @isset($perjalananMendatang)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-[11px] font-medium text-green-700">
                        <i class="bi bi-check2"></i> Terkonfirmasi
                    </span>
                @endisset
            </div>

            @isset($perjalananMendatang)
                @php $p = $perjalananMendatang; @endphp
                <div class="mt-6 grid grid-cols-1 items-center gap-6 sm:grid-cols-[1.1fr_1fr_1.3fr_1fr]">
                    <div>
                        <p class="flex items-center gap-2 font-semibold text-slate-800">
                            <i class="bi bi-tsunami text-[#c8175d]"></i> {{ $p->nama_kapal }}
                        </p>
                        <p class="mt-1.5 text-xs text-slate-500">{{ $p->kelas }} · {{ $p->jumlah }} penumpang</p>
                        <p class="mt-1 text-[11px] text-slate-400">#{{ $p->kode }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-semibold text-slate-800">{{ $p->jam_berangkat }}</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $p->asal }}</p>
                        <p class="text-[11px] text-slate-400">{{ $p->tgl_berangkat }}</p>
                    </div>
                    <div class="text-center text-[11px] text-slate-400">
                        {{ $p->durasi }}
                        <div class="mt-1 flex items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full border border-slate-300"></span>
                            <span class="flex-1 border-t border-dashed border-slate-300"></span>
                            <span class="h-1.5 w-1.5 rounded-full bg-[#c8175d]"></span>
                        </div>
                    </div>
                    <div>
                        <p class="text-3xl font-semibold text-slate-800">{{ $p->jam_tiba }}</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $p->tujuan }}</p>
                        <p class="text-[11px] text-slate-400">{{ $p->tgl_tiba }}</p>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                    <p class="flex items-center gap-2 text-[11px] text-slate-500">
                        <i class="bi bi-info-circle text-[#c8175d]"></i> Tiba di pelabuhan minimal 2 jam sebelum jadwal keberangkatan kapal.
                    </p>
                    <a href="{{ !empty($p->id_pesanan) ? route('invoices.eticket', $p->id_pesanan) : '/transaksi' }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#a6104b] transition">
                        <i class="bi bi-ticket-perforated"></i> Lihat e-tiket
                    </a>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center text-slate-500">
                    <i class="bi bi-ticket-perforated text-3xl text-slate-300"></i>
                    @auth
                        <p class="mt-3 text-sm">Belum ada perjalanan mendatang.</p>
                    @else
                        <p class="mt-3 text-sm">Masuk untuk melihat perjalanan dan e-tiket Anda.</p>
                        <a href="/login" class="mt-3 inline-flex items-center gap-1.5 rounded-xl bg-[#c8175d] px-5 py-2.5 text-xs font-semibold text-white hover:bg-[#a6104b] transition shadow-sm">
                            Login sekarang
                        </a>
                    @endauth
                </div>
            @endisset
        </section>
    </div>

    {{-- ===== Rute populer ===== --}}
    <section class="mt-10">
        <div class="flex items-end justify-between">
            <div>
                <h3 class="text-2xl font-semibold text-slate-800">Rute populer, cerita baru</h3>
                <p class="mt-1 text-sm text-slate-500">Pilihan perjalanan laut untuk destinasi berikutnya.</p>
            </div>
            <a href="{{ route('informasi') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#c8175d]">
                Lihat semua rute <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @php
            $gambarRute = ['/img/pexels.jpg', '/img/pexels2.jpg', '/img/pexels3.jpg'];
            $labelRute  = ['Favorit penumpang', 'Jelajah Nusantara', 'Pilihan hemat'];
        @endphp

        <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($uniqueRutes as $i => $rute)
                @php
                    $asal   = $rute->pelabuhanAsal->Nama_Pelabuhan;
                    $tujuan = $rute->pelabuhanTujuan->Nama_Pelabuhan;
                @endphp
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative h-36">
                        <img src="{{ $gambarRute[$i % 3] }}" alt="{{ $tujuan }}" class="h-full w-full object-cover">
                        <span class="absolute left-3 top-3 rounded-md bg-white px-2.5 py-1 text-[11px] font-medium text-slate-700">
                            {{ $labelRute[$i % 3] }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h4 class="flex items-center gap-2 text-lg font-semibold text-slate-800">
                            {{ $asal }} <i class="bi bi-arrow-right text-base"></i> {{ $tujuan }}
                        </h4>
                        @if (!empty($rute->kapal->Nama_Kapal ?? null))
                            <div class="mt-2 flex items-center justify-between text-[11px]">
                                <span class="text-slate-600 font-medium"><i class="bi bi-tsunami mr-1 text-[#c8175d]"></i> {{ $rute->kapal->Nama_Kapal }}</span>
                                @php
                                    $sisaKursiRute = $rute->tiket?->sum('Jumlah_Tiket') ?? 0;
                                @endphp
                                @if ($sisaKursiRute > 10)
                                    <span class="rounded bg-emerald-50 px-2 py-0.5 font-medium text-emerald-700">
                                        Sisa {{ $sisaKursiRute }} kursi
                                    </span>
                                @elseif ($sisaKursiRute > 0)
                                    <span class="rounded bg-amber-50 px-2 py-0.5 font-medium text-amber-800 animate-pulse">
                                        Sisa {{ $sisaKursiRute }} kursi!
                                    </span>
                                @else
                                    <span class="rounded bg-red-50 px-2 py-0.5 font-medium text-red-700">
                                        Kursi penuh
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-[11px] text-slate-400">
                                @php
                                    $minHarga = is_numeric($rute->harga) ? $rute->harga : ($rute->harga?->min('Harga') ?? null);
                                @endphp
                                @if ($minHarga)
                                    Mulai <span class="text-lg font-bold text-[#c8175d]">{{ rupiah($minHarga) }}</span> / orang
                                @else
                                    Lihat jadwal &amp; harga
                                @endif
                            </p>
                            <a href="{{ route('informasi', ['routeFrom' => $asal, 'routeTo' => $tujuan, 'tanggal_keberangkatan' => now()->addDay()->toDateString(), 'dewasa' => 1, 'anak' => 0]) }}"
                                class="inline-flex items-center gap-1.5 rounded-md bg-pink-50 px-4 py-2 text-[11px] font-semibold text-[#c8175d] hover:bg-pink-100">
                                Pilih <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full py-10 text-center text-sm text-slate-500">Belum ada jadwal mendatang.</p>
            @endforelse
        </div>
    </section>
@endsection