<?php

namespace App\Livewire;

use App\Models\Rute as ModelsRute;
use App\Models\Harga as ModelsHarga;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Harga extends Component
{

    use WithPagination;
    use LivewireAlert;

    public $ID_Rute;
    public $Tipe_Penumpang;
    public $Kelas;
    public $Harga;

    public $search;
    public $updateData = false;
    public $dataRute;

    public $hargaId;

    public function mount()
    {
        $this->dataRute = ModelsRute::all();
    }

    public function store()
    {
        $rules = [
            'ID_Rute' => 'required',
            'Tipe_Penumpang' => 'required|in:dewasa,anak',
            'Kelas' => 'required',
            'Harga' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Rute harus dipilih',
            'Tipe_Penumpang.required' => 'Tipe Penumpang harus dipilih',
            'Tipe_Penumpang.in' => 'Tipe Penumpang harus dewasa atau anak',
            'Kelas.required' => 'Kelas harus diisi',
            'Harga.required' => 'Harga harus diisi',
            'Harga.numeric' => 'Harga harus berupa angka',
        ];

        $this->validate($rules, $pesan);

        ModelsHarga::create([
            'ID_Rute' => $this->ID_Rute,
            'Tipe_Penumpang' => $this->Tipe_Penumpang,
            'Kelas' => $this->Kelas,
            'Harga' => $this->Harga,
        ]);

        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
        $this->clear();
    }

    public function delete($ID_Harga)
    {
        $harga = ModelsHarga::find($ID_Harga);
        if ($harga) {
            $harga->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($ID_Harga)
    {
        $harga = ModelsHarga::find($ID_Harga);
        $this->hargaId = $harga->ID_Harga;
        $this->ID_Rute = $harga->ID_Rute;
        $this->Tipe_Penumpang = $harga->Tipe_Penumpang;
        $this->Kelas = $harga->Kelas;
        $this->Harga = $harga->Harga;

        $this->updateData = true;
    }

    public function update()
    {
        $rules = [
            'ID_Rute' => 'required',
            'Tipe_Penumpang' => 'required|in:dewasa,anak',
            'Kelas' => 'required',
            'Harga' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Rute harus dipilih',
            'Tipe_Penumpang.required' => 'Tipe Penumpang harus dipilih',
            'Tipe_Penumpang.in' => 'Tipe Penumpang harus dewasa atau anak',
            'Kelas.required' => 'Kelas harus diisi',
            'Harga.required' => 'Harga harus diisi',
            'Harga.integer' => 'Harga harus berupa angka',
        ];

        $this->validate($rules, $pesan);

        $harga = ModelsHarga::find($this->hargaId);
        if ($harga) {
            $harga->update([
                'ID_Rute' => $this->ID_Rute,
                'Tipe_Penumpang' => $this->Tipe_Penumpang,
                'Kelas' => $this->Kelas,
                'Harga' => $this->Harga,
            ]);

            $this->alert('success', 'Data Berhasil Diupdate!');
            $this->updateData = false;
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
        $this->clear();
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->ID_Rute = null;
        $this->Tipe_Penumpang = null;
        $this->Kelas = null;
        $this->Harga = null;
        $this->hargaId = null;
        $this->updateData = false;
        $this->resetValidation();
        $this->resetPage();
    }

    public function getRute($ID_Rute)
    {
        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($ID_Rute);
        return $rute ? "{$rute->pelabuhanAsal->Nama_Pelabuhan}, {$rute->pelabuhanAsal->Nama_Kota} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}, {$rute->pelabuhanTujuan->Nama_Kota}" : 'Rute Tidak Ditemukan';
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsHarga::with('rute')
            ->whereHas('rute', function ($query) use ($search) {
                $query->where('ID_Rute', 'like', $search);
            })
            ->orderBy('ID_Harga', 'desc')
            ->paginate(5);

        return view('livewire.harga', [
            'dataHarga' => $data,
            'dataRute' => $this->dataRute
        ])
            ->layout('components.adminHarga');
    }
}
