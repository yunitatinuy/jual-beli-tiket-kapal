<?php

namespace App\Livewire;

use App\Models\Pelabuhan as ModelsPelabuhan;
use App\Models\Rute as ModelsRute;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Pelabuhan extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $Nama_Pelabuhan;
    public $Nama_Kota;

    public $search;
    public $updateData = false;
    public $pelabuhanID;

    protected function rules(): array
    {
        return [
            'Nama_Pelabuhan' => [
                'required', 'string', 'max:100',
                Rule::unique('pelabuhan', 'Nama_Pelabuhan')->ignore($this->pelabuhanID, 'ID_Pelabuhan'),
            ],
            'Nama_Kota' => ['required', 'string', 'max:100'],
        ];
    }

    protected function messages(): array
    {
        return [
            'Nama_Pelabuhan.required' => 'Nama Pelabuhan harus diisi.',
            'Nama_Pelabuhan.max' => 'Nama Pelabuhan maksimal 100 karakter.',
            'Nama_Pelabuhan.unique' => 'Pelabuhan dengan nama ini sudah ada.',
            'Nama_Kota.required' => 'Nama Kota harus diisi.',
            'Nama_Kota.max' => 'Nama Kota maksimal 100 karakter.',
        ];
    }

    public function store()
    {
        ModelsPelabuhan::create($this->validate());

        $this->alert('success', 'Data berhasil ditambahkan');
        $this->clear();
    }

    public function edit($ID_Pelabuhan)
    {
        $data = ModelsPelabuhan::find($ID_Pelabuhan);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        $this->pelabuhanID = $data->ID_Pelabuhan;
        $this->Nama_Pelabuhan = $data->Nama_Pelabuhan;
        $this->Nama_Kota = $data->Nama_Kota;
        $this->updateData = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $data = ModelsPelabuhan::find($this->pelabuhanID);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');
            $this->clear();

            return;
        }

        $data->update($validated);
        $this->alert('success', 'Data berhasil Diupdate');
        $this->clear();
    }

    public function delete($ID_Pelabuhan)
    {
        $pelabuhan = ModelsPelabuhan::find($ID_Pelabuhan);
        if (! $pelabuhan) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        $dipakai = ModelsRute::where('Pelabuhan_Asal', $pelabuhan->ID_Pelabuhan)
            ->orWhere('Pelabuhan_Tujuan', $pelabuhan->ID_Pelabuhan)
            ->exists();
        if ($dipakai) {
            $this->alert('error', 'Pelabuhan masih dipakai di data rute dan tidak bisa dihapus.');

            return;
        }

        $pelabuhan->delete();
        $this->alert('success', 'Data Berhasil Dihapus!');
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->reset('Nama_Pelabuhan', 'Nama_Kota', 'pelabuhanID', 'updateData');
        $this->resetValidation();
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsPelabuhan::where('Nama_Pelabuhan', 'like', $search)
            ->orWhere('Nama_Kota', 'like', $search)
            ->orderBy('ID_Pelabuhan', 'desc')
            ->paginate(5);

        return view('livewire.pelabuhan', ['dataPelabuhan' => $data])
            ->layout('components.adminPelabuhan');
    }
}
