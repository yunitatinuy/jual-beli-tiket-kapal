<?php

namespace App\Livewire;

use App\Models\Tiket as ModelsTiket;
use App\Models\Rute as ModelsRute;
use Livewire\WithPagination;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Tiket extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $ID_Rute;
    public $Jumlah_Tiket;
    public $Harga_Reguler;
    public $Harga_VIP;

    public $search;
    public $updateData = false;
    public $tiketID;

    public $routeInfo;

    public function store()
    {
        $rules = [
            'ID_Rute' => 'required',
            'Jumlah_Tiket' => 'required',
            'Harga_Reguler' => 'required',
            'Harga_VIP' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'Jumlah_Tiket.required' => 'Jumlah Tiket harus diisi.',
            'Harga_Reguler.required' => 'Harga Reguler harus diisi.',
            'Harga_Reguler.numeric' => 'Harga Reguler harus diisi.',
            'Harga_Reguler.decimal' => 'Harga Reguler harus diisi.',
            'Harga_VIP.required' => 'Harga VIP harus diisi.',
            'Harga_VIP.numeric' => 'Harga VIP harus diisi.',
            'Harga_VIP.decimal' => 'Harga VIP harus diisi.',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsTiket::create($validated);
        $this->reset('ID_Rute', 'Jumlah_Tiket', 'Harga_Reguler', 'Harga_VIP');
        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
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

    public function edit($ID_Tiket)
    {
        $data = ModelsTiket::find($ID_Tiket);
        $this->ID_Rute = $data->ID_Rute;
        $this->Jumlah_Tiket = $data->Jumlah_Tiket;
        $this->Harga_Reguler = $data->Harga_Reguler;
        $this->Harga_VIP = $data->Harga_VIP;

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
            'Jumlah_Tiket' => 'required',
            'Harga_Reguler' => 'required',
            'Harga_VIP' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'Jumlah_Tiket.required' => 'Jumlah Tiket harus diisi.',
            'Harga_Reguler.required' => 'Harga Reguler harus diisi.',
            'Harga_Reguler.numeric' => 'Harga Reguler harus diisi.',
            'Harga_Reguler.decimal' => 'Harga Reguler harus diisi.',
            'Harga_VIP.required' => 'Harga VIP harus diisi.',
            'Harga_VIP.numeric' => 'Harga VIP harus diisi.',
            'Harga_VIP.decimal' => 'Harga VIP harus diisi.',
        ];

        $validated = $this->validate($rules, $pesan);

        $data = ModelsTiket::find($this->tiketID);
        $data->update($validated);
        $this->alert('success', 'Data Berhasil Diupdate!');

        $this->clear();
    }

    public function updatedID_Rute($value)
    {
        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($value);
        if ($rute) {
            $this->routeInfo = "{$rute->pelabuhanAsal->Nama_Pelabuhan} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}";
        } else {
            $this->routeInfo = 'Rute Tidak Ditemukan';
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
        $this->Harga_Reguler = '';
        $this->Harga_VIP = '';

        $this->routeInfo = null;

        $this->updateData = false;
        $this->tiketID = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $dataTiket = ModelsTiket::whereHas('rute', function ($query) use ($search) {
            $query->whereHas('pelabuhanAsal', function ($q) use ($search) {
                $q->where('Nama_Pelabuhan', 'like', $search);
            })->orWhereHas('pelabuhanTujuan', function ($q) use ($search) {
                $q->where('Nama_Pelabuhan', 'like', $search);
            });
        })->orWhere('Jumlah_Tiket', 'like', $search)
            ->orWhere('Harga_Reguler', 'like', $search)
            ->orWhere('Harga_VIP', 'like', $search)
            ->orderBy('ID_Tiket', 'desc')
            ->paginate(5);

        $dataRute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->get();

        return view('livewire.tiket', [
            'dataTiket' => $dataTiket,
            'dataRute' => $dataRute,
        ])->layout('components.adminTiket');
    }

    public function getRute($ID_Rute)
    {
        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($ID_Rute);
        return $rute ? "{$rute->pelabuhanAsal->Nama_Pelabuhan}, {$rute->pelabuhanAsal->Nama_Kota} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}, {$rute->pelabuhanTujuan->Nama_Kota}" : 'Rute Tidak Ditemukan';
    }
}
