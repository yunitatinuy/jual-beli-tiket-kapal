<?php

namespace App\Console\Commands;

use App\Services\Payment\MidtransGateway;
use Illuminate\Console\Command;

/**
 * Cek pengaturan Midtrans di .env tanpa membuat transaksi:
 * meminta status order fiktif ke API Midtrans.
 *  - 404 = key valid (order memang tidak ada)
 *  - 401 = Server Key salah / tidak cocok dengan mode sandbox/produksi
 */
class CekMidtrans extends Command
{
    protected $signature = 'midtrans:cek';

    protected $description = 'Cek apakah Server Key / Client Key Midtrans di .env valid';

    public function handle(MidtransGateway $gateway): int
    {
        $produksi = (bool) config('services.midtrans.is_production');
        $server = (string) config('services.midtrans.server_key');
        $client = (string) config('services.midtrans.client_key');

        $this->line('Mode        : ' . ($produksi ? 'PRODUKSI (uang sungguhan)' : 'Sandbox (uji coba)'));
        $this->line('Server Key  : ' . ($server ? $this->samarkan($server) : '(kosong)'));
        $this->line('Client Key  : ' . ($client ? $this->samarkan($client) : '(kosong)'));
        $this->line('Batas bayar : ' . config('services.midtrans.batas_bayar_menit') . ' menit');

        if (! $server || ! $client) {
            $this->error('MIDTRANS_SERVER_KEY / MIDTRANS_CLIENT_KEY di .env masih kosong.');

            return self::FAILURE;
        }

        // Key sandbox lama berawalan "SB-"; akun baru bisa tanpa awalan, jadi hanya peringatan.
        // Kecocokan mode sebenarnya dipastikan oleh panggilan API di bawah (401 jika salah).
        if ($produksi && str_starts_with($server, 'SB-')) {
            $this->warn('Mode produksi tetapi Server Key berawalan SB- (key sandbox). Mungkin MIDTRANS_IS_PRODUCTION harus false.');
        }
        if (! str_contains($server, 'server') || ! str_contains($client, 'client')) {
            $this->error('Server Key dan Client Key sepertinya tertukar. Server Key berisi "Mid-server-", Client Key berisi "Mid-client-".');

            return self::FAILURE;
        }

        try {
            $gateway->status('CEK-KONEKSI-' . now()->format('YmdHis'));
            $this->info('Terhubung ke Midtrans.');
        } catch (\Throwable $e) {
            $pesan = $e->getMessage();

            if (str_contains($pesan, '404') || str_contains($pesan, "doesn't exist")) {
                $this->info('Server Key valid dan terhubung ke Midtrans.');
            } elseif (str_contains($pesan, '401') || str_contains($pesan, 'unauthorized') || str_contains($pesan, 'Access denied')) {
                $this->error('Server Key ditolak Midtrans (401). Salin ulang dari Settings > Access Keys.');

                return self::FAILURE;
            } else {
                $this->error('Gagal menghubungi Midtrans: ' . $pesan);

                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->line('Snap.js     : ' . $gateway->snapJsUrl());
        $this->line('Webhook URL : ' . url('/midtrans/notification') . ' (daftarkan versi https setelah online)');

        return self::SUCCESS;
    }

    private function samarkan(string $key): string
    {
        return substr($key, 0, 11) . str_repeat('*', 6) . substr($key, -4);
    }
}
