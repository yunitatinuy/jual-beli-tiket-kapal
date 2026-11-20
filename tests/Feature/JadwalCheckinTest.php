<?php

namespace Tests\Feature;

use App\Livewire\Checkin;
use App\Livewire\JadwalBerulang;
use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\Pesanan;
use App\Models\Rute;
use App\Models\Tiket;
use App\Models\User;
use App\Services\BookingService;
use App\Services\CheckinService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JadwalCheckinTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Kapal $kapal;
    private Pelabuhan $asal;
    private Pelabuhan $tujuan;

    protected function setUp(): void
    {
        parent::setUp();
        // Waktu tetap supaya hasil hitungan hari konsisten: Senin, 23 Nov 2026 pukul 05:00
        Carbon::setTestNow(Carbon::parse('2026-11-23 05:00:00'));

        $this->admin = User::factory()->create();
        $this->admin->forceFill(['role' => 'admin', 'email_verified_at' => now()])->save();
        $this->actingAs($this->admin);

        $this->kapal = Kapal::create(['Nama_Kapal' => 'KM Uji', 'Kelas' => 'VIP', 'Jumlah_Kursi' => 100]);
        $this->asal = Pelabuhan::create(['Nama_Pelabuhan' => 'Merak', 'Nama_Kota' => 'Cilegon']);
        $this->tujuan = Pelabuhan::create(['Nama_Pelabuhan' => 'Bakauheni', 'Nama_Kota' => 'Lampung']);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function isiForm()
    {
        return Livewire::test(JadwalBerulang::class)
            ->set('kapal', $this->kapal->ID_Kapal)
            ->set('asal', $this->asal->ID_Pelabuhan)
            ->set('tujuan', $this->tujuan->ID_Pelabuhan)
            ->set('mulai', '2026-11-23')
            ->set('selesai', '2026-11-29')            // Senin s/d Minggu
            ->set('hari', [1, 3, 5])                  // Sen, Rab, Jum
            ->set('jam', ['04:00', '08:00', '20:00'])  // 04:00 hari ini sudah lewat
            ->set('tarif.VIP.dewasa', ['harga' => '150000', 'kuota' => '20'])
            ->set('tarif.VIP.anak', ['harga' => '100000', 'kuota' => '10'])
            ->set('tarif.Ekonomi.aktif', false);
    }

    // ---------- Jadwal berulang ----------

    public function test_generator_membuat_jadwal_sesuai_hari_dan_jam(): void
    {
        $this->isiForm()->assertViewHas('jumlahRencana', 8)->call('simpan')->assertHasNoErrors();

        // Sen 23 (08:00, 20:00; 04:00 sudah lewat) + Rab 25 + Jum 27 (masing-masing 3 jam) = 2 + 3 + 3
        $this->assertSame(8, Rute::count());
        $this->assertSame(['2026-11-23', '2026-11-25', '2026-11-27'],
            Rute::orderBy('Tanggal')->pluck('Tanggal')->map(fn ($t) => Carbon::parse($t)->toDateString())->unique()->values()->all());

        // Hanya kelas VIP (2 tipe) per jadwal, dan langsung bisa dipesan
        $this->assertSame(16, Tiket::count());
        $rute = Rute::with('tiket.harga')->first();
        $offers = app(BookingService::class)->offersFor($rute);
        $this->assertSame(['harga' => 150000, 'sisa' => 20], $offers['VIP']['dewasa']);
        $this->assertArrayNotHasKey('Ekonomi', $offers);
    }

    public function test_generator_tidak_menggandakan_jadwal_yang_sudah_ada(): void
    {
        $this->isiForm()->call('simpan');
        $this->isiForm()->call('simpan')->assertSet('hasil', ['dibuat' => 0, 'dilewati' => 8]);

        $this->assertSame(8, Rute::count());
    }

    public function test_generator_memvalidasi_input(): void
    {
        $this->isiForm()
            ->set('tujuan', $this->asal->ID_Pelabuhan)
            ->set('hari', [])
            ->set('tarif.VIP.dewasa.harga', '')
            ->call('simpan')
            ->assertHasErrors(['tujuan' => 'different', 'hari', 'tarif.VIP.dewasa.harga']);

        $this->assertSame(0, Rute::count());
    }

    // ---------- Check-in ----------

    private function pesananLunas(string $tanggal, string $jam = '08:00:00'): Pesanan
    {
        $rute = Rute::create([
            'ID_Kapal' => $this->kapal->ID_Kapal, 'Pelabuhan_Asal' => $this->asal->ID_Pelabuhan,
            'Pelabuhan_Tujuan' => $this->tujuan->ID_Pelabuhan, 'Tanggal' => $tanggal, 'Jam' => $jam,
        ]);
        $harga = \App\Models\Harga::create(['ID_Rute' => $rute->ID_Rute, 'Tipe_Penumpang' => 'dewasa', 'Kelas' => 'VIP', 'Harga' => 100000]);
        Tiket::create(['ID_Rute' => $rute->ID_Rute, 'ID_Harga' => $harga->ID_Harga, 'Jumlah_Tiket' => 10]);

        $pembeli = User::factory()->create();
        $p = app(BookingService::class)->book($pembeli, $rute->ID_Rute, 'VIP', 2, 0, [
            ['Nama_Lengkap' => 'Andi', 'Telepon' => '081234567890', 'Alamat' => 'Jl. A', 'Jenis_Kelamin' => 'laki-laki'],
            ['Nama_Lengkap' => 'Bunga', 'Telepon' => '081234567890', 'Alamat' => 'Jl. A', 'Jenis_Kelamin' => 'perempuan'],
        ]);
        $p->forceFill(['Status' => Pesanan::STATUS_LUNAS, 'Dibayar_Pada' => now()])->save();

        return $p;
    }

    public function test_qr_hanya_muncul_di_invoice_yang_lunas(): void
    {
        $p = $this->pesananLunas('2026-11-23');
        $this->actingAs($p->user)->get(route('invoices.show', $p->ID_Pesanan))
            ->assertOk()->assertSee('data:image/png;base64', false)->assertSee('Boarding pass');

        $p->forceFill(['Status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN])->save();
        $this->actingAs($p->user)->get(route('invoices.show', $p->ID_Pesanan))
            ->assertOk()->assertDontSee('Boarding pass');
    }

    public function test_scan_url_qr_lalu_checkin_semua_penumpang(): void
    {
        $p = $this->pesananLunas('2026-11-23');
        $urlQr = app(\App\Services\TiketQr::class)->isi($p);

        Livewire::test(Checkin::class)
            ->call('cari', $urlQr)
            ->assertSet('kode', $p->Kode_Booking)
            ->assertSee('TIKET VALID')
            ->call('checkinSemua')
            ->assertSee('2 penumpang berhasil check-in')
            ->assertSee('SEMUA SUDAH NAIK');

        $this->assertSame(0, $p->penumpangs()->whereNull('Checkin_Pada')->count());

        // Scan ulang tidak mencatat ulang
        Livewire::test(Checkin::class, ['kode' => $p->Kode_Booking])
            ->call('checkinSemua')->assertSee('sudah check-in sebelumnya');
    }

    public function test_checkin_ditolak_jika_bukan_hari_keberangkatan_atau_belum_lunas(): void
    {
        $besok = $this->pesananLunas('2026-11-24');
        $this->assertStringContainsString('hari keberangkatan', app(CheckinService::class)->alasanDitolak($besok));

        Livewire::test(Checkin::class, ['kode' => $besok->Kode_Booking])
            ->assertSee('TIDAK BISA CHECK-IN')
            ->call('checkinSemua');
        $this->assertSame(2, $besok->penumpangs()->whereNull('Checkin_Pada')->count());

        $belumBayar = $this->pesananLunas('2026-11-23');
        $belumBayar->forceFill(['Status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN])->save();
        $this->assertStringContainsString('belum dibayar', app(CheckinService::class)->alasanDitolak($belumBayar));
    }

    public function test_checkin_ditutup_tiga_jam_setelah_berangkat(): void
    {
        $p = $this->pesananLunas('2026-11-23', '08:00:00');

        Carbon::setTestNow(Carbon::parse('2026-11-23 10:59:00'));
        $this->assertNull(app(CheckinService::class)->alasanDitolak($p), 'Masih boleh sampai 3 jam setelah berangkat');

        Carbon::setTestNow(Carbon::parse('2026-11-23 11:01:00'));
        $this->assertStringContainsString('ditutup', app(CheckinService::class)->alasanDitolak($p));
    }

    public function test_pengguna_biasa_tidak_bisa_membuka_halaman_checkin_dan_generator(): void
    {
        $pengguna = User::factory()->create();
        $pengguna->forceFill(['role' => 'pengguna', 'email_verified_at' => now()])->save();

        // CekRole mengalihkan non-admin keluar dari area admin
        $this->actingAs($pengguna)->get('/admin/checkin')->assertRedirect();
        $this->actingAs($pengguna)->get('/admin/jadwal-berulang')->assertRedirect();
    }
}
