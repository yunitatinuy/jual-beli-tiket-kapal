<?php

namespace App\Livewire;

use App\Models\Tiket as ModelsTiket;
use App\Models\Rute as ModelsRute;
use App\Models\Harga as ModelsHarga;
use Livewire\WithPagination;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Tiket extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $ID_Tiket;
    public $ID_Rute;
    public $Jumlah_Tiket;
    public $ID_Harga;

    public $search;
    public $updateData = false;
    public $tiketID;

    public $dataHarga;

    public $routeInfo;

    public function mount()
    {
        $this->dataHarga = ModelsHarga::all();
    }

    public function updatedID_Rute($value)
    {
        $this->dataHarga = ModelsHarga::where('ID_Rute', $value)->get();
        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($value);
        if ($rute) {
            $this->routeInfo = "{$rute->pelabuhanAsal->Nama_Pelabuhan} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}";
        } else {
            $this->routeInfo = 'Rute Tidak Ditemukan';
        }
    }

    public function store()
    {
        $rules = [
            'ID_Rute' => 'required',
            'ID_Harga' => 'required',
            'Jumlah_Tiket' => 'required|integer',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'ID_Harga.required' => 'Tipe Penumpang harus diisi.',
            'Jumlah_Tiket.required' => 'Jumlah Tiket harus diisi.',
            'Jumlah_Tiket.integer' => 'Jumlah Tiket harus berupa angka.',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsTiket::create($validated);
        $this->reset('ID_Rute', 'ID_Harga', 'Jumlah_Tiket');
        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
    }

    public function edit($ID_Tiket)
    {
        $data = ModelsTiket::find($ID_Tiket);
        $this->ID_Rute = $data->ID_Rute;
        $this->Jumlah_Tiket = $data->Jumlah_Tiket;
        $this->ID_Harga = $data->ID_Harga;

        $this->updateData = true;
        $this->tiketID = $ID_Tiket;

        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($this->ID_Rute);
        if ($rute) {
            $this->routeInfo = "{$rute->pelabuhanAsal->Nama_Pelabuhan} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}";
        }
    }

    public function update()
    {
        $rules = [
            'ID_Rute' => 'required',
            'ID_Harga' => 'required',
            'Jumlah_Tiket' => 'required|integer',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'ID_Harga.required' => 'Tipe Penumpang harus diisi.',
            'Jumlah_Tiket.required' => 'Jumlah Tiket harus diisi.',
            'Jumlah_Tiket.integer' => 'Jumlah Tiket harus berupa angka.',
        ];

        $validated = $this->validate($rules, $pesan);

        $data = ModelsTiket::find($this->tiketID);
        $data->update($validated);
        $this->alert('success', 'Data Berhasil Diupdate!');

        $this->clear();
    }

    public function delete($ID_Tiket)
    {
        $tiket = ModelsTiket::find($ID_Tiket);
        if ($tiket) {
            $tiket->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->ID_Rute = '';
        $this->Jumlah_Tiket = '';
        $this->ID_Harga = null;

        $this->routeInfo = null;

        $this->updateData = false;
        $this->tiketID = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $dataTiket = ModelsTiket::with(['rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'harga'])
            ->whereHas('rute', function ($query) use ($search) {
                $query->whereHas('pelabuhanAsal', function ($q) use ($search) {
                    $q->where('Nama_Pelabuhan', 'like', $search);
                })->orWhereHas('pelabuhanTujuan', function ($q) use ($search) {
                    $q->where('Nama_Pelabuhan', 'like', $search);
                });
            })
            ->orWhere('Jumlah_Tiket', 'like', $search)
            ->orWhereHas('harga', function ($query) use ($search) {
                $query->where('Harga', 'like', $search);
            })
            ->orderBy('ID_Tiket', 'desc')
            ->paginate(5);

        $dataRute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->get();

        // Initialize $dataHarga
        $dataHarga = $this->dataHarga ?: [];

        return view('livewire.tiket', [
            'dataTiket' => $dataTiket,
            'dataRute' => $dataRute,
            'dataHarga' => $dataHarga,
        ])->layout('components.adminTiket');
    }
}
