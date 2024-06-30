<?php

namespace App\Http\Controllers;

use App\Models\Penumpang;
use App\Enums\JenisKelamin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesanTiketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Method to display the form for passenger data input
    public function create()
    {
        $jenisKelaminOptions = JenisKelamin::getValues();
        return view('pengguna.pesantiket', compact('jenisKelaminOptions'));
    }

    // Method to save passenger data to the database
    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'Nama_Lengkap.*' => 'required|string|max:255',
            'Telepon.*' => 'required|numeric|digits_between:11,13',
            'Alamat.*' => 'required|string|max:255',
            'Jenis_Kelamin.*' => ['required', Rule::in(JenisKelamin::getValues())],
        ], [
            'Nama_Lengkap.*.required' => 'Nama lengkap harus diisi.',
            'Nama_Lengkap.*.string' => 'Nama lengkap harus berupa teks.',
            'Nama_Lengkap.*.max' => 'Nama lengkap tidak boleh lebih dari :max karakter.',
            'Telepon.*.required' => 'Nomor telepon harus diisi.',
            'Telepon.*.numeric' => 'Nomor telepon harus berupa angka.',
            'Telepon.*.digits_between' => 'Nomor telepon harus memiliki panjang antara :min dan :max digit.',
            'Alamat.*.required' => 'Alamat harus diisi.',
            'Alamat.*.string' => 'Alamat harus berupa teks.',
            'Alamat.*.max' => 'Alamat tidak boleh lebih dari :max karakter.',
            'Jenis_Kelamin.*.required' => 'Jenis kelamin harus dipilih.',
            'Jenis_Kelamin.*.in' => 'Jenis kelamin yang dipilih tidak valid.',
        ]);

        // Iterate over each set of passenger data and save to the database
        $passengerData = [];
        for ($i = 0; $i < count($request->Nama_Lengkap); $i++) {
            $passengerData[] = [
                'id' => auth()->user()->id,
                'Nama_Lengkap' => $request->Nama_Lengkap[$i],
                'Telepon' => $request->Telepon[$i],
                'Alamat' => $request->Alamat[$i],
                'Jenis_Kelamin' => $request->Jenis_Kelamin[$i],
            ];
        }

        Penumpang::insert($passengerData);

        // Calculate total price
        $totalHarga = ($request->harga_vip_dewasa ?? 0) + ($request->harga_vip_anak ?? 0) + ($request->harga_ekonomi_dewasa ?? 0) + ($request->harga_ekonomi_anak ?? 0);

        // Redirect to payment page with price data in session
        return redirect()->route('pembayaran')->with([
            'success' => 'Data penumpang berhasil disimpan',
            'harga_vip_dewasa' => $request->harga_vip_dewasa,
            'harga_vip_anak' => $request->harga_vip_anak,
            'harga_ekonomi_dewasa' => $request->harga_ekonomi_dewasa,
            'harga_ekonomi_anak' => $request->harga_ekonomi_anak,
            'totalHarga' => $totalHarga
        ]);
    }

    public function index(Request $request)
    {
        $jenisKelaminOptions = JenisKelamin::getValues();

        // Retrieve data from the request
        $asal = $request->input('asal');
        $tujuan = $request->input('tujuan');
        $tanggal = $request->input('tanggal');
        $kapal = $request->input('kapal');
        $jam = $request->input('jam');
        $harga_vip_dewasa = $request->input('harga_vip_dewasa');
        $harga_vip_anak = $request->input('harga_vip_anak');
        $harga_ekonomi_dewasa = $request->input('harga_ekonomi_dewasa');
        $harga_ekonomi_anak = $request->input('harga_ekonomi_anak');

        // Pass data to the view
        return view('pengguna.pesantiket', compact(
            'jenisKelaminOptions',
            'asal',
            'tujuan',
            'tanggal',
            'kapal',
            'jam',
            'harga_vip_dewasa',
            'harga_vip_anak',
            'harga_ekonomi_dewasa',
            'harga_ekonomi_anak'
        ));
    }
}
