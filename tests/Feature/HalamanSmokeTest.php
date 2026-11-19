<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Semua halaman utama harus bisa dibuka tanpa error untuk tiap peran. */
class HalamanSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoDataSeeder::class);
    }

    private function user(string $role): User
    {
        $u = User::factory()->create();
        $u->forceFill(['role' => $role, 'email_verified_at' => now()])->save();

        return $u;
    }

    public static function halamanTamu(): array
    {
        return [['/'], ['/login'], ['/register']];
    }

    public static function halamanPengguna(): array
    {
        return [['/dashboard_pengguna'], ['/informasi'], ['/transaksi'], ['/sekalipergi'], ['/profil']];
    }

    public static function halamanAdmin(): array
    {
        return [['/admin/dashboard'], ['/admin/kapal'], ['/admin/pelabuhan'], ['/admin/tiket'],
            ['/admin/harga'], ['/admin/rute'], ['/admin/pengguna'], ['/admin/pesanan']];
    }

    /** @dataProvider halamanTamu */
    public function test_halaman_tamu(string $url): void
    {
        $this->get($url)->assertOk();
    }

    /** @dataProvider halamanPengguna */
    public function test_halaman_pengguna(string $url): void
    {
        $this->actingAs($this->user('pengguna'))->get($url)->assertOk();
    }

    /** @dataProvider halamanAdmin */
    public function test_halaman_admin(string $url): void
    {
        $this->actingAs($this->user('admin'))->get($url)->assertOk();
    }
}
