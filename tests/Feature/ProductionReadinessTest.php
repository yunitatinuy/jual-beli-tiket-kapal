<?php

namespace Tests\Feature;

use App\Models\Rute;
use App\Models\Tiket;
use App\Models\User;
use App\Notifications\VerifikasiEmail;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_memiliki_header_keamanan(): void
    {
        $this->get('/login')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_email_verifikasi_dikirim_lewat_queue(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'rahasia-123',
            'c_password' => 'rahasia-123',
        ]);

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        Notification::assertSentTo($user, VerifikasiEmail::class);
        $this->assertInstanceOf(ShouldQueue::class, new VerifikasiEmail);
    }

    public function test_email_dengan_karakter_baris_baru_ditolak(): void
    {
        $this->post('/register', [
            'name' => 'Budi',
            'email' => "budi@example.com\r\nBcc: x@evil.test",
            'password' => 'rahasia-123',
            'c_password' => 'rahasia-123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_admin_seeder_membuat_admin_terverifikasi_dari_env(): void
    {
        putenv('ADMIN_EMAIL=admin@kapal.test');
        putenv('ADMIN_PASSWORD=Password-Kuat-123');

        try {
            $this->seed(AdminSeeder::class);
            $this->seed(AdminSeeder::class); // idempoten
        } finally {
            putenv('ADMIN_EMAIL');
            putenv('ADMIN_PASSWORD');
        }

        $admin = User::where('email', 'admin@kapal.test')->sole();
        $this->assertTrue($admin->isAdmin());
        $this->assertNotNull($admin->email_verified_at);
        $this->assertTrue(Hash::check('Password-Kuat-123', $admin->password));
    }

    public function test_demo_seeder_membuat_jadwal_yang_bisa_dicari(): void
    {
        $this->seed(DemoDataSeeder::class);
        $jumlah = Rute::count();
        $this->seed(DemoDataSeeder::class); // tidak menggandakan data

        $this->assertSame($jumlah, Rute::count());
        $this->assertGreaterThan(0, $jumlah);
        $this->assertSame($jumlah * 4, Tiket::count());
        $this->assertTrue(Rute::where('Tanggal', '>', now()->toDateString())->exists());
    }
}
