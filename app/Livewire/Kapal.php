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

    public $Nama_Kapal;
    public $Kelas;
    public $Jumlah_Kursi;

    public $search;
    public $updateData = false;
    public $kapalID;

    public function store()
    {
        $rules = [
            'Nama_Kapal' => 'required',
            'Kelas' => 'required',
            'Jumlah_Kursi' => 'required|integer',
        ];

        $pesan = [
            'Nama_Kapal.required' => 'Nama Kapal harus diisi.',
            'Kelas.required' => 'Kelas harus diisi.',
            'Jumlah_Kursi.required' => 'Jumlah Kursi harus diisi.',
            'Jumlah_Kursi.integer' => 'Jumlah Kursi diisi angka.',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsKapal::create($validated);
        $this->reset('Nama_Kapal', 'Kelas', 'Jumlah_Kursi');
        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
    }

    public function delete($ID_Kapal)
    {
        $kapal = ModelsKapal::find($ID_Kapal);
        if ($kapal) {
            $kapal->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($ID_Kapal)
    {
        $data = ModelsKapal::find($ID_Kapal);
        $this->Nama_Kapal = $data->Nama_Kapal;
        $this->Kelas = $data->Kelas;
        $this->Jumlah_Kursi = $data->Jumlah_Kursi;

        $this->updateData = true;
        $this->kapalID = $ID_Kapal;
    }

    public function update()
    {
        $rules = [
            'Nama_Kapal' => 'required',
            'Kelas' => 'required',
            'Jumlah_Kursi' => 'required|integer',
        ];

        $pesan = [
            'Nama_Kapal.required' => 'Nama Kapal harus diisi.',
            'Kelas.required' => 'Kelas harus diisi.',
            'Jumlah_Kursi.required' => 'Jumlah Kursi harus diisi.',
            'Jumlah_Kursi.integer' => 'Jumlah Kursi diisi angka.',
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
        $this->Nama_Kapal = '';
        $this->Kelas = '';
        $this->Jumlah_Kursi = '';

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
        $data = ModelsKapal::where('Nama_Kapal', 'like', $search)
            ->orWhere('Kelas', 'like', $search)
            ->orWhere('Jumlah_Kursi', 'like', $search)
            ->orderBy('ID_Kapal', 'desc')
            ->paginate(5);

        return view('livewire.kapal', ['dataKapal' => $data])
        ->layout('components.adminKapal');
    }
}
