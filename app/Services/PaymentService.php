<?php

namespace App\Services;

use App\Mail\TiketLunas;
use App\Models\Pesanan;
use App\Services\Payment\MidtransGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Pembayaran lewat Midtrans Snap.
 *
 * Alur:
 * 1. Pembeli klik "Bayar" -> snapToken() membuat transaksi Snap (order_id = Kode_Booking)
 * 2. Midtrans mengirim notifikasi (webhook) -> handleNotification()
 *    Status juga bisa disinkronkan manual lewat syncStatus() (berguna di localhost,
 *    karena webhook Midtrans tidak bisa menjangkau komputer lokal).
 * 3. Status Midtrans diterjemahkan oleh applyStatus(): lunas / tetap menunggu / dibatalkan.
 */
class PaymentService
{
    public function __construct(
        private MidtransGateway $gateway,
        private BookingService $booking,
    ) {
    }

    public function gateway(): MidtransGateway
    {
        return $this->gateway;
    }

    public function snapToken(Pesanan $pesanan): string
    {
        if ($pesanan->Status !== Pesanan::STATUS_MENUNGGU_PEMBAYARAN) {
            throw new BookingException('Pesanan ini tidak menunggu pembayaran.');
        }
        if ($pesanan->sudahKedaluwarsa()) {
            $this->booking->cancel($pesanan, 'Batas waktu pembayaran habis');
            throw new BookingException('Batas waktu pembayaran sudah habis. Pesanan dibatalkan.');
        }

        // Token Snap berlaku untuk satu order_id; pakai ulang agar tidak membuat transaksi ganda
        if ($pesanan->Snap_Token) {
            return $pesanan->Snap_Token;
        }

        $pesanan->loadMissing(['user', 'penumpangs', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan']);

        $token = $this->gateway->createSnapToken($this->snapParams($pesanan));
        $pesanan->forceFill(['Snap_Token' => $token])->save();

        return $token;
    }

    /**
     * Proses notifikasi HTTP dari Midtrans.
     * Signature diverifikasi, lalu status diambil ulang dari API Midtrans
     * supaya data palsu yang dikirim ke endpoint ini tidak bisa menandai pesanan lunas.
     *
     * @return bool false jika notifikasi ditolak
     */
    public function handleNotification(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->gateway->serverKey());
        if ($orderId === '' || ! hash_equals($expected, $signature)) {
            Log::warning('Midtrans: signature notifikasi tidak valid', ['order_id' => $orderId]);

            return false;
        }

        $pesanan = Pesanan::where('Kode_Booking', $orderId)->first();
        if (! $pesanan) {
            Log::warning('Midtrans: order_id tidak dikenal', ['order_id' => $orderId]);

            return true; // dianggap selesai agar Midtrans tidak mengirim ulang terus
        }

        $this->applyStatus($pesanan, $this->gateway->status($orderId));

        return true;
    }

    /** Ambil status terbaru dari Midtrans untuk pesanan yang masih menunggu. */
    public function syncStatus(Pesanan $pesanan): Pesanan
    {
        if ($pesanan->Status !== Pesanan::STATUS_MENUNGGU_PEMBAYARAN || ! $pesanan->Snap_Token) {
            return $pesanan;
        }

        try {
            $status = $this->gateway->status($pesanan->Kode_Booking);
        } catch (\Throwable $e) {
            // 404 = pembeli belum memilih metode bayar di Snap; bukan error
            return $pesanan;
        }

        $this->applyStatus($pesanan, $status);

        return $pesanan->refresh();
    }

    /** Terjemahkan status transaksi Midtrans ke status pesanan. */
    public function applyStatus(Pesanan $pesanan, array $status): void
    {
        $transactionStatus = $status['transaction_status'] ?? null;
        $fraudStatus = $status['fraud_status'] ?? null;

        if ((string) ($status['order_id'] ?? '') !== $pesanan->Kode_Booking) {
            Log::warning('Midtrans: order_id status tidak cocok', ['pesanan' => $pesanan->ID_Pesanan]);

            return;
        }

        $lunas = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        if ($lunas) {
            if ((int) round((float) ($status['gross_amount'] ?? 0)) !== (int) $pesanan->Total_Harga) {
                Log::error('Midtrans: nominal pembayaran tidak sama dengan total pesanan', [
                    'pesanan' => $pesanan->ID_Pesanan,
                    'gross_amount' => $status['gross_amount'] ?? null,
                ]);

                return;
            }
            $this->markPaid($pesanan, $status);

            return;
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $this->booking->cancel($pesanan, "Pembayaran {$transactionStatus} di Midtrans");
        }

        // pending / capture-challenge: tetap menunggu
    }

    private function markPaid(Pesanan $pesanan, array $status): void
    {
        $baruLunas = DB::transaction(function () use ($pesanan, $status) {
            $locked = Pesanan::whereKey($pesanan->getKey())->lockForUpdate()->first();

            if ($locked->Status === Pesanan::STATUS_LUNAS) {
                return null; // notifikasi dikirim ulang; sudah diproses
            }

            if ($locked->Status === Pesanan::STATUS_DIBATALKAN) {
                // Pembayaran masuk setelah pesanan dibatalkan (kursi sudah dilepas).
                // Perlu ditangani admin (refund atau pesan ulang manual).
                Log::critical('Midtrans: pembayaran diterima untuk pesanan yang sudah dibatalkan', [
                    'pesanan' => $locked->ID_Pesanan,
                    'kode' => $locked->Kode_Booking,
                ]);

                return null;
            }

            $paymentType = (string) ($status['payment_type'] ?? '');
            $locked->forceFill([
                'Status' => Pesanan::STATUS_LUNAS,
                'Dibayar_Pada' => now(),
                'Midtrans_Transaction_Id' => $status['transaction_id'] ?? null,
                'Midtrans_Payment_Type' => $paymentType ?: null,
                'Metode_Bayar' => $this->labelMetode($status),
            ])->save();

            return $locked;
        });

        // Dikirim setelah commit dan hanya sekali (notifikasi ulang tidak mengirim email lagi)
        if ($baruLunas && $baruLunas->user?->email) {
            try {
                Mail::to($baruLunas->user->email)->queue(new TiketLunas($baruLunas));
            } catch (\Throwable $e) {
                // Gagal kirim email tidak boleh membatalkan status lunas
                Log::error('Gagal mengantrekan email tiket lunas', ['pesanan' => $baruLunas->ID_Pesanan, 'error' => $e->getMessage()]);
            }
        }
    }

    private function snapParams(Pesanan $pesanan): array
    {
        $rute = $pesanan->rute;
        $namaRute = Str::limit(
            ($rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '') . '-' . ($rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? ''),
            25,
            ''
        );

        $items = $pesanan->penumpangs
            ->groupBy(fn ($p) => $p->ID_Tiket . '|' . $p->Tipe_Penumpang)
            ->map(fn ($group) => [
                'id' => 'TKT-' . $group->first()->ID_Tiket,
                'price' => (int) $group->first()->Harga,
                'quantity' => $group->count(),
                // Midtrans membatasi nama item 50 karakter
                'name' => Str::limit("Tiket {$pesanan->Kelas} " . ucfirst($group->first()->Tipe_Penumpang) . " {$namaRute}", 50, ''),
            ])
            ->values()
            ->all();

        $menit = max(1, (int) ceil(now()->diffInSeconds($pesanan->Batas_Bayar, false) / 60));
        $penumpangPertama = $pesanan->penumpangs->first();

        return [
            'transaction_details' => [
                'order_id' => $pesanan->Kode_Booking,
                'gross_amount' => (int) $pesanan->Total_Harga,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => Str::limit($pesanan->user->name, 50, ''),
                'email' => $pesanan->user->email,
                'phone' => $penumpangPertama?->Telepon,
            ],
            // Transaksi di Midtrans ikut kedaluwarsa saat batas bayar habis
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => $menit,
            ],
            'callbacks' => [
                'finish' => route('invoices.show', $pesanan->ID_Pesanan),
            ],
        ];
    }

    private function labelMetode(array $status): string
    {
        $type = (string) ($status['payment_type'] ?? 'midtrans');

        $bank = $status['va_numbers'][0]['bank'] ?? ($status['bank'] ?? null);
        if ($type === 'echannel') {
            $bank = 'mandiri';
        }

        $label = str_replace('_', ' ', $type);

        return 'Midtrans - ' . ucwords($label) . ($bank ? ' (' . strtoupper($bank) . ')' : '');
    }
}
