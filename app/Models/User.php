<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'id'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // Laravel 10 memakai properti $casts (method casts() baru ada di Laravel 11)
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Aturan password yang sama untuk register, ganti password di profil, dan reset password:
     * minimal 8 karakter, mengandung huruf dan angka, serta dikonfirmasi (password_confirmation).
     */
    public static function passwordRules(): array
    {
        return ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()];
    }

    public static function passwordMessages(): array
    {
        return [
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordEmail($token));
    }

    /** Kirim email verifikasi lewat queue (lihat App\Notifications\VerifikasiEmail). */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifikasiEmail);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function penumpang()
    {
        return $this->hasOne(Penumpang::class, 'id');
    }
}
