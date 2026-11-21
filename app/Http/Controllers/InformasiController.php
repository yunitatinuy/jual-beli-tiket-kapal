<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;
use App\Models\Rute;
use App\Services\BookingService;
use App\Services\JadwalService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function __construct(
        private BookingService $booking,
        private JadwalService $jadwalService,
    ) {
    }

    public function index(Request $request)
    {
        $routeFrom = trim((string) $request->input('routeFrom', ''));
        $routeTo = trim((string) $request->input('routeTo', ''));

        $tanggalInput = $request->input('tanggal_keberangkatan');
        if ($tanggalInput) {
            try {
                $tanggal = Carbon::parse($tanggalInput);
            } catch (\Exception $e) {
                $tanggal = now()->addDay();
            }
        } else {
            $tanggal = now()->addDay();
        }

        $tipePerjalanan = $request->input('tipe_perjalanan', 'sekali_jalan');
        $tanggalPulangInput = $request->input('tanggal_pulang');
        $isPulangPergi = $tipePerjalanan === 'pulang_pergi' && !empty($tanggalPulangInput);

        $tanggalPulang = null;
        if ($isPulangPergi) {
            try {
                $tanggalPulang = Carbon::parse($tanggalPulangInput);
            } catch (\Exception $e) {
                $tanggalPulang = $tanggal->copy()->addDays(2);
            }
        }

        $dewasa = max(0, min(BookingService::MAX_PENUMPANG, (int) $request->input('dewasa', 1)));
        $anak = max(0, min(BookingService::MAX_PENUMPANG, (int) $request->input('anak', 0)));
        if ($dewasa + $anak < 1) {
            $dewasa = 1;
        }

        $query = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan', 'kapal', 'tiket.harga'])
            ->whereDate('Tanggal', $tanggal->toDateString())
            ->orderBy('Jam');

        if ($routeFrom !== '') {
            $query->whereHas('pelabuhanAsal', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeFrom . '%'));
        }
        if ($routeTo !== '') {
            $query->whereHas('pelabuhanTujuan', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeTo . '%'));
        }

        $jadwal = $query->get()
            ->reject(fn (Rute $rute) => $this->booking->sudahBerangkat($rute))
            ->map(fn (Rute $rute) => [
                'rute' => $rute,
                'offers' => $this->booking->offersFor($rute),
            ])
            ->filter(fn ($item) => ! empty($item['offers']))
            ->values();

        // Pencarian jadwal pulang jika tipe perjalanan pulang-pergi
        $jadwalPulang = collect();
        if ($isPulangPergi && $tanggalPulang) {
            $queryPulang = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan', 'kapal', 'tiket.harga'])
                ->whereDate('Tanggal', $tanggalPulang->toDateString())
                ->orderBy('Jam');

            if ($routeTo !== '') {
                $queryPulang->whereHas('pelabuhanAsal', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeTo . '%'));
            }
            if ($routeFrom !== '') {
                $queryPulang->whereHas('pelabuhanTujuan', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeFrom . '%'));
            }

            $jadwalPulang = $queryPulang->get()
                ->reject(fn (Rute $rute) => $this->booking->sudahBerangkat($rute))
                ->map(fn (Rute $rute) => [
                    'rute' => $rute,
                    'offers' => $this->booking->offersFor($rute),
                ])
                ->filter(fn ($item) => ! empty($item['offers']))
                ->values();
        }

        $searchResults = [];
        if ($jadwal->isEmpty()) {
            if ($routeFrom !== '' && $routeTo !== '') {
                $searchResults['message'] = "Tiket perjalanan dari {$routeFrom} ke {$routeTo} pada tanggal "
                    . $tanggal->translatedFormat('d F Y')
                    . ' tidak ditemukan. Silakan pilih tanggal atau rute lain.';
            } elseif ($routeFrom !== '' || $routeTo !== '') {
                $pelabuhanCari = $routeFrom ?: $routeTo;
                $searchResults['message'] = "Tiket perjalanan untuk pelabuhan {$pelabuhanCari} pada tanggal "
                    . $tanggal->translatedFormat('d F Y')
                    . ' tidak ditemukan. Silakan pilih tanggal lain.';
            } else {
                $searchResults['message'] = 'Belum ada jadwal keberangkatan kapal untuk tanggal '
                    . $tanggal->translatedFormat('d F Y')
                    . '. Silakan pilih tanggal atau pelabuhan lain.';
            }
        } else {
            $searchResults['jadwal'] = $jadwal;
        }

        if ($isPulangPergi) {
            $searchResults['jadwal_pulang'] = $jadwalPulang;
        }

        return view('pengguna.informasi', [
            'searchResults' => $searchResults,
            'namaPelabuhan' => $this->jadwalService->namaPelabuhan(),
            'dewasa' => $dewasa,
            'anak' => $anak,
            'tanggal' => $tanggal,
            'tanggalPulang' => $tanggalPulang,
            'tipePerjalanan' => $tipePerjalanan,
            'isPulangPergi' => $isPulangPergi,
            'jadwalPulang' => $jadwalPulang,
        ]);
    }
}
