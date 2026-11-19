<?php

namespace App\Livewire;

use App\Models\Kapal as ModelsKapal;
use App\Models\Rute as ModelsRute;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

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

    protected function rules(): array
    {
        return [
            'Nama_Kapal' => [
                'required', 'string', 'max:100',
                Rule::unique('kapal', 'Nama_Kapal')->ignore($this->kapalID, 'ID_Kapal'),
            ],
            'Kelas' => ['required', 'string', 'max:50'],
            'Jumlah_Kursi' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'Nama_Kapal.required' => 'Nama Kapal harus diisi.',
            'Nama_Kapal.max' => 'Nama Kapal maksimal 100 karakter.',
            'Nama_Kapal.unique' => 'Kapal dengan nama ini sudah ada.',
            'Kelas.required' => 'Kelas harus diisi.',
            'Kelas.max' => 'Kelas maksimal 50 karakter.',
            'Jumlah_Kursi.required' => 'Jumlah Kursi harus diisi.',
            'Jumlah_Kursi.integer' => 'Jumlah Kursi diisi angka bulat.',
            'Jumlah_Kursi.min' => 'Jumlah Kursi minimal 1.',
            'Jumlah_Kursi.max' => 'Jumlah Kursi maksimal 10.000.',
        ];
    }

    public function store()
    {
        ModelsKapal::create($this->validate());

        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->clear();
    }

    public function edit($ID_Kapal)
    {
        $data = ModelsKapal::find($ID_Kapal);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        $this->kapalID = $data->ID_Kapal;
        $this->Nama_Kapal = $data->Nama_Kapal;
        $this->Kelas = $data->Kelas;
        $this->Jumlah_Kursi = $data->Jumlah_Kursi;
        $this->updateData = true;
    }

    public function update()
    {
        $validated = $this->validate();

        $data = ModelsKapal::find($this->kapalID);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');
            $this->clear();

            return;
        }

        $data->update($validated);
        $this->alert('success', 'Data Berhasil Diupdate!');
        $this->clear();
    }

    public function delete($ID_Kapal)
    {
        $kapal = ModelsKapal::find($ID_Kapal);
        if (! $kapal) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        if (ModelsRute::where('ID_Kapal', $kapal->ID_Kapal)->exists()) {
            $this->alert('error', 'Kapal masih dipakai di data rute dan tidak bisa dihapus.');

            return;
        }

        $kapal->delete();
        $this->alert('success', 'Data Berhasil Dihapus!');
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->reset('Nama_Kapal', 'Kelas', 'Jumlah_Kursi', 'kapalID', 'updateData');
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
        $data = ModelsKapal::where('Nama_Kapal', 'like', $search)
            ->orWhere('Kelas', 'like', $search)
            ->orWhere('Jumlah_Kursi', 'like', $search)
            ->orderBy('ID_Kapal', 'desc')
            ->paginate(5);

        return view('livewire.kapal', ['dataKapal' => $data])
            ->layout('components.adminKapal');
    }
}
