<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\Pelabuhan;
use App\Models\Rute;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SekaliPergiController extends Controller
{
    public function __construct(private BookingService $booking)
    {
    }

    public function index()
    {
        return view('pengguna.sekalipergi', [
            'kapals' => Kapal::all(),
            'pelabuhans' => Pelabuhan::all(),
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'routeFrom' => 'nullable|string|max:100',
            'routeTo' => 'nullable|string|max:100',
            'tanggal_keberangkatan' => 'required|date',
            'dewasa' => 'nullable|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
            'anak' => 'nullable|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
        ], [
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan harus diisi.',
        ]);

        $routeFrom = $data['routeFrom'] ?? '';
        $routeTo = $data['routeTo'] ?? '';
        $tanggal = Carbon::parse($data['tanggal_keberangkatan']);
        $dewasa = (int) ($data['dewasa'] ?? 0);
        $anak = (int) ($data['anak'] ?? 0);

        $searchResults = [];

        if ($dewasa + $anak < 1) {
            $searchResults['message'] = 'Isi jumlah penumpang minimal 1 orang.';
        } elseif ($dewasa + $anak > BookingService::MAX_PENUMPANG) {
            $searchResults['message'] = 'Maksimal ' . BookingService::MAX_PENUMPANG . ' penumpang per pesanan.';
        } else {
            $query = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan', 'kapal', 'tiket.harga'])
                ->whereDate('Tanggal', $tanggal->toDateString())
                ->orderBy('Jam');

            if ($routeFrom !== '') {
                $query->whereHas('pelabuhanAsal', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeFrom . '%'));
            }
            if ($routeTo !== '') {
                $query->whereHas('pelabuhanTujuan', fn ($q) => $q->where('Nama_Pelabuhan', 'like', '%' . $routeTo . '%'));
            }

            // Hanya jadwal yang belum berangkat dan punya tiket yang bisa dijual
            $jadwal = $query->get()
                ->reject(fn (Rute $rute) => $this->booking->sudahBerangkat($rute))
                ->map(fn (Rute $rute) => [
                    'rute' => $rute,
                    'offers' => $this->booking->offersFor($rute),
                ])
                ->filter(fn ($item) => ! empty($item['offers']))
                ->values();

            if ($jadwal->isEmpty()) {
                $searchResults['message'] = "Tiket perjalanan dari {$routeFrom} ke {$routeTo} pada tanggal "
                    . $tanggal->format('d-m-Y')
                    . ' tidak dapat ditemukan. Silakan pilih tanggal lain.';
            } else {
                $searchResults['jadwal'] = $jadwal;
            }
        }

        return view('pengguna.sekalipergi', [
            'searchResults' => $searchResults,
            'kapals' => Kapal::all(),
            'pelabuhans' => Pelabuhan::all(),
            'dewasa' => $dewasa,
            'anak' => $anak,
        ]);
    }
}
