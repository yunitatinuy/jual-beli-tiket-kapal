<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email verifikasi yang dikirim lewat queue, supaya halaman register tidak
 * menunggu (atau gagal) karena koneksi SMTP yang lambat.
 */
class VerifikasiEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 60];

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - ' . config('app.name'))
            ->greeting('Halo!')
            ->line('Klik tombol di bawah untuk memverifikasi alamat email Anda.')
            ->action('Verifikasi Email', $url)
            ->line('Link ini berlaku selama ' . config('auth.verification.expire', 60) . ' menit.')
            ->line('Jika Anda tidak merasa mendaftar, abaikan email ini.')
            ->salutation('Salam, ' . config('app.name'));
    }
}
