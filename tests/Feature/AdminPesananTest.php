<?php

namespace Tests\Feature;

use App\Livewire\AdminDashboard;
use App\Livewire\Pesanan as PesananComponent;
use App\Models\Pesanan;
use App\Models\Rute;
use App\Models\Tiket;
use App\Models\User;
use App\Services\BookingService;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPesananTest extends TestCase
{
    use RefreshDatabase;

    private User $pembeli;
    private Rute $rute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoDataSeeder::class);

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin', 'email_verified_at' => now()])->save();
        $this->actingAs($admin);

        $this->pembeli = User::factory()->create(['name' => 'Siti Pembeli']);
        $this->rute = Rute::orderBy('Tanggal')->orderBy('Jam')->first();
    }

    private function pesan(string $nama = 'Andi'): Pesanan
    {
        return app(BookingService::class)->book($this->pembeli, $this->rute->ID_Rute, 'Ekonomi', 1, 0, [[
            'Nama_Lengkap' => $nama, 'Telepon' => '081234567890', 'Alamat' => 'Jl. Uji', 'Jenis_Kelamin' => 'laki-laki',
        ]]);
    }

    public function test_dashboard_menghitung_pendapatan_dari_pesanan_lunas_saja(): void
    {
        $lunas = $this->pesan();
        $lunas->forceFill(['Status' => Pesanan::STATUS_LUNAS, 'Dibayar_Pada' => now()])->save();
        $this->pesan(); // masih menunggu pembayaran

        $ringkasan = Livewire::test(AdminDashboard::class)->viewData('ringkasan');

        $this->assertSame((int) $lunas->Total_Harga, $ringkasan['pendapatanBulanIni']);
        $this->assertSame(1, $ringkasan['menungguBayar']);
        $this->assertSame(2, $ringkasan['pesananHariIni']);
    }

    public function test_filter_status_dan_pencarian(): void
    {
        $a = $this->pesan('Andi');
        $b = $this->pesan('Budi');
        $b->forceFill(['Status' => Pesanan::STATUS_LUNAS, 'Dibayar_Pada' => now()])->save();

        $ids = fn ($c) => $c->viewData('dataPesanan')->pluck('ID_Pesanan')->all();

        $komponen = Livewire::test(PesananComponent::class);
        $this->assertEqualsCanonicalizing([$a->ID_Pesanan, $b->ID_Pesanan], $ids($komponen));

        $this->assertSame([$b->ID_Pesanan], $ids($komponen->set('status', Pesanan::STATUS_LUNAS)));
        $this->assertSame([$a->ID_Pesanan], $ids($komponen->set('status', '')->set('search', 'Andi')));
        $this->assertSame([$b->ID_Pesanan], $ids($komponen->set('search', $b->Kode_Booking)));
        $this->assertCount(2, $ids($komponen->set('search', 'Siti')));
    }

    public function test_detail_menampilkan_penumpang(): void
    {
        $p = $this->pesan('Citra Penumpang');

        Livewire::test(PesananComponent::class)
            ->call('lihat', $p->ID_Pesanan)
            ->assertSee('Citra Penumpang')
            ->assertSee($p->Kode_Booking);
    }

    public function test_admin_bisa_membatalkan_pesanan_menunggu_dan_kuota_kembali(): void
    {
        $p = $this->pesan();
        $tiket = Tiket::find($p->penumpangs()->first()->ID_Tiket);
        $kuotaSetelahPesan = $tiket->Jumlah_Tiket;

        Livewire::test(PesananComponent::class)->call('batalkan', $p->ID_Pesanan);

        $this->assertSame(Pesanan::STATUS_DIBATALKAN, $p->fresh()->Status);
        $this->assertSame($kuotaSetelahPesan + 1, $tiket->fresh()->Jumlah_Tiket);
    }

    public function test_api_register_tidak_lagi_tersedia(): void
    {
        $this->postJson('/api/register', ['name' => 'x', 'email' => 'x@example.test', 'password' => 'password123'])
            ->assertNotFound();
    }

    public function test_informasi_hanya_menampilkan_jadwal_mendatang(): void
    {
        $lewat = Rute::create([
            'ID_Kapal' => $this->rute->ID_Kapal, 'Pelabuhan_Asal' => $this->rute->Pelabuhan_Asal,
            'Pelabuhan_Tujuan' => $this->rute->Pelabuhan_Tujuan, 'Tanggal' => now()->subDays(3)->toDateString(), 'Jam' => '06:45:00',
        ]);

        $pengguna = User::factory()->create();
        $pengguna->forceFill(['role' => 'pengguna', 'email_verified_at' => now()])->save();

        $this->actingAs($pengguna)->get('/informasi')
            ->assertOk()
            ->assertDontSee(now()->subDays(3)->format('d-m-Y'))
            ->assertSee(\Carbon\Carbon::parse($this->rute->Tanggal)->format('d-m-Y'));
    }
}
