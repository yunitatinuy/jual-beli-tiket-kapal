<?php

namespace App\Livewire;

use App\Models\Kapal as ModelsKapal;
use Livewire\WithPagination;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Kapal extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $namakapal;
    public $kelas;
    public $jumlahkursi;

    public $search;
    public $updateData = false;
    public $kapalID;

    public function store()
    {
        $rules = [
            'namakapal' => 'required',
            'kelas' => 'required',
            'jumlahkursi' => 'required|integer',
        ];

        $pesan = [
            'namakapal.required' => 'Nama Kapal harus diisi.',
            'kelas.required' => 'Kelas harus diisi.',
            'jumlahkursi.required' => 'Jumlah Kursi harus diisi.',
            'jumlahkursi.integer' => 'Jumlah Kursi diisi angka.',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsKapal::create($validated);
        $this->reset('namakapal', 'kelas', 'jumlahkursi');
        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
    }

    public function delete($idkapal)
    {
        $kapal = ModelsKapal::find($idkapal);
        if ($kapal) {
            $kapal->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($idkapal)
    {
        $data = ModelsKapal::find($idkapal);
        $this->namakapal = $data->namakapal;
        $this->kelas = $data->kelas;
        $this->jumlahkursi = $data->jumlahkursi;

        $this->updateData = true;
        $this->kapalID = $idkapal;
    }

    public function update()
    {
        $rules = [
            'namakapal' => 'required',
            'kelas' => 'required',
            'jumlahkursi' => 'required|integer',
        ];

        $pesan = [
            'namakapal.required' => 'Nama Kapal harus diisi.',
            'kelas.required' => 'Kelas harus diisi.',
            'jumlahkursi.required' => 'Jumlah Kursi harus diisi.',
            'jumlahkursi.integer' => 'Jumlah Kursi diisi angka.',
        ];

        $validated = $this->validate($rules, $pesan);

        $data = ModelsKapal::find($this->kapalID);
        $data->update($validated);
        $this->alert('success', 'Data Berhasil Diupdate!');

        $this->clear();
    }


    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->namakapal = '';
        $this->kelas = '';
        $this->jumlahkursi = '';

        $this->updateData = false;
        $this->kapalID = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        // $data = ModelsKapal::orderBy('idkapal', 'asc')->paginate(5);
        // return view('livewire.kapal', ['dataKapal' => $data]);
        $search = '%' . $this->search . '%';
        $data = ModelsKapal::where('namakapal', 'like', $search)
            ->orWhere('kelas', 'like', $search)
            ->orWhere('jumlahkursi', 'like', $search)
            ->orderBy('idkapal', 'desc')
            ->paginate(5);

        return view('livewire.kapal', ['dataKapal' => $data]);
    }
}
