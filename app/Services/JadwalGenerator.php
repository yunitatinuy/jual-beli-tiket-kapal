<?php

namespace App\Services;

use App\Models\Harga;
use App\Models\Rute;
use App\Models\Tiket;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

/**
 * Membuat banyak jadwal sekaligus: untuk setiap tanggal (sesuai hari yang dipilih)
 * dan setiap jam, dibuat 1 rute + harga + tiket (kuota) per kombinasi kelas/tipe.
 * Jadwal yang sudah ada (kapal, asal, tujuan, tanggal, jam sama) dilewati.
 */
class JadwalGenerator
{
    public const MAKS_JADWAL = 500;

    /**
     * @param  array<int>  $hari  Hari dalam minggu, ISO: 1 = Senin ... 7 = Minggu
     * @param  array<string>  $jamList  Format "HH:MM"
     * @param  array<string, array<string, array{harga:int, kuota:int}>>  $tarif  [Kelas][tipe] => harga & kuota
     * @return array<int, array{tanggal: string, jam: string}>
     */
    public function rencana(string $mulai, string $selesai, array $hari, array $jamList): array
    {
        $hasil = [];
        $sekarang = now();

        foreach (CarbonPeriod::create($mulai, $selesai) as $tanggal) {
            if (! in_array($tanggal->dayOfWeekIso, $hari, true)) {
                continue;
            }
            foreach ($jamList as $jam) {
                $waktu = Carbon::parse($tanggal->toDateString() . ' ' . $jam);
                if ($waktu->lessThanOrEqualTo($sekarang)) {
                    continue; // tidak membuat jadwal yang sudah lewat
                }
                $hasil[] = ['tanggal' => $tanggal->toDateString(), 'jam' => $jam];
            }
        }

        return $hasil;
    }

    /**
     * @return array{dibuat: int, dilewati: int}
     */
    public function buat(int $kapal, int $asal, int $tujuan, array $rencana, array $tarif): array
    {
        if (count($rencana) > self::MAKS_JADWAL) {
            throw new \InvalidArgumentException('Terlalu banyak jadwal sekaligus (maksimal ' . self::MAKS_JADWAL . ').');
        }

        return DB::transaction(function () use ($kapal, $asal, $tujuan, $rencana, $tarif) {
            $dibuat = 0;
            $dilewati = 0;

            // Jadwal yang sudah ada untuk lintasan & kapal ini dalam rentang tanggal
            $tanggalList = array_unique(array_column($rencana, 'tanggal'));
            $ada = Rute::where('ID_Kapal', $kapal)
                ->where('Pelabuhan_Asal', $asal)
                ->where('Pelabuhan_Tujuan', $tujuan)
                ->whereIn('Tanggal', $tanggalList)
                ->get(['Tanggal', 'Jam'])
                ->map(fn ($r) => Carbon::parse($r->Tanggal)->toDateString() . ' ' . substr((string) $r->Jam, 0, 5))
                ->flip();

            foreach ($rencana as $item) {
                if ($ada->has($item['tanggal'] . ' ' . $item['jam'])) {
                    $dilewati++;
                    continue;
                }

                $rute = Rute::create([
                    'ID_Kapal' => $kapal,
                    'Pelabuhan_Asal' => $asal,
                    'Pelabuhan_Tujuan' => $tujuan,
                    'Tanggal' => $item['tanggal'],
                    'Jam' => $item['jam'] . ':00',
                ]);

                foreach ($tarif as $kelas => $perTipe) {
                    foreach ($perTipe as $tipe => $nilai) {
                        $harga = Harga::create([
                            'ID_Rute' => $rute->ID_Rute,
                            'Tipe_Penumpang' => $tipe,
                            'Kelas' => $kelas,
                            'Harga' => (int) $nilai['harga'],
                        ]);
                        Tiket::create([
                            'ID_Rute' => $rute->ID_Rute,
                            'ID_Harga' => $harga->ID_Harga,
                            'Jumlah_Tiket' => (int) $nilai['kuota'],
                        ]);
                    }
                }

                $dibuat++;
            }

            return ['dibuat' => $dibuat, 'dilewati' => $dilewati];
        });
    }
}
