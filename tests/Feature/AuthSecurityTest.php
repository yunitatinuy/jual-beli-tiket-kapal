<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifikasiEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role = 'pengguna', bool $verified = true): User
    {
        $user = new User([
            'name' => 'Tes ' . $role,
            'email' => uniqid($role) . '@example.test',
            'password' => bcrypt('password123'),
        ]);
        $user->role = $role;
        $user->email_verified_at = $verified ? now() : null;
        $user->save();

        return $user;
    }

    public function test_register_tidak_bisa_menjadi_admin(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.test',
            'password' => 'password123',
            'c_password' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'penyusup@example.test')->firstOrFail();
        $this->assertSame('pengguna', $user->role);
    }

    public function test_register_mengirim_email_verifikasi(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.test',
            'password' => 'password123',
            'c_password' => 'password123',
        ]);

        $user = User::where('email', 'budi@example.test')->firstOrFail();
        Notification::assertSentTo($user, VerifikasiEmail::class);
    }

    public function test_register_menolak_email_yang_sudah_terdaftar(): void
    {
        $existing = $this->makeUser();

        $this->post('/register', [
            'name' => 'Duplikat',
            'email' => $existing->email,
            'password' => 'password123',
            'c_password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', $existing->email)->count());
    }

    public function test_login_pengguna_diarahkan_ke_dashboard_pengguna(): void
    {
        $user = $this->makeUser('pengguna');

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect('/dashboard_pengguna');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_admin_diarahkan_ke_dashboard_admin(): void
    {
        $admin = $this->makeUser('admin');

        $this->post('/login', ['email' => $admin->email, 'password' => 'password123'])
            ->assertRedirect('/admin/dashboard');
    }

    public function test_login_dikunci_setelah_terlalu_banyak_percobaan_gagal(): void
    {
        $user = $this->makeUser();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'salah']);
        }

        // Password benar pun ditolak selama masih terkunci
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertSessionHas('loginError', fn ($msg) => str_contains($msg, 'Terlalu banyak'));

        $this->assertGuest();
    }

    public function test_pengguna_tidak_bisa_membuka_halaman_admin(): void
    {
        $this->actingAs($this->makeUser('pengguna'))
            ->get('/admin/kapal')
            ->assertRedirect('/');
    }

    public function test_user_belum_verifikasi_diarahkan_ke_halaman_verifikasi(): void
    {
        $this->actingAs($this->makeUser('pengguna', verified: false))
            ->get('/dashboard_pengguna')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_invoice_milik_orang_lain_tidak_bisa_dibuka(): void
    {
        $pemilik = $this->makeUser();
        $orangLain = $this->makeUser();

        $id = DB::table('pesanan')->insertGetId([
            'ID_User' => $pemilik->id,
            'Tanggal_Pesanan' => now()->toDateString(),
            'Waktu' => now()->format('H:i:s'),
            'Total_Harga' => 100000,
            'Metode_Bayar' => 'BNI',
        ], 'ID_Pesanan');

        $this->actingAs($orangLain)->get("/invoices/{$id}")->assertNotFound();
        $this->actingAs($orangLain)->get("/invoice/pdf/{$id}")->assertNotFound();
    }

    public function test_ganti_email_di_profil_mereset_verifikasi(): void
    {
        Notification::fake();
        $user = $this->makeUser();

        $this->actingAs($user)->post('/profil', [
            'name' => $user->name,
            'email' => 'email-baru@example.test',
            'current_password' => 'password123',
        ])->assertRedirect(route('verification.notice'));

        $user->refresh();
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifikasiEmail::class);
    }
}
