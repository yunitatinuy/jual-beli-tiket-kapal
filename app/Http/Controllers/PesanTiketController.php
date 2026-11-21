<?php

namespace App\Http\Controllers;

use App\Enums\JenisKelamin;
use App\Services\BookingException;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesanTiketController extends Controller
{
    public function __construct(private BookingService $booking)
    {
    }

    /**
     * Form data penumpang. Hanya menerima ID rute, kelas dan jumlah penumpang;
     * harga dihitung ulang dari database.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'rute' => 'required|integer',
            'kelas' => ['required', Rule::in(BookingService::KELAS)],
            'dewasa' => 'nullable|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
            'anak' => 'nullable|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
        ]);

        try {
            $quote = $this->booking->quote(
                (int) $data['rute'],
                $data['kelas'],
                (int) ($data['dewasa'] ?? 0),
                (int) ($data['anak'] ?? 0),
            );
        } catch (BookingException $e) {
            return redirect()->route('informasi')->with('error', $e->getMessage());
        }

        return view('pengguna.pesantiket', [
            'quote' => $quote,
            'jenisKelaminOptions' => JenisKelamin::getValues(),
            'batasBayarMenit' => BookingService::batasBayarMenit(),
        ]);
    }

    public function store(Request $request)
    {
        $base = $request->validate([
            'rute' => 'required|integer',
            'kelas' => ['required', Rule::in(BookingService::KELAS)],
            'dewasa' => 'required|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
            'anak' => 'required|integer|min:0|max:' . BookingService::MAX_PENUMPANG,
        ]);

        $jumlah = (int) $base['dewasa'] + (int) $base['anak'];

        $request->validate([
            'penumpang' => "required|array|size:{$jumlah}",
            'penumpang.*.Nama_Lengkap' => 'required|string|max:255',
            'penumpang.*.Telepon' => ['required', 'regex:/^(\+62|62|0)8[0-9]{8,12}$/'],
            'penumpang.*.Alamat' => 'required|string|max:255',
            'penumpang.*.Jenis_Kelamin' => ['required', Rule::in(JenisKelamin::getValues())],
        ], [
            'penumpang.size' => 'Jumlah data penumpang harus :size orang.',
            'penumpang.*.Nama_Lengkap.required' => 'Nama lengkap wajib diisi.',
            'penumpang.*.Telepon.required' => 'Nomor telepon wajib diisi.',
            'penumpang.*.Telepon.regex' => 'Nomor telepon tidak valid (contoh: 081234567890).',
            'penumpang.*.Alamat.required' => 'Alamat wajib diisi.',
            'penumpang.*.Jenis_Kelamin.required' => 'Jenis kelamin wajib dipilih.',
        ]);

        try {
            $pesanan = $this->booking->book(
                $request->user(),
                (int) $base['rute'],
                $base['kelas'],
                (int) $base['dewasa'],
                (int) $base['anak'],
                array_values($request->input('penumpang')),
            );
        } catch (BookingException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('invoices.show', ['id' => $pesanan->ID_Pesanan])
            ->with('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran sebelum '
                . $pesanan->Batas_Bayar->format('H:i') . ' WIB.');
    }
}
