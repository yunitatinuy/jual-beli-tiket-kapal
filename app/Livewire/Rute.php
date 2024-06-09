<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rute as ModelsRute;
use App\Models\Kapal as ModelsKapal;
use App\Models\Pelabuhan as ModelsPelabuhan;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Rute extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $rute;
    public $ID_Rute;
    public $kapalList;
    public $pelabuhanList;
    public $selectedKapal;
    public $selectedPelabuhanAsal;
    public $selectedPelabuhanTujuan;
    public $tanggal;
    public $jam;

    public $search;
    public $updateData = false;
    public $ruteId;

    public function mount()
    {
        $this->kapalList = ModelsKapal::all();
        $this->pelabuhanList = ModelsPelabuhan::all();
    }

    public function store()
    {
        $rules = [
            'selectedKapal' => 'required',
            'selectedPelabuhanAsal' => 'required',
            'selectedPelabuhanTujuan' => 'required',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
        ];

        $pesan = [
            'selectedKapal.required' => 'Nama Kapal Harus Dipilih',
            'selectedPelabuhanAsal.required' => 'Pelabuhan Asal Harus Dipilih',
            'selectedPelabuhanTujuan.required' => 'Pelabuhan Tujuan Harus Dipilih',
            'tanggal.required' => 'Tanggal Berangkat Harus Dipilih',
            'jam.required' => 'Jam Berangkat Harus Dipilih',
        ];

        $this->validate($rules, $pesan);

        ModelsRute::create([
            'ID_Kapal' => $this->selectedKapal,
            'Pelabuhan_Asal' => $this->selectedPelabuhanAsal,
            'Pelabuhan_Tujuan' => $this->selectedPelabuhanTujuan,
            'Tanggal' => $this->tanggal,
            'Jam' => $this->jam,
        ]);

        $this->alert('success', 'Data berhasil ditambahkan');
        $this->resetPage();
        $this->clear();
    }

    public function delete($ID_Rute)
    {
        $rute = ModelsRute::find($ID_Rute);
        if ($rute) {
            $rute->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($ID_Rute)
    {
        $rute = ModelsRute::find($ID_Rute);
        $this->ruteId = $rute->ID_Rute;
        $this->selectedKapal = $rute->ID_Kapal;
        $this->selectedPelabuhanAsal = $rute->Pelabuhan_Asal;
        $this->selectedPelabuhanTujuan = $rute->Pelabuhan_Tujuan;
        $this->tanggal = $rute->Tanggal;
        $this->jam = $rute->Jam;

        $this->updateData = true;
    }

    public function update()
    {
        $rules = [
            'selectedKapal' => 'required',
            'selectedPelabuhanAsal' => 'required',
            'selectedPelabuhanTujuan' => 'required',
            'tanggal' => 'required|date',
            'jam' => 'required',
        ];

        $pesan = [
            'selectedKapal.required' => 'Nama Kapal Harus Dipilih',
            'selectedPelabuhanAsal.required' => 'Pelabuhan Asal Harus Dipilih',
            'selectedPelabuhanTujuan.required' => 'Pelabuhan Tujuan Harus Dipilih',
            'tanggal.required' => 'Tanggal Berangkat Harus Dipilih',
            'jam.required' => 'Jam Berangkat Harus Dipilih',
        ];

        $this->validate($rules, $pesan);

        $rute = ModelsRute::find($this->ruteId);
        if ($rute) {
            $rute->update([
                'ID_Kapal' => $this->selectedKapal,
                'Pelabuhan_Asal' => $this->selectedPelabuhanAsal,
                'Pelabuhan_Tujuan' => $this->selectedPelabuhanTujuan,
                'Tanggal' => $this->tanggal,
                'Jam' => $this->jam,
            ]);

            $this->alert('success', 'Data berhasil diupdate');

            $this->updateData = false;
        } else {
            $this->alert('error', 'Data tidak ditemukan');
        }
        $this->clear();
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->selectedKapal = null;
        $this->selectedPelabuhanAsal = null;
        $this->selectedPelabuhanTujuan = null;
        $this->tanggal = null;
        $this->jam = null;
        $this->ruteId = null;
        $this->updateData = false;
        $this->resetValidation();
        $this->resetpage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsRute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->whereHas('kapal', function ($query) use ($search) {
                $query->where('Nama_Kapal', 'like', $search);
            })
            ->orWhereHas('pelabuhanAsal', function ($query) use ($search) {
                $query->where('Nama_Pelabuhan', 'like', $search);
            })
            ->orWhereHas('pelabuhanTujuan', function ($query) use ($search) {
                $query->where('Nama_Pelabuhan', 'like', $search);
            })
            ->orderBy('ID_Rute', 'desc')
            ->paginate(5);

        return view('livewire.rute', ['dataRute' => $data])
            ->layout('components.adminRute');
    }
}
