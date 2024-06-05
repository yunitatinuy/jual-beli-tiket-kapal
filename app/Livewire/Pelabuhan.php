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

    public $namapelabuhan;
    public $namakapal;

    public $search;
    public $updateData = false;
    public $pelabuhanID;

    public function store()
    {
        $rules = [
            'namapelabuhan' => 'required',
            'namakapal' => 'required',
        ];

        $pesan = [
            'namapelabuhan.required' => 'Nama pelabuhan harus diisi',
            'namakapal.required' => 'Nama kapal harus diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsPelabuhan::create($validated);
        $this->reset('namapelabuhan', 'namakapal');
        $this->alert('success', 'Data berhasil ditambahkan');
        $this->resetPage();
    }

    public function delete($idpelabuhan)
    {
        $pelabuhan = ModelsPelabuhan::find($idpelabuhan);
        if ($pelabuhan) {
            $pelabuhan->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($idpelabuhan)
    {
        $data = ModelsPelabuhan::find($idpelabuhan);
        $this->namapelabuhan = $data->namapelabuhan;
        $this->namakapal = $data->namakapal;

        $this->updateData = true;
        $this->pelabuhanID = $idpelabuhan;
    }

    public function update()
    {
        $rules = [
            'namapelabuhan' => 'required',
            'namakapal' => 'required',
        ];

        $pesan = [
            'namapelabuhan.required' => 'Nama pelabuhan harus diisi',
            'namakapal.required' => 'Nama kapal harus diisi',
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
        $this->namapelabuhan = '';
        $this->namakapal = '';

        $this->updateData = false;
        $this->pelabuhanID = '';
        $this->resetValidation();
        $this->resetpage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = Modelspelabuhan::where('namapelabuhan', 'like', $search)
            ->orWhere('namakapal', 'like', $search)
            ->orderBy('idpelabuhan', 'desc')
            ->paginate(5);

        return view('livewire.pelabuhan', ['dataPelabuhan' => $data])
            ->layout('components.adminPelabuhan');
    }
}
