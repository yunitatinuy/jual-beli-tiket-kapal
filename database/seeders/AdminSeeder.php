<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Membuat (atau memperbarui) akun admin dari ADMIN_NAME / ADMIN_EMAIL / ADMIN_PASSWORD di .env.
 * Jika ADMIN_PASSWORD kosong, password acak dibuat dan ditampilkan sekali di terminal.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');

        if (! $email) {
            $this->command?->warn('ADMIN_EMAIL belum diisi di .env, akun admin tidak dibuat.');

            return;
        }

        $password = env('ADMIN_PASSWORD') ?: Str::password(16);

        $user = User::firstOrNew(['email' => $email]);
        $baru = ! $user->exists;

        $user->name = env('ADMIN_NAME', 'Administrator');
        if ($baru || env('ADMIN_PASSWORD')) {
            $user->password = $password;
        }
        $user->forceFill([
            'role' => 'admin',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->command?->info(($baru ? 'Admin dibuat: ' : 'Admin diperbarui: ') . $email);
        if ($baru && ! env('ADMIN_PASSWORD')) {
            $this->command?->warn('Password admin (simpan sekarang, tidak ditampilkan lagi): ' . $password);
        }
    }
}
