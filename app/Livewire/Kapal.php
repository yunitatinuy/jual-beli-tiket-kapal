<?php

namespace App\Livewire;

use App\Models\Kapal as ModelsKapal;
use Livewire\WithPagination;
use Livewire\Component;

class Kapal extends Component
{
    use WithPagination;

    public $namakapal;
    public $kelas;
    public $jumlahkursi;

    public $search;

    public function store()
    {
        $rules = [
            'namakapal' => 'required',
            'kelas' => 'required',
            'jumlahkursi' => 'required|integer',
        ];

        $validated = $this->validate($rules);
        ModelsKapal::create($validated);
        $this->reset('namakapal', 'kelas', 'jumlahkursi');
        session()->flash('success', 'Data berhasil ditambahkan');
    }

    public function delete($idkapal)
    {
        $kapal = ModelsKapal::find($idkapal);
        if ($kapal) {
            $kapal->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } else {
            session()->flash('error', 'Data tidak ditemukan');
        }
    }

    public function render()
    {
        // $data = ModelsKapal::orderBy('idkapal', 'asc')->paginate(5);
        // return view('livewire.kapal', ['dataKapal' => $data]);
        $search = '%' . $this->search . '%';
        $data = ModelsKapal::where('namakapal', 'like', $search)
            ->orWhere('kelas', 'like', $search)
            ->orWhere('jumlahkursi', 'like', $search)
            ->orderBy('idkapal', 'asc')
            ->paginate(5);

        return view('livewire.kapal', ['dataKapal' => $data]);
    }
}
