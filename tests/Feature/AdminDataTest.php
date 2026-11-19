<?php

namespace Tests\Feature;

use App\Livewire\Harga as HargaComponent;
use App\Livewire\Kapal as KapalComponent;
use App\Livewire\Pelabuhan as PelabuhanComponent;
use App\Livewire\Rute as RuteComponent;
use App\Livewire\Tiket as TiketComponent;
use App\Models\Harga;
use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\Rute;
use App\Models\Tiket;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** Form admin: validasi data dan perlindungan data yang sudah dipakai pesanan. */
class AdminDataTest extends TestCase
{
    use RefreshDatabase;

    private Kapal $kapal;
    private Pelabuhan $asal;
    private Pelabuhan $tujuan;
    private Rute $rute;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin', 'email_verified_at' => now()])->save();
        $this->actingAs($admin);

        $this->kapal = Kapal::create(['Nama_Kapal' => 'KM Uji', 'Kelas' => 'VIP', 'Jumlah_Kursi' => 100]);
        $this->asal = Pelabuhan::create(['Nama_Pelabuhan' => 'Merak', 'Nama_Kota' => 'Cilegon']);
        $this->tujuan = Pelabuhan::create(['Nama_Pelabuhan' => 'Bakauheni', 'Nama_Kota' => 'Lampung']);
        $this->rute = Rute::create([
            'ID_Kapal' => $this->kapal->ID_Kapal,
            'Pelabuhan_Asal' => $this->asal->ID_Pelabuhan,
            'Pelabuhan_Tujuan' => $this->tujuan->ID_Pelabuhan,
            'Tanggal' => now()->addDays(2)->toDateString(),
            'Jam' => '08:00:00',
        ]);
    }

    private function hargaDanTiket(string $tipe = 'dewasa', string $kelas = 'VIP', int $kuota = 10): Tiket
    {
        $harga = Harga::create(['ID_Rute' => $this->rute->ID_Rute, 'Tipe_Penumpang' => $tipe, 'Kelas' => $kelas, 'Harga' => 100000]);

        return Tiket::create(['ID_Rute' => $this->rute->ID_Rute, 'ID_Harga' => $harga->ID_Harga, 'Jumlah_Tiket' => $kuota]);
    }

    private function pesan(): void
    {
        $pembeli = User::factory()->create();
        app(BookingService::class)->book($pembeli, $this->rute->ID_Rute, 'VIP', 1, 0, [[
            'Nama_Lengkap' => 'Budi', 'Telepon' => '081234567890', 'Alamat' => 'Jl. Uji', 'Jenis_Kelamin' => 'laki-laki',
        ]]);
    }

    // ---------- Harga ----------

    public function test_harga_baru_tersimpan_dengan_rute(): void
    {
        Livewire::test(HargaComponent::class)
            ->set('ID_Rute', $this->rute->ID_Rute)
            ->set('Tipe_Penumpang', 'dewasa')
            ->set('Kelas', 'VIP')
            ->set('Harga', '125000')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('harga', ['ID_Rute' => $this->rute->ID_Rute, 'Kelas' => 'VIP', 'Harga' => 125000]);
    }

    public function test_harga_menolak_input_tidak_valid_dan_duplikat(): void
    {
        Livewire::test(HargaComponent::class)
            ->set('ID_Rute', '')
            ->set('Tipe_Penumpang', 'dewasa')
            ->set('Kelas', 'Bisnis')
            ->set('Harga', '-5')
            ->call('store')
            ->assertHasErrors(['ID_Rute', 'Kelas', 'Harga']);

        $this->hargaDanTiket('dewasa', 'VIP');

        Livewire::test(HargaComponent::class)
            ->set('ID_Rute', $this->rute->ID_Rute)
            ->set('Tipe_Penumpang', 'dewasa')
            ->set('Kelas', 'VIP')
            ->set('Harga', '90000')
            ->call('store')
            ->assertHasErrors(['Kelas' => 'unique']);
    }

    public function test_harga_yang_dipakai_tiket_tidak_bisa_dihapus(): void
    {
        $tiket = $this->hargaDanTiket();

        Livewire::test(HargaComponent::class)->call('delete', $tiket->ID_Harga);

        $this->assertDatabaseHas('harga', ['ID_Harga' => $tiket->ID_Harga]);
    }

    // ---------- Tiket ----------

    public function test_tiket_hanya_menawarkan_harga_milik_rute_terpilih(): void
    {
        $milikRute = Harga::create(['ID_Rute' => $this->rute->ID_Rute, 'Tipe_Penumpang' => 'anak', 'Kelas' => 'Ekonomi', 'Harga' => 40000]);
        $ruteLain = Rute::create([
            'ID_Kapal' => $this->kapal->ID_Kapal, 'Pelabuhan_Asal' => $this->tujuan->ID_Pelabuhan,
            'Pelabuhan_Tujuan' => $this->asal->ID_Pelabuhan, 'Tanggal' => now()->addDays(3)->toDateString(), 'Jam' => '09:00:00',
        ]);
        $milikLain = Harga::create(['ID_Rute' => $ruteLain->ID_Rute, 'Tipe_Penumpang' => 'anak', 'Kelas' => 'Ekonomi', 'Harga' => 45000]);

        $komponen = Livewire::test(TiketComponent::class)->set('ID_Rute', $this->rute->ID_Rute);
        $pilihan = $komponen->viewData('dataHarga')->pluck('ID_Harga')->all();
        $this->assertSame([$milikRute->ID_Harga], $pilihan);

        // Mengirim ID harga rute lain secara paksa tetap ditolak
        $komponen->set('ID_Harga', $milikLain->ID_Harga)->set('Jumlah_Tiket', 10)
            ->call('store')->assertHasErrors(['ID_Harga' => 'exists']);
        $this->assertDatabaseCount('tiket', 0);
    }

    public function test_tiket_ganda_dan_jumlah_negatif_ditolak(): void
    {
        $tiket = $this->hargaDanTiket();

        Livewire::test(TiketComponent::class)
            ->set('ID_Rute', $this->rute->ID_Rute)
            ->set('ID_Harga', $tiket->ID_Harga)
            ->set('Jumlah_Tiket', '-1')
            ->call('store')
            ->assertHasErrors(['ID_Harga' => 'unique', 'Jumlah_Tiket' => 'min']);
    }

    public function test_tiket_yang_sudah_dipesan_tidak_bisa_dihapus(): void
    {
        $tiket = $this->hargaDanTiket();
        $this->pesan();

        Livewire::test(TiketComponent::class)->call('delete', $tiket->ID_Tiket);

        $this->assertDatabaseHas('tiket', ['ID_Tiket' => $tiket->ID_Tiket]);
    }

    // ---------- Rute ----------

    public function test_rute_menolak_pelabuhan_sama_dan_tanggal_lewat(): void
    {
        Livewire::test(RuteComponent::class)
            ->set('selectedKapal', $this->kapal->ID_Kapal)
            ->set('selectedPelabuhanAsal', $this->asal->ID_Pelabuhan)
            ->set('selectedPelabuhanTujuan', $this->asal->ID_Pelabuhan)
            ->set('tanggal', now()->subDay()->toDateString())
            ->set('jam', '07:00')
            ->call('store')
            ->assertHasErrors(['selectedPelabuhanTujuan' => 'different', 'tanggal' => 'after_or_equal']);
    }

    public function test_rute_bisa_dibuat_di_luar_jam_09_18(): void
    {
        Livewire::test(RuteComponent::class)
            ->set('selectedKapal', $this->kapal->ID_Kapal)
            ->set('selectedPelabuhanAsal', $this->asal->ID_Pelabuhan)
            ->set('selectedPelabuhanTujuan', $this->tujuan->ID_Pelabuhan)
            ->set('tanggal', now()->addDays(5)->toDateString())
            ->set('jam', '20:30')
            ->call('store')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('rute', ['Jam' => '20:30:00']);
    }

    public function test_rute_yang_sudah_dipesan_tidak_bisa_dihapus_atau_ganti_lintasan(): void
    {
        $this->hargaDanTiket();
        $this->pesan();
        $lain = Pelabuhan::create(['Nama_Pelabuhan' => 'Ketapang', 'Nama_Kota' => 'Banyuwangi']);

        Livewire::test(RuteComponent::class)->call('delete', $this->rute->ID_Rute);
        $this->assertDatabaseHas('rute', ['ID_Rute' => $this->rute->ID_Rute]);

        Livewire::test(RuteComponent::class)
            ->call('edit', $this->rute->ID_Rute)
            ->set('selectedPelabuhanTujuan', $lain->ID_Pelabuhan)
            ->call('update')
            ->assertHasErrors('selectedPelabuhanAsal');
        $this->assertSame($this->tujuan->ID_Pelabuhan, $this->rute->fresh()->Pelabuhan_Tujuan);

        // Ganti jam (reschedule) tetap boleh
        Livewire::test(RuteComponent::class)
            ->call('edit', $this->rute->ID_Rute)
            ->set('jam', '10:15')
            ->call('update')
            ->assertHasNoErrors();
        $this->assertSame('10:15:00', $this->rute->fresh()->Jam);
    }

    public function test_rute_tanpa_pesanan_dihapus_beserta_harga_dan_tiketnya(): void
    {
        $tiket = $this->hargaDanTiket();

        Livewire::test(RuteComponent::class)->call('delete', $this->rute->ID_Rute);

        $this->assertDatabaseMissing('rute', ['ID_Rute' => $this->rute->ID_Rute]);
        $this->assertDatabaseMissing('tiket', ['ID_Tiket' => $tiket->ID_Tiket]);
        $this->assertDatabaseMissing('harga', ['ID_Harga' => $tiket->ID_Harga]);
    }

    // ---------- Kapal & Pelabuhan ----------

    public function test_kapal_dan_pelabuhan_yang_dipakai_rute_tidak_bisa_dihapus(): void
    {
        Livewire::test(KapalComponent::class)->call('delete', $this->kapal->ID_Kapal);
        Livewire::test(PelabuhanComponent::class)->call('delete', $this->asal->ID_Pelabuhan);

        $this->assertDatabaseHas('kapal', ['ID_Kapal' => $this->kapal->ID_Kapal]);
        $this->assertDatabaseHas('pelabuhan', ['ID_Pelabuhan' => $this->asal->ID_Pelabuhan]);
    }

    public function test_kapal_menolak_kursi_tidak_valid(): void
    {
        Livewire::test(KapalComponent::class)
            ->set('Nama_Kapal', 'KM Baru')
            ->set('Kelas', 'Ekonomi')
            ->set('Jumlah_Kursi', '0')
            ->call('store')
            ->assertHasErrors(['Jumlah_Kursi' => 'min']);
    }
}
