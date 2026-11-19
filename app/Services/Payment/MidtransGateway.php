<?php

namespace App\Services\Payment;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

/**
 * Pembungkus tipis untuk library Midtrans, supaya PaymentService
 * bisa dites dengan gateway palsu tanpa memanggil server Midtrans.
 */
class MidtransGateway
{
    public function __construct()
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$clientKey = (string) config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function isConfigured(): bool
    {
        return config('services.midtrans.server_key') && config('services.midtrans.client_key');
    }

    public function serverKey(): string
    {
        return (string) config('services.midtrans.server_key');
    }

    public function clientKey(): string
    {
        return (string) config('services.midtrans.client_key');
    }

    public function snapJsUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function createSnapToken(array $params): string
    {
        $this->ensureConfigured();

        return Snap::getSnapToken($params);
    }

    /**
     * Status transaksi langsung dari API Midtrans (sumber kebenaran).
     *
     * @return array<string, mixed>
     */
    public function status(string $orderId): array
    {
        $this->ensureConfigured();

        return json_decode(json_encode(Transaction::status($orderId)), true) ?? [];
    }

    private function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Midtrans belum dikonfigurasi (MIDTRANS_SERVER_KEY / MIDTRANS_CLIENT_KEY kosong).');
        }
    }
}
