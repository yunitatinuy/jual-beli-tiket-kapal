@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        $ada = isset($searchResults) && isset($searchResults['jadwal']);
        $dewasa = $dewasa ?? 0;
        $anak = $anak ?? 0;
        $jumlahPenumpang = $dewasa + $anak;

        // Ratakan jadwal -> satu kartu per (jadwal, kelas)
        $kartu = collect();
        if ($ada) {
            foreach ($searchResults['jadwal'] as $item) {
                $rute = $item['rute'];
                $berangkat = Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam));
                foreach ($item['offers'] as $kelas => $tipeHarga) {
                    // Kelas hanya bisa dipesan jika tiket untuk setiap tipe penumpang yang diminta tersedia
                    $kurang = [];
                    $total = 0;
                    foreach (['dewasa' => $dewasa, 'anak' => $anak] as $tipe => $jumlah) {
                        if ($jumlah <= 0) continue;
                        if (!isset($tipeHarga[$tipe])) {
                            $kurang[] = "tidak ada tiket {$tipe}";
                        } elseif ($tipeHarga[$tipe]['sisa'] < $jumlah) {
                            $kurang[] = "sisa tiket {$tipe} {$tipeHarga[$tipe]['sisa']}";
                        } else {
                            $total += $tipeHarga[$tipe]['harga'] * $jumlah;
                        }
                    }
                    $jam = (int) $berangkat->format('G');
                    $kartu->push([
                        'id' => $kartu->count(),
                        'rute' => $rute,
                        'kelas' => $kelas,
                        'tipeHarga' => $tipeHarga,
                        'berangkat' => $berangkat,
                        'total' => $total,
                        'kurang' => $kurang,
                        'ok' => empty($kurang),
                        'slot' => $jam >= 6 && $jam < 12 ? 'pagi' : ($jam >= 12 && $jam < 18 ? 'siang' : 'malam'),
                        'hargaOrang' => $tipeHarga['dewasa']['harga'] ?? ($tipeHarga['anak']['harga'] ?? 0),
                    ]);
                }
            }
        }

        $pertama = $kartu->first()['rute'] ?? null;
        $tanggal = $pertama ? Carbon::parse($pertama->Tanggal)->locale('id') : Carbon::parse(request('tanggal_keberangkatan', now()->addDay()))->locale('id');
        $tersedia = $kartu->where('ok', true);
        $hargaMin = $tersedia->min('hargaOrang');
        $hargaMax = $tersedia->max('hargaOrang');
        $daftarKelas = $kartu->pluck('kelas')->unique()->values();
        $asalReq = request('routeFrom', $pertama?->pelabuhanAsal?->Nama_Pelabuhan);
        $tujuanReq = request('routeTo', $pertama?->pelabuhanTujuan?->Nama_Pelabuhan);

        // Data untuk filter di browser
        $meta = $kartu->map(fn($k) => [
            'id' => $k['id'], 'slot' => $k['slot'], 'kelas' => $k['kelas'],
            'harga' => $k['hargaOrang'], 'ts' => $k['berangkat']->timestamp, 'ok' => $k['ok'],
        ])->values();

        // Tab tanggal: sehari sebelum sampai 2 hari sesudah
        $tabTanggal = collect([-1, 0, 1, 2])
            ->map(fn($d) => $tanggal->copy()->addDays($d))
            ->filter(fn($d) => $d->greaterThanOrEqualTo(now()->startOfDay()));
    @endphp

    <nav class="text-xs text-slate-500">
        <a href="/" class="hover:text-[#c8175d]">Beranda</a> <span class="mx-1">/</span> <span>Hasil pencarian</span>
    </nav>
    <h1 class="mt-5 text-[28px] font-semibold text-slate-800">Pilih tiket perjalananmu</h1>
    <p class="mt-3 text-sm text-slate-500">Temukan jadwal yang sesuai, lalu lengkapi data penumpang untuk melanjutkan pemesanan.</p>

    @if (session('error') || (isset($errors) && $errors->any()))
        <div class="mt-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">{{ session('error') ?? ($errors?->first() ?? '') }}</div>
    @endif

    {{-- ===== Form cari ulang ===== --}}
    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-lg font-semibold text-slate-800">Perjalanan yang kamu cari</h3>
            <div class="flex items-center gap-5 text-xs">
                <label class="flex items-center gap-2 font-medium text-slate-800"><input type="radio" checked class="accent-[#c8175d]"> Sekali jalan</label>
                <label class="flex items-center gap-2 text-slate-400"><input type="radio" disabled> Pulang pergi</label>
            </div>
        </div>

        @isset($namaPelabuhan)
            <datalist id="daftar-pelabuhan">@foreach ($namaPelabuhan as $nama)<option value="{{ $nama }}">@endforeach</datalist>
        @endisset

        <form action="{{ route('informasi') }}" method="GET" class="mt-5 grid grid-cols-1 items-center gap-3 md:grid-cols-2 lg:grid-cols-[1fr_auto_1fr_0.8fr_1fr_auto]"
            x-data="{
                bukaAsal: false,
                bukaTujuan: false,
                asal: @js($asalReq ?? ''),
                tujuan: @js($tujuanReq ?? ''),
                cariAsal: '',
                cariTujuan: '',
                daftar: @js($namaPelabuhan ?? []),
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

            {{-- Pelabuhan Asal Dropdown --}}
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

            {{-- Pelabuhan Tujuan Dropdown --}}
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

            <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-2.5 focus-within:border-[#c8175d]">
                <i class="bi bi-calendar3 text-lg text-slate-400"></i>
                <span class="flex-1"><span class="block text-[11px] text-slate-400">Tanggal berangkat</span>
                    <input type="date" name="tanggal_keberangkatan" value="{{ $tanggal->toDateString() }}" min="{{ now()->toDateString() }}" required
                        class="w-full border-0 bg-transparent p-0 text-sm font-medium focus:outline-none focus:ring-0"></span>
            </label>

            <div class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-2.5 focus-within:border-[#c8175d]">
                <i class="bi bi-people text-lg text-slate-400"></i>
                <div class="flex flex-1 gap-3">
                    <label class="flex-1"><span class="block text-[11px] text-slate-400">Dewasa</span>
                        <input type="number" name="dewasa" min="0" max="10" value="{{ $dewasa }}" class="w-full border-0 bg-transparent p-0 text-sm font-medium focus:outline-none focus:ring-0"></label>
                    <label class="flex-1"><span class="block text-[11px] text-slate-400">Anak</span>
                        <input type="number" name="anak" min="0" max="10" value="{{ $anak }}" class="w-full border-0 bg-transparent p-0 text-sm font-medium focus:outline-none focus:ring-0"></label>
                </div>
            </div>

            <button type="submit" class="flex items-center justify-center gap-2 rounded-xl bg-[#c8175d] px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#a6104b] md:col-span-2 lg:col-span-1">
                <i class="bi bi-search"></i> Cari ulang
            </button>
        </form>
    </section>

    @if ($ada)
        <div class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[304px_1fr]"
            x-data="{
                data: @js($meta),
                waktu: [], kelas: [], urut: 'awal',
                get terlihat() {
                    let l = this.data.filter(k =>
                        (this.waktu.length === 0 || this.waktu.includes(k.slot)) &&
                        (this.kelas.length === 0 || this.kelas.includes(k.kelas)));
                    l.sort((a, b) => this.urut === 'murah' ? a.harga - b.harga : this.urut === 'mahal' ? b.harga - a.harga : a.ts - b.ts);
                    return l.map(k => k.id);
                },
                get jumlahTersedia() { return this.data.filter(k => k.ok && this.terlihat.includes(k.id)).length },
                reset() { this.waktu = []; this.kelas = []; this.urut = 'awal' },
            }">

            {{-- ===== Sidebar filter ===== --}}
            <aside class="flex flex-col gap-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-semibold text-slate-800">Filter</h3>
                        <button type="button" @click="reset()" class="text-xs font-semibold text-[#c8175d]">Reset</button>
                    </div>

                    <p class="mt-5 text-sm font-semibold text-slate-800">Waktu keberangkatan</p>
                    <div class="mt-3 space-y-3 text-xs text-slate-600">
                        @foreach (['pagi' => 'Pagi · 06.00–11.59', 'siang' => 'Siang · 12.00–17.59', 'malam' => 'Malam · 18.00–05.59'] as $v => $l)
                            <label class="flex items-center gap-2"><input type="checkbox" value="{{ $v }}" x-model="waktu" class="rounded border-slate-300 accent-[#c8175d]"> {{ $l }}</label>
                        @endforeach
                    </div>

                    <div class="my-5 border-t border-slate-100"></div>
                    <p class="text-sm font-semibold text-slate-800">Kelas kapal</p>
                    <div class="mt-3 space-y-3 text-xs text-slate-600">
                        @foreach ($daftarKelas as $k)
                            <label class="flex items-center gap-2"><input type="checkbox" value="{{ $k }}" x-model="kelas" class="rounded border-slate-300 accent-[#c8175d]"> {{ $k }}</label>
                        @endforeach
                    </div>

                    @if ($hargaMin)
                        <div class="my-5 border-t border-slate-100"></div>
                        <p class="text-sm font-semibold text-slate-800">Harga per penumpang</p>
                        <p class="mt-3 text-xs text-slate-500">{{ rupiah($hargaMin) }} – {{ rupiah($hargaMax) }}</p>
                    @endif
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-800">Butuh bantuan?</h3>
                    <p class="mt-3 text-xs text-slate-500">Kami siap membantu menemukan tiket untuk perjalananmu.</p>
                    <a href="/informasi" class="mt-4 inline-block rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50">Hubungi bantuan</a>
                </section>
            </aside>

            {{-- ===== Hasil ===== --}}
            <div>
                {{-- Tab tanggal --}}
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    @foreach ($tabTanggal as $d)
                        @php $aktif = $d->isSameDay($tanggal); @endphp
                        <a href="{{ route('informasi', array_merge(request()->query(), ['tanggal_keberangkatan' => $d->toDateString()])) }}"
                            class="rounded-xl border px-4 py-3 text-xs {{ $aktif ? 'border-[#c8175d] bg-pink-50 text-[#c8175d]' : 'border-slate-200 bg-white text-slate-700 hover:border-[#c8175d]' }}">
                            <p class="font-medium">{{ $d->translatedFormat('D, d M') }}</p>
                            <p class="mt-2 {{ $aktif ? 'font-medium' : 'text-slate-400' }}">{{ $aktif && $hargaMin ? 'Mulai ' . rupiah($hargaMin) : 'Lihat jadwal' }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800"><span x-text="jumlahTersedia"></span> tiket tersedia</h3>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $asalReq }} <i class="bi bi-arrow-right"></i> {{ $tujuanReq }} · {{ $tanggal->translatedFormat('l, d F Y') }} · {{ $dewasa }} dewasa{{ $anak ? ', ' . $anak . ' anak' : '' }}
                        </p>
                    </div>
                    <select x-model="urut" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-semibold text-[#c8175d] focus:border-[#c8175d] focus:outline-none focus:ring-0">
                        <option value="awal">Urutkan: paling awal</option>
                        <option value="murah">Urutkan: termurah</option>
                        <option value="mahal">Urutkan: termahal</option>
                    </select>
                </div>

                <div class="mt-4 flex flex-col gap-4">
                    @foreach ($kartu as $k)
                        @php
                            $rute = $k['rute'];
                            $b = $k['berangkat'];
                            $jamTiba = !empty($rute->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
                            $tglTiba = !empty($rute->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('d M Y') : '';
                            $labelSlot = ['pagi' => 'Pilihan pagi', 'siang' => 'Pilihan siang', 'malam' => 'Pilihan malam'][$k['slot']];

                            $kapasitasKapal = $rute->kapal->Jumlah_Kursi ?? 0;
                            $sisaDewasa = $k['tipeHarga']['dewasa']['sisa'] ?? 0;
                            $sisaAnak = $k['tipeHarga']['anak']['sisa'] ?? 0;
                            $sisaKursiUtama = $sisaDewasa;
                            $isPenuh = !$k['ok'] || ($sisaDewasa < $dewasa) || ($sisaAnak < $anak) || ($sisaDewasa <= 0 && $sisaAnak <= 0);
                        @endphp

                        <form action="{{ route('pesantiket') }}" method="GET"
                            x-show="terlihat.includes({{ $k['id'] }})" :style="`order: ${terlihat.indexOf({{ $k['id'] }})}`"
                            class="rounded-2xl border bg-white p-6 transition shadow-sm"
                            :class="{{ $isPenuh ? 'true' : 'false' }} ? 'border-red-200 bg-red-50/20' : 'border-slate-200 hover:border-pink-200'">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                                        <i class="bi bi-tsunami text-[#c8175d]"></i> {{ $rute->kapal->Nama_Kapal ?? '-' }}
                                    </h4>
                                    <p class="mt-1 text-xs text-slate-500">{{ $k['kelas'] }} · Pelayaran langsung</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="rounded-lg bg-pink-50 px-3 py-1 font-semibold text-[#c8175d]">{{ $labelSlot }}</span>
                                    @if ($isPenuh)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1 font-semibold text-red-700">
                                            <i class="bi bi-x-circle-fill text-red-500"></i> Kursi Kapal Penuh
                                        </span>
                                    @elseif ($sisaKursiUtama <= 10)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1 font-semibold text-amber-800 animate-pulse">
                                            <i class="bi bi-fire text-amber-600"></i> Sisa {{ $sisaKursiUtama }} kursi lagi!
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                                            <i class="bi bi-people-fill text-emerald-600"></i> Sisa {{ $sisaKursiUtama }} kursi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 items-center gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-[1fr_auto_1fr]">
                                <div>
                                    <p class="text-3xl font-semibold text-slate-800">{{ $b->format('H.i') }} WIB</p>
                                    <p class="mt-2 text-sm text-slate-700">{{ $rute->pelabuhanAsal->Nama_Pelabuhan ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $b->locale('id')->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="text-center text-[11px] text-slate-400">
                                    {{ $rute->Durasi ?? '' }}
                                    <div class="my-1 flex w-36 items-center gap-1">
                                        <span class="h-2 w-2 rounded-full border border-[#c8175d]"></span>
                                        <span class="flex-1 border-t border-slate-300"></span>
                                        <span class="h-2 w-2 rounded-full bg-[#c8175d]"></span>
                                    </div>
                                    Langsung
                                </div>
                                <div class="sm:text-right">
                                    <p class="text-3xl font-semibold text-slate-800">{{ $jamTiba }}</p>
                                    <p class="mt-2 text-sm text-slate-700">{{ $rute->pelabuhanTujuan->Nama_Pelabuhan ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $tglTiba }}</p>
                                </div>
                            </div>

                            {{-- Informasi Kapasitas & Sisa Kursi Rute --}}
                            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 rounded-xl bg-slate-50/80 px-4 py-2.5 text-xs">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="bi bi-info-circle text-[#c8175d]"></i>
                                    <span>Kapasitas: <strong>{{ $kapasitasKapal }} kursi</strong></span>
                                    <span class="text-slate-300">|</span>
                                    @if ($isPenuh)
                                        <span class="font-semibold text-red-600">Kursi penuh untuk jumlah penumpang ini</span>
                                    @else
                                        <span>Tersedia: <strong>{{ $sisaDewasa }}</strong> dewasa, <strong>{{ $sisaAnak }}</strong> anak</span>
                                    @endif
                                </div>
                                <div>
                                    @foreach (['dewasa' => 'Dewasa', 'anak' => 'Anak'] as $tipe => $label)
                                        @isset($k['tipeHarga'][$tipe])
                                            <span class="text-slate-500">{{ $label }}: <strong class="text-slate-800">{{ rupiah($k['tipeHarga'][$tipe]['harga']) }}</strong>{{ !$loop->last ? ' · ' : '' }}</span>
                                        @endisset
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">
                                <p class="text-[11px] text-slate-500">Harga final termasuk fasilitas kapal</p>
                                <div class="flex items-center gap-4">
                                    @if (!$isPenuh && $k['ok'])
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-[#c8175d]">{{ rupiah($k['hargaOrang']) }} <span class="text-sm font-normal text-slate-500">/ orang</span></p>
                                            <p class="text-[11px] text-slate-500">{{ rupiah($k['total']) }} untuk {{ $dewasa }} dewasa{{ $anak ? ', ' . $anak . ' anak' : '' }}</p>
                                        </div>
                                        <input type="hidden" name="rute" value="{{ $rute->ID_Rute }}">
                                        <input type="hidden" name="kelas" value="{{ $k['kelas'] }}">
                                        <input type="hidden" name="dewasa" value="{{ $dewasa }}">
                                        <input type="hidden" name="anak" value="{{ $anak }}">
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-6 py-3 text-xs font-semibold text-white shadow-sm transition hover:bg-[#a6104b]">
                                            Pilih tiket <i class="bi bi-arrow-right"></i>
                                        </button>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <p class="text-xs font-medium text-red-600">
                                                {{ !empty($k['kurang']) ? Str::ucfirst(implode(', ', $k['kurang'])) : 'Kursi pada rute ini sudah penuh' }}
                                            </p>
                                            <button type="button" disabled class="cursor-not-allowed inline-flex items-center gap-1.5 rounded-xl bg-slate-200 px-5 py-2.5 text-xs font-semibold text-slate-500 shadow-none">
                                                <i class="bi bi-slash-circle"></i> Kursi Penuh
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    @endforeach

                    <p x-show="terlihat.length === 0" class="rounded-2xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                        Tidak ada jadwal yang cocok dengan filter. Coba reset filter.
                    </p>
                </div>

                <div class="mt-4 rounded-xl bg-pink-100/70 px-6 py-5">
                    <p class="text-sm font-semibold text-[#c8175d]">Sudah menemukan jadwal yang cocok?</p>
                    <p class="mt-2 text-xs text-slate-500">Pilih tiket untuk mengisi data pemesan dan {{ $jumlahPenumpang }} penumpang.</p>
                </div>
            </div>
        </div>
    @else
        {{-- Tidak ada hasil --}}
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-10 text-center">
            <i class="bi bi-search text-4xl text-slate-300"></i>
            <p class="mt-4 text-lg font-semibold text-slate-800">
                {{ isset($searchResults) ? ($searchResults['message'] ?? 'Jadwal tidak ditemukan.') : 'Silakan cari jadwal kapal terlebih dahulu.' }}
            </p>
            <p class="mt-2 text-sm text-slate-500">Coba ubah tanggal atau pelabuhan pada form di atas.</p>
            <a href="/" class="mt-5 inline-block rounded-lg bg-[#c8175d] px-6 py-3 text-xs font-semibold text-white hover:bg-[#a6104b]">Kembali ke beranda</a>
        </div>
    @endif
@endsection