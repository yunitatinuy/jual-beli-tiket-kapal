<?php

namespace Tests\Feature;

use App\Models\Pesanan;
use App\Models\Rute;
use App\Models\User;
use App\Services\BookingService;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Batas jumlah query per halaman, supaya masalah N+1 tidak muncul lagi diam-diam.
 * Data: 84 jadwal demo + beberapa pesanan dengan banyak penumpang.
 */
class PerformaHalamanTest extends TestCase
{
    use RefreshDatabase;

    private User $pengguna;
    private User $admin;
    private Pesanan $pesanan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoDataSeeder::class);

        $this->pengguna = User::factory()->create();
        $this->pengguna->forceFill(['role' => 'pengguna', 'email_verified_at' => now()])->save();
        $this->admin = User::factory()->create();
        $this->admin->forceFill(['role' => 'admin', 'email_verified_at' => now()])->save();

        $booking = app(BookingService::class);
        foreach (Rute::orderBy('Tanggal')->limit(6)->get() as $rute) {
            $penumpang = array_fill(0, 4, ['Nama_Lengkap' => 'Uji', 'Telepon' => '081234567890', 'Alamat' => 'Jl. A', 'Jenis_Kelamin' => 'laki-laki']);
            $this->pesanan = $booking->book($this->pengguna, $rute->ID_Rute, 'Ekonomi', 2, 2, $penumpang);
        }
    }

    private function hitungQuery(callable $aksi): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $aksi();
        $jumlah = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $jumlah;
    }

    public static function halaman(): array
    {
        return [
            // [url, role, maks query]
            'landing' => ['/', null, 6],
            'dashboard pengguna' => ['/dashboard_pengguna', 'pengguna', 8],
            'informasi' => ['/informasi', 'pengguna', 8],
            'transaksi' => ['/transaksi', 'pengguna', 10],
            'cari jadwal' => ['/search?routeFrom=Merak&routeTo=Bakauheni&tanggal_keberangkatan=TANGGAL&dewasa=1&anak=0', 'pengguna', 10],
            'invoice' => ['/invoices/PESANAN', 'pengguna', 14],
            'admin dashboard' => ['/admin/dashboard', 'admin', 14],
            'admin rute' => ['/admin/rute', 'admin', 12],
            'admin harga' => ['/admin/harga', 'admin', 12],
            'admin tiket' => ['/admin/tiket', 'admin', 12],
            'admin pesanan' => ['/admin/pesanan', 'admin', 12],
            'admin pengguna' => ['/admin/pengguna', 'admin', 8],
            'admin jadwal berulang' => ['/admin/jadwal-berulang', 'admin', 8],
            'admin checkin' => ['/admin/checkin', 'admin', 8],
        ];
    }

    /** @dataProvider halaman */
    public function test_jumlah_query_halaman_tetap_rendah(string $url, ?string $role, int $maks): void
    {
        $url = str_replace(
            ['TANGGAL', 'PESANAN'],
            [now()->addDay()->toDateString(), (string) $this->pesanan->ID_Pesanan],
            $url
        );
        if ($role) {
            $this->actingAs($role === 'admin' ? $this->admin : $this->pengguna);
        }

        $jumlah = $this->hitungQuery(fn () => $this->get($url)->assertOk());

        fwrite(STDERR, sprintf("  %-110s %3d query\n", $url, $jumlah));
        $this->assertLessThanOrEqual($maks, $jumlah, "{$url} menjalankan {$jumlah} query (maks {$maks})");
    }
}
