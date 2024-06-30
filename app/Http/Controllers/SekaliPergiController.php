<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Kapal;
use App\Models\Rute;
use App\Models\Pelabuhan;
use App\Models\Harga;
use App\Models\Tiket;
use Carbon\Carbon;

class SekaliPergiController extends Controller
{
    public function index()
    {
        return view('pengguna.sekalipergi', [
            'kapals' => Kapal::all(),
            'pelabuhans' => Pelabuhan::all(),
        ]);
    }

    public function search(Request $request)
    {
        $searchResults = [];
        $routeFrom = $request->input('routeFrom');
        $routeTo = $request->input('routeTo');
        $tanggal = $request->input('tanggal_keberangkatan');
        $dewasa = $request->input('dewasa', 0);
        $anak = $request->input('anak', 0);

        if (!$tanggal) {
            $searchResults['message'] = "Tanggal keberangkatan harus diisi.";
        } else {
            $parsedDate = Carbon::parse($tanggal)->format('Y-m-d');

            // Search routes
            $query = Rute::with(['pelabuhanAsal', 'pelabuhanTujuan', 'kapal', 'tiket.harga'])
                ->whereDate('Tanggal', $parsedDate);

            if ($routeFrom) {
                $query->whereHas('pelabuhanAsal', function ($q) use ($routeFrom) {
                    $q->where('Nama_Pelabuhan', 'like', '%' . $routeFrom . '%');
                });
            }

            if ($routeTo) {
                $query->whereHas('pelabuhanTujuan', function ($q) use ($routeTo) {
                    $q->where('Nama_Pelabuhan', 'like', '%' . $routeTo . '%');
                });
            }

            $routes = $query->get();

            // Logging for debugging
            Log::info('Routes found:', ['routes' => $routes->toArray()]);

            // Check if routes are found
            if ($routes->isEmpty()) {
                $searchResults['message'] = "Tiket perjalanan dari {$routeFrom} ke {$routeTo} pada tanggal {$tanggal} tidak dapat ditemukan. Silakan pilih tanggal lain untuk tiket ferry dari {$routeFrom} ke {$routeTo}.";
            } else {
                // Calculate prices based on the number of passengers
                foreach ($routes as $route) {
                    foreach ($route->tiket as $tiket) {
                        if ($tiket->harga->Tipe_Penumpang == 'dewasa') {
                            $tiket->harga->Harga *= $dewasa;
                        } elseif ($tiket->harga->Tipe_Penumpang == 'anak') {
                            $tiket->harga->Harga *= $anak;
                        }
                    }
                }

                $searchResults['routes'] = $routes;
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

    public function cari(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'Tanggal' => 'required|date',
            'ID_Rute' => 'required|numeric',
        ]);

        // Simpan ID_Rute ke session
        $request->session()->put('ID_Rute', $validated['ID_Rute']);

        // Redirect ke halaman pembayaran
        return redirect()->route('pembayaran.index');
    }
}
