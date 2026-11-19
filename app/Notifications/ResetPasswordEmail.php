<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/** Email link reset password (Bahasa Indonesia, lewat queue). */
class ResetPasswordEmail extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Atur Ulang Password - ' . config('app.name'))
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mengatur ulang password akun Anda.')
            ->action('Atur Ulang Password', $url)
            ->line('Link ini berlaku selama ' . config('auth.passwords.users.expire') . ' menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak berubah.')
            ->salutation('Salam, ' . config('app.name'));
    }
}
