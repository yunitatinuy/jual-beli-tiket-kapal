<?php

namespace App\Livewire;

use App\Models\Pelabuhan as ModelsPelabuhan;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Pelabuhan extends Component
{

    use WithPagination;
    use LivewireAlert;

    public $Nama_Pelabuhan;
    public $Nama_Kota;

    public $search;
    public $updateData = false;
    public $pelabuhanID;

    public function store()
    {
        $rules = [
            'Nama_Pelabuhan' => 'required',
            'Nama_Kota' => 'required',
        ];

        $pesan = [
            'Nama_Pelabuhan.required' => 'Nama Pelabuhan harus diisi',
            'Nama_Kota.required' => 'Nama Kota harus diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsPelabuhan::create($validated);
        $this->reset('Nama_Pelabuhan', 'Nama_Kota');
        $this->alert('success', 'Data berhasil ditambahkan');
        $this->resetPage();
    }

    public function delete($ID_Pelabuhan)
    {
        $pelabuhan = ModelsPelabuhan::find($ID_Pelabuhan);
        if ($pelabuhan) {
            $pelabuhan->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($ID_Pelabuhan)
    {
        $data = ModelsPelabuhan::find($ID_Pelabuhan);
        $this->Nama_Pelabuhan = $data->Nama_Pelabuhan;
        $this->Nama_Kota = $data->Nama_Kota;

        $this->updateData = true;
        $this->pelabuhanID = $ID_Pelabuhan;
    }

    public function update()
    {
        $rules = [
            'Nama_Pelabuhan' => 'required',
            'Nama_Kota' => 'required',
        ];

        $pesan = [
            'Nama_Pelabuhan.required' => 'Nama Pelabuhan harus diisi',
            'Nama_Kota.required' => 'Nama Kota harus diisi',
        ];

        $validated = $this->validate($rules, $pesan);

        $data = ModelsPelabuhan::find($this->pelabuhanID);
        $data->update($validated);
        $this->alert('success', 'Data berhasil Diupdate');

        $this->clear();
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->Nama_Pelabuhan = '';
        $this->Nama_Kota = '';

        $this->updateData = false;
        $this->pelabuhanID = '';
        $this->resetValidation();
        $this->resetpage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = Modelspelabuhan::where('Nama_Pelabuhan', 'like', $search)
            ->orWhere('Nama_Kota', 'like', $search)
            ->orderBy('ID_Pelabuhan', 'desc')
            ->paginate(5);

        return view('livewire.pelabuhan', ['dataPelabuhan' => $data])
            ->layout('components.adminPelabuhan');
    }
}
