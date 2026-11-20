<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/** Kirim email uji untuk memastikan pengaturan SMTP di .env sudah benar. */
class TesEmail extends Command
{
    protected $signature = 'email:tes {tujuan : Alamat email penerima}';

    protected $description = 'Kirim email uji untuk mengecek pengaturan SMTP';

    public function handle(): int
    {
        $tujuan = $this->argument('tujuan');

        $this->line('Mailer : ' . config('mail.default'));
        $this->line('Host   : ' . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port'));
        $this->line('Akun   : ' . (config('mail.mailers.smtp.username') ?: '(kosong)'));
        $this->line('Dari   : ' . (config('mail.from.address') ?: '(kosong)'));

        if (config('mail.default') === 'smtp' && (! config('mail.mailers.smtp.username') || ! config('mail.mailers.smtp.password'))) {
            $this->error('MAIL_USERNAME / MAIL_PASSWORD di .env masih kosong.');

            return self::FAILURE;
        }

        try {
            Mail::raw(
                "Halo!\n\nIni email uji dari " . config('app.name') . ". Jika email ini sampai, pengaturan SMTP sudah benar.",
                fn ($m) => $m->to($tujuan)->subject('Tes SMTP - ' . config('app.name'))
            );
        } catch (\Throwable $e) {
            $this->error('Gagal mengirim: ' . $e->getMessage());

            if (str_contains($e->getMessage(), '535') || str_contains($e->getMessage(), 'Username and Password not accepted')) {
                $this->warn('Server SMTP menolak login. Periksa MAIL_USERNAME dan MAIL_PASSWORD di .env.');
                $this->warn('Mailtrap: salin dari Email Testing > Inboxes > (inbox) > SMTP. Gmail: pakai App Password 16 huruf, bukan password akun.');
            }

            return self::FAILURE;
        }

        $this->info("Email uji terkirim ke {$tujuan}. Cek inbox (dan folder spam).");

        return self::SUCCESS;
    }
}
