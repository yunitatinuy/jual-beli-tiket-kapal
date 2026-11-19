<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class BatalkanPesananKedaluwarsa extends Command
{
    protected $signature = 'pesanan:batalkan-kedaluwarsa';

    protected $description = 'Batalkan pesanan yang melewati batas bayar dan kembalikan kuota tiketnya';

    public function handle(BookingService $booking): int
    {
        $jumlah = $booking->cancelExpired();
        $this->info("{$jumlah} pesanan kedaluwarsa dibatalkan.");

        return self::SUCCESS;
    }
}
