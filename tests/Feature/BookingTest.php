<?php

namespace Tests\Feature;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private int $ruteId;
    private int $tiketDewasa;
    private int $tiketAnak;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->makeUser();

        $kapal = DB::table('kapal')->insertGetId(['Nama_Kapal' => 'KM Uji', 'Kelas' => 'VIP', 'Jumlah_Kursi' => '100'], 'ID_Kapal');
        $asal = DB::table('pelabuhan')->insertGetId(['Nama_Pelabuhan' => 'Batam Centre', 'Nama_Kota' => 'Batam'], 'ID_Pelabuhan');
        $tujuan = DB::table('pelabuhan')->insertGetId(['Nama_Pelabuhan' => 'Tanjung Pinang', 'Nama_Kota' => 'Tanjung Pinang'], 'ID_Pelabuhan');

        $this->ruteId = $this->makeRute($kapal, $asal, $tujuan, now()->addDays(3));
        $this->tiketDewasa = $this->makeTiket($this->ruteId, 'dewasa', 'VIP', 150000, 5);
        $this->tiketAnak = $this->makeTiket($this->ruteId, 'anak', 'VIP', 100000, 5);
    }

    private function makeUser(): User
    {
        $user = new User(['name' => 'Pembeli', 'email' => uniqid('u') . '@example.test', 'password' => bcrypt('password123')]);
        $user->role = 'pengguna';
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    private function makeRute(int $kapal, int $asal, int $tujuan, \Carbon\Carbon $waktu): int
    {
        return DB::table('rute')->insertGetId([
            'ID_Kapal' => $kapal,
            'Pelabuhan_Asal' => $asal,
            'Pelabuhan_Tujuan' => $tujuan,
            'Tanggal' => $waktu->toDateString(),
            'Jam' => $waktu->format('H:i:s'),
        ], 'ID_Rute');
    }

    private function makeTiket(int $rute, string $tipe, string $kelas, int $harga, int $kuota): int
    {
        $hargaId = DB::table('harga')->insertGetId([
            'ID_Rute' => $rute, 'Tipe_Penumpang' => $tipe, 'Kelas' => $kelas, 'Harga' => $harga,
        ], 'ID_Harga');

        return DB::table('tiket')->insertGetId([
            'ID_Rute' => $rute, 'ID_Harga' => $hargaId, 'Jumlah_Tiket' => $kuota,
        ], 'ID_Tiket');
    }

    private function penumpang(int $n): array
    {
        return collect(range(1, $n))->map(fn ($i) => [
            'Nama_Lengkap' => "Penumpang {$i}",
            'Telepon' => '08123456789' . $i,
            'Alamat' => 'Jl. Uji No. ' . $i,
            'Jenis_Kelamin' => 'laki-laki',
        ])->all();
    }

    private function pesan(array $override = [])
    {
        $data = array_merge([
            'rute' => $this->ruteId,
            'kelas' => 'VIP',
            'dewasa' => 2,
            'anak' => 1,
            'penumpang' => $this->penumpang(3),
        ], $override);

        return $this->actingAs($this->user)->post('/pesantiket', $data);
    }

    private function kuota(int $tiketId): int
    {
        return (int) DB::table('tiket')->where('ID_Tiket', $tiketId)->value('Jumlah_Tiket');
    }

    public function test_total_harga_dihitung_dari_database_bukan_dari_form(): void
    {
        $this->pesan([
            // Harga palsu dari browser harus diabaikan
            'harga_vip_dewasa' => 1,
            'harga_vip_anak' => 1,
            'Total_Harga' => 1,
        ])->assertRedirect();

        $pesanan = Pesanan::firstOrFail();
        $this->assertSame(2 * 150000 + 100000, $pesanan->Total_Harga);
        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $pesanan->Status);
        $this->assertSame($this->ruteId, (int) $pesanan->ID_Rute);
        $this->assertNotEmpty($pesanan->Kode_Booking);
    }

    public function test_kuota_tiket_berkurang_sesuai_jumlah_penumpang(): void
    {
        $this->pesan()->assertRedirect();

        $this->assertSame(3, $this->kuota($this->tiketDewasa));
        $this->assertSame(4, $this->kuota($this->tiketAnak));
    }

    public function test_pesanan_ditolak_jika_kuota_tidak_cukup(): void
    {
        DB::table('tiket')->where('ID_Tiket', $this->tiketDewasa)->update(['Jumlah_Tiket' => 1]);

        $this->pesan()->assertSessionHas('error');

        $this->assertSame(0, Pesanan::count());
        $this->assertSame(1, $this->kuota($this->tiketDewasa));
        $this->assertSame(5, $this->kuota($this->tiketAnak), 'Kuota tipe lain tidak boleh ikut berkurang');
    }

    public function test_jadwal_yang_sudah_berangkat_tidak_bisa_dipesan(): void
    {
        DB::table('rute')->where('ID_Rute', $this->ruteId)->update([
            'Tanggal' => now()->subDay()->toDateString(),
        ]);

        $this->pesan()->assertSessionHas('error');
        $this->assertSame(0, Pesanan::count());
    }

    public function test_jumlah_data_penumpang_harus_sesuai_jumlah_tiket(): void
    {
        $this->pesan(['penumpang' => $this->penumpang(2)])->assertSessionHasErrors('penumpang');
        $this->assertSame(0, Pesanan::count());
    }

    public function test_nomor_telepon_harus_valid(): void
    {
        $penumpang = $this->penumpang(3);
        $penumpang[0]['Telepon'] = '12345';

        $this->pesan(['penumpang' => $penumpang])->assertSessionHasErrors('penumpang.0.Telepon');
    }

    public function test_penumpang_tersimpan_per_pesanan_dengan_tipe_dan_harga(): void
    {
        $this->pesan()->assertRedirect();
        $pesanan = Pesanan::with('penumpangs')->firstOrFail();

        $this->assertCount(3, $pesanan->penumpangs);
        $this->assertSame(['dewasa', 'dewasa', 'anak'], $pesanan->penumpangs->pluck('Tipe_Penumpang')->all());
        $this->assertSame([150000, 150000, 100000], $pesanan->penumpangs->pluck('Harga')->all());
    }

    public function test_invoice_menampilkan_penumpang_dari_pesanan_itu_sendiri(): void
    {
        $this->pesan(['dewasa' => 1, 'anak' => 0, 'penumpang' => [
            ['Nama_Lengkap' => 'Andi Pesanan Pertama', 'Telepon' => '081234567890', 'Alamat' => 'A', 'Jenis_Kelamin' => 'laki-laki'],
        ]]);
        $this->pesan(['dewasa' => 1, 'anak' => 0, 'penumpang' => [
            ['Nama_Lengkap' => 'Budi Pesanan Kedua', 'Telepon' => '081234567891', 'Alamat' => 'B', 'Jenis_Kelamin' => 'laki-laki'],
        ]]);

        $pertama = Pesanan::orderBy('ID_Pesanan')->firstOrFail();

        $this->actingAs($this->user)->get("/invoices/{$pertama->ID_Pesanan}")
            ->assertOk()
            ->assertSee('Andi Pesanan Pertama')
            ->assertDontSee('Budi Pesanan Kedua')
            ->assertSee('Rp150.000')
            ->assertSee('Batam Centre');
    }

    public function test_invoice_pdf_bisa_diunduh(): void
    {
        $this->pesan()->assertRedirect();
        $pesanan = Pesanan::firstOrFail();

        $response = $this->actingAs($this->user)->get("/invoice/pdf/{$pesanan->ID_Pesanan}");

        $response->assertOk();
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_halaman_form_menampilkan_harga_dari_database(): void
    {
        $this->actingAs($this->user)
            ->get("/pesantiket?rute={$this->ruteId}&kelas=VIP&dewasa=2&anak=1")
            ->assertOk()
            ->assertSee('Rp400.000')
            ->assertSee('Penumpang 3');
    }

    public function test_pencarian_tidak_menampilkan_jadwal_yang_sudah_lewat(): void
    {
        $kemarin = now()->subDay();
        $rutelama = $this->makeRute(
            DB::table('kapal')->value('ID_Kapal'),
            DB::table('pelabuhan')->min('ID_Pelabuhan'),
            DB::table('pelabuhan')->max('ID_Pelabuhan'),
            $kemarin
        );
        $this->makeTiket($rutelama, 'dewasa', 'VIP', 150000, 5);

        $this->actingAs($this->user)
            ->get('/search?routeFrom=Batam&routeTo=Pinang&tanggal_keberangkatan=' . $kemarin->toDateString() . '&dewasa=1&anak=0')
            ->assertOk()
            ->assertSee('tidak dapat ditemukan');

        $this->actingAs($this->user)
            ->get('/search?routeFrom=Batam&routeTo=Pinang&tanggal_keberangkatan=' . now()->addDays(3)->toDateString() . '&dewasa=1&anak=1')
            ->assertOk()
            ->assertSee('KM Uji')
            ->assertSee('Rp250.000');
    }
}
