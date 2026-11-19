<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Konfirmasi ke pembeli setelah pembayaran Midtrans diterima. */
class TiketLunas extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 60];

    public function __construct(public Pesanan $pesanan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran diterima - Kode Booking ' . $this->pesanan->Kode_Booking,
        );
    }

    public function content(): Content
    {
        $this->pesanan->loadMissing(['rute.kapal', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'penumpangs', 'user']);

        return new Content(markdown: 'emails.tiket-lunas');
    }
}
