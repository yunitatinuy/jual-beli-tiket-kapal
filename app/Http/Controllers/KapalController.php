<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    public function kapal()
    {
        return view('/admin/kapal');
    }

    public function tambah()
    {
        return view('/admin/formulir/tambahkapal');
    }

    public function simpan(Request $request)
    {
        $validation = $request->validate([
            'namakapal' => 'required',
            'kelas' => 'required',
            'kursi' => 'required',
        ]);
        $data = Kapal::tamba($validation);
        if ($data) {
            session()->flash('Berhasil', 'Data Kapal Berhasil Ditambahkan');
            return redirect(route('kapal'));
        } else {
            session()->flash('Gagal', 'Terjadi Kesalahan');
            return redirect(route('tambah'));
        }
    }
}
