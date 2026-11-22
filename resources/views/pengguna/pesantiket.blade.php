@extends('layout.main')

@section('container')
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        $rute = $quote['rute'];
        $items = $quote['items'];
        $dewasa = $items['dewasa']['jumlah'] ?? 0;
        $anak = $items['anak']['jumlah'] ?? 0;
        $jumlah = $dewasa + $anak;
        $berangkat = Carbon::parse($rute->Tanggal)->setTimeFrom(Carbon::parse($rute->Jam))->locale('id');
        $asal = $rute->pelabuhanAsal->Nama_Pelabuhan ?? '-';
        $tujuan = $rute->pelabuhanTujuan->Nama_Pelabuhan ?? '-';
        $jamTiba = !empty($rute->Jam_Tiba) ? Carbon::parse($rute->Jam_Tiba)->format('H.i') . ' WIB' : '-';
        $tglTiba = !empty($rute->Tanggal_Tiba) ? Carbon::parse($rute->Tanggal_Tiba)->locale('id')->translatedFormat('D, d M Y') : '';
        $user = Auth::user();
        $kembali = url()->previous();

        $input = 'block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#c8175d] focus:outline-none focus:ring-0 transition';
        $label = 'mb-2 block text-xs font-semibold text-slate-800';

        $oldPen = old('penumpang');
        $initList = [];
        if (is_array($oldPen) && count($oldPen) > 0) {
            foreach ($oldPen as $idx => $p) {
                $initList[] = [
                    'uid' => $idx + 1,
                    'tipe' => $idx < $dewasa ? 'dewasa' : 'anak',
                    'nama' => (string) ($p['Nama_Lengkap'] ?? ''),
                    'telepon' => (string) ($p['Telepon'] ?? ''),
                    'jk' => (string) ($p['Jenis_Kelamin'] ?? ''),
                    'alamat' => (string) ($p['Alamat'] ?? ''),
                ];
            }
        } else {
            for ($i = 0; $i < $jumlah; $i++) {
                $initList[] = [
                    'uid' => $i + 1,
                    'tipe' => $i < $dewasa ? 'dewasa' : 'anak',
                    'nama' => '',
                    'telepon' => '',
                    'jk' => '',
                    'alamat' => '',
                ];
            }
        }
    @endphp

    <nav class="text-xs text-slate-500">
        <a href="/" class="hover:text-[#c8175d]">Beranda</a> <span class="mx-1">/</span>
        <a href="{{ $kembali }}" class="hover:text-[#c8175d]">Hasil pencarian</a> <span class="mx-1">/</span>
        <span class="text-slate-700 font-medium">Data penumpang</span>
    </nav>

    <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-semibold text-slate-800">Lengkapi data perjalanan</h1>
            <p class="mt-2 text-sm text-slate-500">Isi data seluruh penumpang sesuai kartu identitas resmi yang berlaku.</p>
        </div>
        <a href="{{ $kembali }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-[#c8175d] hover:bg-pink-50 transition">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar tiket
        </a>
    </div>

    {{-- Langkah --}}
    <ol class="mt-6 grid grid-cols-2 gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-sm md:grid-cols-4">
        @foreach ([['Pilih tiket', 'done'], ['Data penumpang', 'now'], ['Pembayaran', 'next'], ['E-tiket terbit', 'next']] as $n => [$teks, $st])
            <li class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold
                    {{ $st === 'now' ? 'bg-[#c8175d] text-white' : 'bg-pink-50 text-[#c8175d]' }}">
                    @if ($st === 'done') <i class="bi bi-check2"></i> @else {{ $n + 1 }} @endif
                </span>
                <span class="{{ $st === 'now' ? 'font-semibold text-[#c8175d]' : 'text-slate-600' }}">{{ $teks }}</span>
            </li>
        @endforeach
    </ol>

    {{-- Error --}}
    @if (session('error'))
        <div class="mt-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800" role="alert">{{ session('error') }}</div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="mt-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pesantiket.store') }}" method="POST"
        x-data="{
            dewasaCount: {{ $dewasa }},
            anakCount: {{ $anak }},
            hargaDewasa: {{ $items['dewasa']['harga'] ?? 0 }},
            hargaAnak: {{ $items['anak']['harga'] ?? ($items['dewasa']['harga'] ?? 0) }},
            maxPenumpang: {{ \App\Services\BookingService::MAX_PENUMPANG }},
            penumpangs: @js($initList),
            akun: { nama: @js($user->name ?? ''), telepon: @js($user->telepon ?? '') },
            terisi: 0,
            get totalPenumpang() {
                return this.penumpangs.length;
            },
            get totalHarga() {
                return (this.dewasaCount * this.hargaDewasa) + (this.anakCount * this.hargaAnak);
            },
            formatRupiah(n) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(n);
            },
            hitung() {
                this.terisi = [...document.querySelectorAll('[data-penumpang]')].filter(p =>
                    [...p.querySelectorAll('[required]')].every(el => el.value && el.value.trim() !== '')).length;
            },
            tambahPenumpang(tipe = 'dewasa') {
                if (this.totalPenumpang >= this.maxPenumpang) {
                    alert('Maksimal pemesanan adalah ' + this.maxPenumpang + ' penumpang.');
                    return;
                }
                if (tipe === 'dewasa') {
                    this.dewasaCount++;
                } else {
                    this.anakCount++;
                }
                this.penumpangs.push({
                    uid: Date.now() + Math.random(),
                    tipe: tipe,
                    nama: '',
                    telepon: '',
                    jk: '',
                    alamat: ''
                });
                this.$nextTick(() => this.hitung());
            },
            hapusPenumpang(index) {
                if (this.totalPenumpang <= 1) {
                    alert('Minimal harus ada 1 penumpang.');
                    return;
                }
                const p = this.penumpangs[index];
                if (p.tipe === 'dewasa') {
                    if (this.dewasaCount <= 1 && this.anakCount > 0) {
                        alert('Pemesanan harus menyertakan minimal 1 penumpang dewasa.');
                        return;
                    }
                    this.dewasaCount--;
                } else {
                    this.anakCount--;
                }
                this.penumpangs.splice(index, 1);
                this.$nextTick(() => this.hitung());
            },
            samaDenganAkun(cek, index) {
                if (this.penumpangs[index]) {
                    this.penumpangs[index].nama = cek ? this.akun.nama : '';
                    this.penumpangs[index].telepon = cek ? this.akun.telepon : '';
                    this.$nextTick(() => this.hitung());
                }
            }
        }"
        x-init="hitung()" @input="hitung()" @change="hitung()"
        class="mt-6 grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_368px]">
        @csrf
        <input type="hidden" name="rute" value="{{ $rute->ID_Rute }}">
        <input type="hidden" name="kelas" value="{{ $quote['kelas'] }}">
        <input type="hidden" name="dewasa" :value="dewasaCount">
        <input type="hidden" name="anak" :value="anakCount">

        {{-- ===== Kolom kiri ===== --}}
        <div class="flex flex-col gap-5">
            <div class="rounded-xl bg-pink-100/70 px-6 py-5">
                <p class="text-sm font-semibold text-[#c8175d]"><i class="bi bi-shield-check mr-1.5"></i> Pastikan data sesuai identitas asli</p>
                <p class="mt-1 text-xs text-slate-600">Nama dan nomor telepon harus benar karena dipakai untuk verifikasi tiket saat boarding di pelabuhan.</p>
            </div>

            {{-- Pemesan (dari akun) --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Data pemesan</h3>
                <p class="mt-1 text-xs text-slate-500">E-tiket dan notifikasi perjalanan akan terhubung ke akun yang sedang masuk.</p>
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="{{ $label }}">Nama pemesan</p>
                        <p class="{{ $input }} bg-slate-50 font-medium text-slate-700">{{ $user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="{{ $label }}">Alamat email</p>
                        <p class="{{ $input }} bg-slate-50 font-medium text-slate-700">{{ $user->email ?? '-' }}</p>
                    </div>
                </div>
            </section>

            {{-- Header Penumpang + Tombol Tambah Penumpang di Dalam Tampilan --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-800">Data penumpang</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        <span x-text="terisi"></span> dari <span x-text="totalPenumpang"></span> penumpang terisi lengkap
                    </p>
                </div>
                {{-- Tombol Tambah Penumpang langsung di dalam tampilan --}}
                <div class="flex items-center gap-2">
                    <button type="button" @click="tambahPenumpang('dewasa')"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-pink-200 bg-pink-50 px-3.5 py-2 text-xs font-semibold text-[#c8175d] shadow-sm transition hover:bg-[#c8175d] hover:text-white"
                        title="Tambah penumpang dewasa baru">
                        <i class="bi bi-person-plus-fill"></i> + Tambah Dewasa
                    </button>
                    <button type="button" @click="tambahPenumpang('anak')"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-[#c8175d]"
                        title="Tambah penumpang anak baru">
                        <i class="bi bi-person-plus"></i> + Tambah Anak
                    </button>
                </div>
            </div>

            {{-- Formulir Penumpang Dinamis (Alpine x-for) --}}
            <template x-for="(p, i) in penumpangs" :key="p.uid">
                <section data-penumpang :data-index="i" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-pink-50 text-sm font-bold text-[#c8175d]"
                                x-text="i + 1"></span>
                            <div>
                                <h3 class="text-base font-semibold text-slate-800">
                                    Penumpang <span x-text="i + 1"></span>
                                </h3>
                                <p class="text-[11px] text-slate-400" x-text="p.tipe === 'dewasa' ? 'Kategori Dewasa (≥ 12 tahun)' : 'Kategori Anak (< 12 tahun)'"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="p.tipe === 'dewasa' ? 'bg-pink-100 text-[#c8175d]' : 'bg-amber-100 text-amber-800'"
                                x-text="p.tipe === 'dewasa' ? 'Dewasa' : 'Anak'"></span>

                            <button type="button" x-show="totalPenumpang > 1" @click="hapusPenumpang(i)"
                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100 transition"
                                title="Hapus data penumpang ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>

                    {{-- Opsi sama dengan data pemesan untuk penumpang 1 --}}
                    <div x-show="i === 0" class="mt-4">
                        <label class="flex items-center gap-2 text-xs font-medium text-[#c8175d] cursor-pointer">
                            <input type="checkbox" @change="samaDenganAkun($event.target.checked, i)" class="rounded border-slate-300 accent-[#c8175d]">
                            Sama dengan data pemesan akun saya
                        </label>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}">Nama lengkap sesuai identitas *</label>
                            <input type="text" :name="'penumpang[' + i + '][Nama_Lengkap]'" x-model="p.nama"
                                placeholder="Masukkan nama lengkap tanpa gelar"
                                required maxlength="255" class="{{ $input }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">Nomor telepon aktif *</label>
                            <input type="tel" :name="'penumpang[' + i + '][Telepon]'" x-model="p.telepon"
                                placeholder="08xxxxxxxxxx"
                                required inputmode="numeric" class="{{ $input }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">Jenis kelamin *</label>
                            <select :name="'penumpang[' + i + '][Jenis_Kelamin]'" x-model="p.jk" required class="{{ $input }}">
                                <option value="" disabled>Pilih jenis kelamin</option>
                                @foreach ($jenisKelaminOptions as $value)
                                    <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="{{ $label }}">Alamat lengkap *</label>
                            <textarea :name="'penumpang[' + i + '][Alamat]'" x-model="p.alamat" rows="1"
                                placeholder="Alamat sesuai No HP/identitas" required maxlength="255"
                                class="{{ $input }}"></textarea>
                        </div>
                    </div>
                    <p class="mt-4 text-[11px] text-slate-400">Bawa identitas resmi (No HP / Paspor / KIA) yang masih berlaku saat check-in di loket pelabuhan.</p>
                </section>
            </template>

            {{-- Tombol Tambah Penumpang Cepat di Bawah Form --}}
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-dashed border-slate-300 bg-white p-5">
                <div class="flex items-center gap-2">
                    <i class="bi bi-people text-xl text-[#c8175d]"></i>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Ingin menambah teman perjalanan?</p>
                        <p class="text-xs text-slate-500">Anda dapat memesan hingga maksimal <span x-text="maxPenumpang"></span> penumpang sekaligus.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="tambahPenumpang('dewasa')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-pink-50 border border-pink-200 px-4 py-2 text-xs font-semibold text-[#c8175d] hover:bg-[#c8175d] hover:text-white transition">
                        <i class="bi bi-person-plus-fill"></i> + Tambah Dewasa
                    </button>
                    <button type="button" @click="tambahPenumpang('anak')"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-50 border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                        <i class="bi bi-person-plus"></i> + Tambah Anak
                    </button>
                </div>
            </div>

            {{-- Persetujuan & submit --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <label class="flex items-start gap-3 text-xs text-slate-600 cursor-pointer">
                    <input type="checkbox" required class="mt-0.5 rounded border-slate-300 accent-[#c8175d]">
                    <span>Saya memastikan data pemesan dan seluruh penumpang sudah benar serta menyetujui Syarat &amp; Ketentuan dan Kebijakan Privasi Anang Ferry.</span>
                </label>
                <div class="mt-5 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-5">
                    <div>
                        <p class="text-xs text-slate-500">Total untuk <span x-text="totalPenumpang"></span> penumpang</p>
                        <p class="text-3xl font-bold text-[#c8175d]" x-text="formatRupiah(totalHarga)"></p>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#c8175d] px-8 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#a6104b]">
                        Lanjut ke pembayaran <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
                <p class="mt-4 text-[11px] text-slate-400">
                    Pembayaran diproses lewat Midtrans (Virtual Account, QRIS, GoPay, ShopeePay, dan Kartu). Kursi ditahan selama
                    <strong>{{ $batasBayarMenit }} menit</strong>; jika belum dibayar, pesanan otomatis dibatalkan.
                </p>
            </section>
        </div>

        {{-- ===== Sidebar ===== --}}
        <aside class="flex flex-col gap-5 lg:sticky lg:top-28">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Ringkasan perjalanan</h3>
                <p class="mt-4 flex items-center gap-2 text-xl font-semibold text-[#c8175d]">{{ $asal }} <i class="bi bi-arrow-right"></i> {{ $tujuan }}</p>
                <p class="mt-1.5 text-sm font-semibold text-slate-800">{{ $rute->kapal->Nama_Kapal ?? '-' }} · {{ $quote['kelas'] }}</p>
                <p class="mt-1 text-xs text-slate-500">
                    Sekali jalan · <span x-text="dewasaCount"></span> dewasa<span x-show="anakCount > 0" x-text="', ' + anakCount + ' anak'"></span>
                </p>

                <div class="mt-5 rounded-xl bg-slate-50 p-4">
                    <p class="text-[10px] font-medium uppercase text-slate-500">Berangkat · {{ $berangkat->translatedFormat('D, d M Y') }}</p>
                    <p class="mt-1 text-lg font-semibold text-slate-800">{{ $berangkat->format('H.i') }} WIB · {{ $asal }}</p>
                    @if (!empty($rute->Durasi))
                        <p class="my-3 flex items-center gap-2 text-xs text-[#c8175d]"><i class="bi bi-arrow-down"></i> {{ $rute->Durasi }} · Langsung</p>
                    @else
                        <div class="my-3 border-t border-dashed border-slate-300"></div>
                    @endif
                    <p class="text-[10px] font-medium uppercase text-slate-500">Tiba · {{ $tglTiba }}</p>
                    <p class="mt-1 text-lg font-semibold text-slate-800">{{ $jamTiba }} · {{ $tujuan }}</p>
                </div>
                <a href="{{ $kembali }}" class="mt-4 inline-block text-xs font-semibold text-[#c8175d] hover:underline">Ganti jadwal kapal</a>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Rincian harga</h3>
                <div class="mt-5 space-y-4 text-sm">
                    {{-- Dewasa --}}
                    <div x-show="dewasaCount > 0">
                        <div class="flex justify-between text-slate-600">
                            <span>Tiket dewasa × <span x-text="dewasaCount"></span></span>
                            <span class="font-semibold text-slate-800" x-text="formatRupiah(dewasaCount * hargaDewasa)"></span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400"><span x-text="formatRupiah(hargaDewasa)"></span> / penumpang</p>
                    </div>

                    {{-- Anak --}}
                    <div x-show="anakCount > 0">
                        <div class="flex justify-between text-slate-600">
                            <span>Tiket anak × <span x-text="anakCount"></span></span>
                            <span class="font-semibold text-slate-800" x-text="formatRupiah(anakCount * hargaAnak)"></span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400"><span x-text="formatRupiah(hargaAnak)"></span> / penumpang</p>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5">
                    <span class="text-xs text-slate-500">Total pembayaran</span>
                    <span class="text-2xl font-bold text-[#c8175d]" x-text="formatRupiah(totalHarga)"></span>
                </div>
            </section>

            <section class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-6">
                <h3 class="text-sm font-semibold text-emerald-800"><i class="bi bi-shield-check mr-1 text-emerald-600"></i> Data penumpang terlindungi</h3>
                <p class="mt-2 text-xs text-emerald-700 leading-relaxed">Data yang Anda masukkan dilindungi enkripsi aman dan hanya digunakan untuk keperluan manifest kapal serta tiket.</p>
            </section>
        </aside>
    </form>
@endsection
