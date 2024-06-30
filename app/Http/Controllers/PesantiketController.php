<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
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

    public function create()
    {
        $jenisKelaminOptions = JenisKelamin::getValues();
        return view('pengguna.pesantiket', compact('jenisKelaminOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama_Lengkap.*' => 'required|string|max:255',
            'Telepon.*' => 'required|numeric|digits_between:11,13',
            'Alamat.*' => 'required|string|max:255',
            'Jenis_Kelamin.*' => ['required', Rule::in(JenisKelamin::getValues())],
            'metode_bayar' => 'required|string',
        ]);

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

        $totalHarga = ($request->harga_vip_dewasa ?? 0) + ($request->harga_vip_anak ?? 0) + ($request->harga_ekonomi_dewasa ?? 0) + ($request->harga_ekonomi_anak ?? 0);

        // Simpan pesanan ke tabel Pesanan
        $pesanan = Pesanan::create([
            'ID_User' => auth()->user()->id,
            'Tanggal_Pesanan' => now()->toDateString(),
            'Waktu' => now()->format('H:i:s'),
            'Total_Harga' => $totalHarga,
            'Metode_Bayar' => $request->metode_bayar,
            'Kapal' => $request->kapal,
            'Asal' => $request->asal,
            'Tujuan' => $request->tujuan,
            'Jam' => $request->jam,
            'Harga_VIP_Dewasa' => $request->harga_vip_dewasa,
            'Harga_VIP_Anak' => $request->harga_vip_anak,
            'Harga_Ekonomi_Dewasa' => $request->harga_ekonomi_dewasa,
            'Harga_Ekonomi_Anak' => $request->harga_ekonomi_anak,
        ]);

        // Redirect ke halaman invoice dengan membawa ID_Pesanan yang baru saja disimpan
        return redirect()->route('invoices.show', ['id' => $pesanan->ID_Pesanan])->with('success', 'Data penumpang berhasil disimpan');
    }

    public function index(Request $request)
    {
        $jenisKelaminOptions = JenisKelamin::getValues();
        $asal = $request->input('asal');
        $tujuan = $request->input('tujuan');
        $tanggal = $request->input('tanggal');
        $kapal = $request->input('kapal');
        $jam = $request->input('jam');
        $harga_vip_dewasa = $request->input('harga_vip_dewasa');
        $harga_vip_anak = $request->input('harga_vip_anak');
        $harga_ekonomi_dewasa = $request->input('harga_ekonomi_dewasa');
        $harga_ekonomi_anak = $request->input('harga_ekonomi_anak');

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
