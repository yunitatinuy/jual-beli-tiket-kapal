<?php

namespace App\Services;

use App\Models\Penumpang;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;

/** Aturan check-in (naik kapal) penumpang berdasarkan QR / kode booking e-tiket. */
class CheckinService
{
    /** Check-in masih dibuka sampai sekian jam setelah jadwal berangkat (antisipasi keterlambatan kapal). */
    public const JAM_SETELAH_BERANGKAT = 3;

    public function __construct(private BookingService $booking)
    {
    }

    /** Ambil kode booking dari teks hasil scan (URL check-in) atau ketikan manual. */
    public function ambilKode(?string $teks): string
    {
        $teks = trim((string) $teks);
        if ($teks === '') {
            return '';
        }

        $query = parse_url($teks, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $q);
            if (! empty($q['kode'])) {
                $teks = (string) $q['kode'];
            }
        }

        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $teks));
    }

    /**
     * Alasan check-in tidak bisa dilakukan, atau null jika boleh.
     */
    public function alasanDitolak(Pesanan $pesanan): ?string
    {
        if ($pesanan->Status !== Pesanan::STATUS_LUNAS) {
            return $pesanan->Status === Pesanan::STATUS_DIBATALKAN
                ? 'Pesanan ini sudah dibatalkan. Tiket tidak berlaku.'
                : 'Pesanan belum dibayar. Penumpang belum boleh naik.';
        }

        $rute = $pesanan->rute;
        if (! $rute) {
            return 'Data jadwal pesanan ini tidak ditemukan.';
        }

        $berangkat = $this->booking->waktuBerangkat($rute);
        if (! $berangkat->isSameDay(now())) {
            return $berangkat->isFuture()
                ? 'Check-in hanya bisa di hari keberangkatan (' . $berangkat->translatedFormat('d M Y') . ').'
                : 'Jadwal keberangkatan tiket ini sudah lewat (' . $berangkat->translatedFormat('d M Y, H:i') . ').';
        }
        if (now()->greaterThan($berangkat->copy()->addHours(self::JAM_SETELAH_BERANGKAT))) {
            return 'Check-in sudah ditutup. Kapal berangkat pukul ' . $berangkat->format('H:i') . '.';
        }

        return null;
    }

    /** @return int Jumlah penumpang yang baru di-check-in */
    public function checkin(Pesanan $pesanan, User $petugas, ?int $penumpangId = null): int
    {
        if ($alasan = $this->alasanDitolak($pesanan)) {
            throw new BookingException($alasan);
        }

        return Penumpang::where('ID_Pesanan', $pesanan->ID_Pesanan)
            ->whereNull('Checkin_Pada')
            ->when($penumpangId, fn ($q) => $q->whereKey($penumpangId))
            ->update(['Checkin_Pada' => now(), 'Checkin_Oleh' => $petugas->id]);
    }

    public function batal(Pesanan $pesanan, int $penumpangId): bool
    {
        return Penumpang::where('ID_Pesanan', $pesanan->ID_Pesanan)
            ->whereKey($penumpangId)
            ->update(['Checkin_Pada' => null, 'Checkin_Oleh' => null]) > 0;
    }

    public function waktuBerangkat(Pesanan $pesanan): ?Carbon
    {
        return $pesanan->rute ? $this->booking->waktuBerangkat($pesanan->rute) : null;
    }
}
