<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    private function pengguna(): User
    {
        $user = User::factory()->create(['password' => 'password123']);
        $user->forceFill(['role' => 'pengguna', 'email_verified_at' => now()])->save();

        return $user;
    }

    public function test_halaman_login_menampilkan_link_lupa_password(): void
    {
        $this->get('/login')->assertOk()->assertSee(route('password.request'));
        $this->get(route('password.request'))->assertOk();
    }

    public function test_link_reset_dikirim_ke_email_terdaftar(): void
    {
        Notification::fake();
        $user = $this->pengguna();

        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordEmail::class);
    }

    public function test_email_tidak_terdaftar_mendapat_pesan_yang_sama(): void
    {
        Notification::fake();

        $this->post(route('password.email'), ['email' => 'tidak-ada@example.test'])
            ->assertSessionHas('status')
            ->assertSessionHasNoErrors();

        Notification::assertNothingSent();
    }

    public function test_password_bisa_direset_dengan_token_valid(): void
    {
        $user = $this->pengguna();
        $token = Password::createToken($user);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))->assertOk();

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'PasswordBaru99',
            'password_confirmation' => 'PasswordBaru99',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('PasswordBaru99', $user->fresh()->password));

        // Token hanya bisa dipakai sekali
        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'PasswordLain77',
            'password_confirmation' => 'PasswordLain77',
        ])->assertSessionHasErrors('email');
    }

    public function test_reset_dengan_token_salah_ditolak(): void
    {
        $user = $this->pengguna();

        $this->post(route('password.update'), [
            'token' => 'token-palsu',
            'email' => $user->email,
            'password' => 'PasswordBaru99',
            'password_confirmation' => 'PasswordBaru99',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    public function test_ganti_password_di_profil_wajib_password_lama(): void
    {
        $user = $this->pengguna();
        $data = ['name' => $user->name, 'email' => $user->email, 'password' => 'PasswordBaru99', 'password_confirmation' => 'PasswordBaru99'];

        $this->actingAs($user)->post('/profil', $data)->assertSessionHasErrors('current_password');
        $this->actingAs($user)->post('/profil', $data + ['current_password' => 'salah123'])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));

        $this->actingAs($user)->post('/profil', $data + ['current_password' => 'password123'])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('PasswordBaru99', $user->fresh()->password));
    }

    public function test_ganti_nama_saja_tidak_perlu_password_lama(): void
    {
        $user = $this->pengguna();

        $this->actingAs($user)->post('/profil', ['name' => 'Nama Baru', 'email' => $user->email])->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $user->fresh()->name);
    }

    public function test_password_lemah_ditolak_saat_register_dan_profil(): void
    {
        $this->post('/register', [
            'name' => 'Budi', 'email' => 'budi@example.test', 'password' => 'abcdefgh', 'c_password' => 'abcdefgh',
        ])->assertSessionHasErrors('password');

        $user = $this->pengguna();
        $this->actingAs($user)->post('/profil', [
            'name' => $user->name, 'email' => $user->email,
            'password' => '12345678', 'password_confirmation' => '12345678', 'current_password' => 'password123',
        ])->assertSessionHasErrors('password');
    }
}
