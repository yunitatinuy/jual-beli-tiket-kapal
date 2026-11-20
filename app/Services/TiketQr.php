<?php

namespace App\Services;

use App\Models\Pesanan;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * QR code e-tiket. Isinya URL halaman check-in admin untuk kode booking ini,
 * jadi bisa dipindai dengan scanner di halaman check-in maupun kamera HP biasa
 * (petugas tetap harus login sebagai admin).
 */
class TiketQr
{
    public function isi(Pesanan $pesanan): string
    {
        return route('admin.checkin', ['kode' => $pesanan->Kode_Booking]);
    }

    /** Gambar PNG dalam bentuk data URI, bisa dipakai di <img> web maupun PDF. */
    public function dataUri(Pesanan $pesanan, int $skala = 6): string
    {
        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::M,
            'scale' => $skala,
            'quietzoneSize' => 2,
            'outputBase64' => true,
        ]);

        return (new QRCode($options))->render($this->isi($pesanan));
    }
}
