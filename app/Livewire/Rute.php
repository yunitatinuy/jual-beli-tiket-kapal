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
    public $kapalList;
    public $pelabuhanList;
    public $selectedKapal;
    public $selectedPelabuhanAsal;
    public $selectedPelabuhanTujuan;

    public $search;
    public $updateData = false;


    public function mount()
    {
        $this->rute = ModelsRute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])->get();
        $this->kapalList = ModelsKapal::all();
        $this->pelabuhanList = ModelsPelabuhan::all();
    }

    public function store()
    {
        $rules = [
            'ID_Kapal' => 'required',
            'Pelabuhan_Asal' => 'required',
            'Pelabuhan_Tujuan' => 'required',
            'Tanggal' => 'required',
            'Jam' => 'required',
        ];

        $pesan = [
            'ID_Kapal.required' => 'Nama Kapal Harus Dipilih',
            'Pelabuhan_Asal.required' => 'Pelabuhan Asal Harus Dipilih',
            'Pelabuhan_Tujuan.required' => 'Pelabuhan Tujuan Harus Dipilih',
            'Tanggal.required' => 'Tanggal Berangkat Harus Dipilih',
            'Jam.required' => 'Jam Berangkat Harus Dipilih',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsRute::create($validated, [
            'ID_Kapal' => $this->selectedKapal,
            'Pelabuhan_Asal' => $this->selectedPelabuhanAsal,
            'Pelabuhan_Tujuan' => $this->selectedPelabuhanTujuan,
            'Tanggal' => now(),  // Isi dengan nilai yang sesuai
            'Jam' => now(), // Isi dengan nilai yang sesuai
        ]);

        $this->alert('success', 'Data berhasil ditambahkan');
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsRute::where('ID_Kapal', 'like', $search)
            ->orWhere('Pelabuhan_Asal', 'like', $search)
            ->orderBy('Pelabuhan_Tujuan', 'desc')
            ->paginate(5);

        return view('livewire.rute', ['dataRute' => $data])
            ->layout('components.adminRute');
    }
}
