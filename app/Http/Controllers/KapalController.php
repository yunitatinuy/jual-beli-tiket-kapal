<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    public function kapal()
    {
    $kapals = Kapal::all();
    return view('/admin/kapal', ['kapals' => $kapals]);
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
        $data = Kapals::tambah($validation);
        if ($data) {
            session()->flash('Berhasil', 'Data Kapal Berhasil Ditambahkan');
            return redirect(route('kapal'));
        } else {
            session()->flash('Gagal', 'Terjadi Kesalahan');
            return redirect(route('tambah'));
        }
    }
    public function edit($idkapal)
    {
        $products = Product::findOrFail($idkapal);
        return view('update', compact('kapals'));
    }
 
    public function delete($id)
    {
        $products = Product::findOrFail($id)->delete();
        if ($products) {
            session()->flash('success', 'Product Deleted Successfully');
            return redirect(route('/admin/kapal'));
        } else {
            session()->flash('error', 'Product Not Delete successfully');
            return redirect(route('/admin/kapal'));
        }
    }
 
    public function update(Request $request, $idkapal)
    {
        $products = Product::findOrFail($idkapal);
        $namakapal = $request->namakapal;
        $kelas = $request->kelas;
        $jumlahkursi = $request->jumlahkursi;
 
        $kapals->namakapl = $namakapl;
        $kapals->kelas = $kelas;
        $kapals->jumlahkursi = $jumlahkursi;
        $data = $kapals->save();
        if ($data) {
            session()->flash('success', 'Product Update Successfully');
            return redirect(route('/admin/kapal'));
        } else {
            session()->flash('error', 'Some problem occure');
            return redirect(route('update'));
        }
    }
}
