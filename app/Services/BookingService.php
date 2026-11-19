<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Rute;
use App\Models\Tiket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Semua aturan pemesanan tiket ada di sini:
 * harga selalu diambil dari database, kuota dicek dan dikurangi di dalam transaksi.
 */
class BookingService
{
    public const KELAS = ['VIP', 'Ekonomi'];
    public const TIPE = ['dewasa', 'anak'];
    public const MAX_PENUMPANG = 10;

    /**
     * Hitung rincian harga untuk rute + kelas + jumlah penumpang.
     *
     * @return array{rute: Rute, kelas: string, items: array<string, array{tiket: Tiket, harga: int, jumlah: int, subtotal: int}>, total: int}
     */
    public function quote(int $ruteId, string $kelas, int $dewasa, int $anak): array
    {
        $this->validateJumlah($dewasa, $anak);
        $kelas = $this->normalizeKelas($kelas);

        $rute = Rute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])->find($ruteId);
        if (! $rute) {
            throw new BookingException('Jadwal kapal tidak ditemukan.');
        }
        $this->ensureBelumBerangkat($rute);

        $tikets = $this->tiketsFor($ruteId, $kelas);

        return $this->buildQuote($rute, $kelas, $tikets, ['dewasa' => $dewasa, 'anak' => $anak]);
    }

    /**
     * Buat pesanan. Kuota tiket dikunci (SELECT ... FOR UPDATE) supaya dua orang
     * yang memesan bersamaan tidak bisa membeli kursi terakhir yang sama.
     *
     * @param  array<int, array{Nama_Lengkap: string, Telepon: string, Alamat: string, Jenis_Kelamin: string}>  $penumpang
     *         urutan: semua penumpang dewasa dulu, lalu anak
     */
    public function book(User $user, int $ruteId, string $kelas, int $dewasa, int $anak, array $penumpang): Pesanan
    {
        $this->validateJumlah($dewasa, $anak);
        $kelas = $this->normalizeKelas($kelas);

        if (count($penumpang) !== $dewasa + $anak) {
            throw new BookingException('Jumlah data penumpang tidak sesuai dengan jumlah tiket.');
        }

        return DB::transaction(function () use ($user, $ruteId, $kelas, $dewasa, $anak, $penumpang) {
            $rute = Rute::find($ruteId);
            if (! $rute) {
                throw new BookingException('Jadwal kapal tidak ditemukan.');
            }
            $this->ensureBelumBerangkat($rute);

            $tikets = $this->tiketsFor($ruteId, $kelas, lock: true);
            $quote = $this->buildQuote($rute, $kelas, $tikets, ['dewasa' => $dewasa, 'anak' => $anak]);

            // Kurangi kuota
            foreach ($quote['items'] as $item) {
                $item['tiket']->decrement('Jumlah_Tiket', $item['jumlah']);
            }

            $now = now();
            $pesanan = Pesanan::create([
                'Kode_Booking' => $this->generateKodeBooking(),
                'ID_User' => $user->id,
                'ID_Rute' => $rute->ID_Rute,
                'Kelas' => $kelas,
                'Jumlah_Dewasa' => $dewasa,
                'Jumlah_Anak' => $anak,
                'Tanggal_Pesanan' => $now->toDateString(),
                'Waktu' => $now->format('H:i:s'),
                'Total_Harga' => $quote['total'],
                'Metode_Bayar' => 'Midtrans',
                'Status' => Pesanan::STATUS_MENUNGGU_PEMBAYARAN,
                'Batas_Bayar' => $now->copy()->addMinutes(self::batasBayarMenit()),
            ]);

            foreach (array_values($penumpang) as $i => $data) {
                $tipe = $i < $dewasa ? 'dewasa' : 'anak';
                $item = $quote['items'][$tipe];

                $pesanan->penumpangs()->create([
                    'ID_Tiket' => $item['tiket']->ID_Tiket,
                    'id' => $user->id,
                    'Nama_Lengkap' => $data['Nama_Lengkap'],
                    'Telepon' => $data['Telepon'],
                    'Alamat' => $data['Alamat'],
                    'Jenis_Kelamin' => $data['Jenis_Kelamin'],
                    'Tipe_Penumpang' => $tipe,
                    'Harga' => $item['harga'],
                ]);
            }

            return $pesanan;
        });
    }

    /**
     * Batalkan pesanan yang belum dibayar dan kembalikan kuota tiketnya.
     * Aman dipanggil berkali-kali (idempotent): pesanan yang sudah lunas/dibatalkan tidak diubah.
     *
     * @return bool true jika pesanan benar-benar dibatalkan oleh panggilan ini
     */
    public function cancel(Pesanan $pesanan, string $alasan = ''): bool
    {
        $cancelled = DB::transaction(function () use ($pesanan) {
            $locked = Pesanan::whereKey($pesanan->getKey())->lockForUpdate()->first();
            if (! $locked || $locked->Status !== Pesanan::STATUS_MENUNGGU_PEMBAYARAN) {
                return false;
            }

            $perTiket = $locked->penumpangs()->whereNotNull('ID_Tiket')
                ->get()
                ->countBy('ID_Tiket');

            foreach ($perTiket as $tiketId => $jumlah) {
                Tiket::whereKey($tiketId)->lockForUpdate()->first()?->increment('Jumlah_Tiket', $jumlah);
            }

            $locked->forceFill(['Status' => Pesanan::STATUS_DIBATALKAN])->save();

            return true;
        });

        if ($cancelled) {
            $pesanan->refresh();
            \Illuminate\Support\Facades\Log::info('Pesanan dibatalkan', [
                'kode' => $pesanan->Kode_Booking,
                'alasan' => $alasan,
            ]);
        }

        return $cancelled;
    }

    /**
     * Batalkan semua pesanan yang melewati batas bayar.
     * Diberi jeda beberapa menit setelah batas bayar supaya notifikasi pembayaran
     * dari Midtrans yang datang terlambat masih sempat diproses lebih dulu.
     */
    public function cancelExpired(int $graceMinutes = 5): int
    {
        $count = 0;

        Pesanan::where('Status', Pesanan::STATUS_MENUNGGU_PEMBAYARAN)
            ->whereNotNull('Batas_Bayar')
            ->where('Batas_Bayar', '<', now()->subMinutes($graceMinutes))
            ->orderBy('ID_Pesanan')
            ->each(function (Pesanan $pesanan) use (&$count) {
                if ($this->cancel($pesanan, 'Batas waktu pembayaran habis')) {
                    $count++;
                }
            });

        return $count;
    }

    public static function batasBayarMenit(): int
    {
        return max(5, (int) config('services.midtrans.batas_bayar_menit', 60));
    }

    /**
     * Ringkasan kelas yang tersedia untuk satu rute (dipakai di hasil pencarian).
     *
     * @return array<string, array<string, array{harga: int, sisa: int}>>  [kelas => [tipe => info]]
     */
    public function offersFor(Rute $rute): array
    {
        $offers = [];
        foreach ($rute->tiket as $tiket) {
            $harga = $tiket->harga;
            if (! $harga || $harga->Harga <= 0) {
                continue;
            }
            $kelas = $this->normalizeKelas($harga->Kelas, strict: false);
            $tipe = strtolower($harga->Tipe_Penumpang);
            if (! $kelas || ! in_array($tipe, self::TIPE, true)) {
                continue;
            }
            // Jika ada lebih dari satu tiket untuk kombinasi yang sama, pakai yang pertama
            $offers[$kelas][$tipe] ??= [
                'harga' => (int) $harga->Harga,
                'sisa' => (int) $tiket->Jumlah_Tiket,
            ];
        }

        return $offers;
    }

    public function sudahBerangkat(Rute $rute): bool
    {
        return $this->waktuBerangkat($rute)->isPast();
    }

    public function waktuBerangkat(Rute $rute): Carbon
    {
        return Carbon::parse(Carbon::parse($rute->Tanggal)->toDateString() . ' ' . $rute->Jam);
    }

    // ---------------------------------------------------------------------

    private function buildQuote(Rute $rute, string $kelas, Collection $tikets, array $jumlah): array
    {
        $items = [];
        $total = 0;

        foreach ($jumlah as $tipe => $n) {
            if ($n <= 0) {
                continue;
            }

            /** @var Tiket|null $tiket */
            $tiket = $tikets->first(fn (Tiket $t) => strtolower($t->harga->Tipe_Penumpang) === $tipe);
            if (! $tiket) {
                throw new BookingException("Tiket kelas {$kelas} untuk penumpang {$tipe} tidak tersedia di jadwal ini.");
            }
            if ($tiket->Jumlah_Tiket < $n) {
                throw new BookingException(
                    "Sisa tiket kelas {$kelas} untuk penumpang {$tipe} tinggal {$tiket->Jumlah_Tiket}."
                );
            }

            $harga = (int) $tiket->harga->Harga;
            $subtotal = $harga * $n;
            $items[$tipe] = ['tiket' => $tiket, 'harga' => $harga, 'jumlah' => $n, 'subtotal' => $subtotal];
            $total += $subtotal;
        }

        return ['rute' => $rute, 'kelas' => $kelas, 'items' => $items, 'total' => $total];
    }

    private function tiketsFor(int $ruteId, string $kelas, bool $lock = false): Collection
    {
        $query = Tiket::with('harga')
            ->where('ID_Rute', $ruteId)
            ->whereHas('harga', fn ($q) => $q->where('Kelas', $kelas)->where('Harga', '>', 0))
            ->orderBy('ID_Tiket');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get();
    }

    private function validateJumlah(int $dewasa, int $anak): void
    {
        if ($dewasa < 0 || $anak < 0) {
            throw new BookingException('Jumlah penumpang tidak valid.');
        }
        if ($dewasa + $anak < 1) {
            throw new BookingException('Minimal pesan 1 tiket.');
        }
        if ($dewasa + $anak > self::MAX_PENUMPANG) {
            throw new BookingException('Maksimal ' . self::MAX_PENUMPANG . ' penumpang per pesanan.');
        }
    }

    private function normalizeKelas(string $kelas, bool $strict = true): ?string
    {
        foreach (self::KELAS as $k) {
            if (strcasecmp($k, trim($kelas)) === 0) {
                return $k;
            }
        }
        if ($strict) {
            throw new BookingException('Kelas tidak valid.');
        }

        return null;
    }

    private function ensureBelumBerangkat(Rute $rute): void
    {
        if ($this->sudahBerangkat($rute)) {
            throw new BookingException('Jadwal ini sudah berangkat dan tidak bisa dipesan.');
        }
    }

    private function generateKodeBooking(): string
    {
        do {
            $kode = 'KPL' . now()->format('ymd') . strtoupper(Str::random(5));
        } while (Pesanan::where('Kode_Booking', $kode)->exists());

        return $kode;
    }
}
