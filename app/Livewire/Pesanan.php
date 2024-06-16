<?php

namespace App\Livewire;

use App\Models\Pesanan as ModelsPesanan;
use App\Models\Tiket as ModelsTiket;
use App\Models\Rute as ModelsRute;
use App\Models\User as ModelsUser;
use App\Models\Harga as ModelsHarga;
use Livewire\WithPagination;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Pesanan extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $ID_Rute;
    public $ID_User;
    public $ID_Tiket;
    public $Tanggal_Pesanan;
    public $Waktu;
    public $Total_Pesanan;

    public $search;
    public $updateData = false;
    public $dataRute;
    public $dataTiket;
    public $dataUser;
    
    public $pesananID;

    public function mount()
    {
        $this->dataTiket = ModelsTiket::all();
        $this->dataRute = ModelsRute::all();
        $this->dataUser = ModelsUser::all();
        $this->dataHarga = ModelsHarga::all();
    }

    public function store()
    {
        $rules = [
            'ID_Rute' => 'required',
            'ID_User' => 'required',
            'ID_Tiket' => 'required',
            'Tanggal_Pesanan' => 'required',
            'Waktu' => 'required',
            'Total_Pesanan' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'ID_User.required' => 'Nama User harus diisi.',
            'ID_Tiket.required' => 'Nama Tiket harus diisi.',
            'Tanggal_Pesanan.required' => 'Tanggal Pesanan harus diisi.',
            'Waktu.required' => 'Tanggal Pesanan harus diisi.',
            'Total_Pesanan.required' => 'Total Pesanan harus diisi.',
        ];

        $this->validate($rules, $pesan);
        ModelsPesanan::create($validated);
        $this->reset('ID_Rute', 'ID_User', 'ID_Tiket', 'Tanggal_Pesanan', 'Waktu', 'Total_Pesanan');
        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();

        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->resetPage();
        $this->clear();
    }

    public function delete($ID_Pesanan)
    {
        $pesanan = ModelsPesanan::find($ID_Pesanan);
        if ($pesanan) {
            $pesanan->delete();
            $this->alert('success', 'Data Berhasil Dihapus!');
        } else {
            $this->alert('error', 'Data Tidak Ditemukan!');
        }
    }

    public function edit($ID_Pesanan)
    {
        $pesanan = ModelsPesanan::find($ID_Pesanan);
        $this->ID_Rute = $pesanan->ID_Rute;
        $this->ID_User = $pesanan->ID_User;
        $this->ID_Tiket = $pesanan->ID_Tiket;
        $this->Tanggal_Pesanan = $pesanan->Tanggal_Pesanan;
        $this->Waktu = $pesanan->Waktu;
        $this->Total_Pesanan = $pesanan->Total_Pesanan;

        $this->updateData = true;
        $this->pesananID = $ID_Pesanan;
    }

    public function update()
    {
        $rules = [
            'ID_Rute' => 'required',
            'ID_User' => 'required',
            'ID_Tiket' => 'required',
            'Tanggal_Pesanan' => 'required',
            'Waktu' => 'required',
            'Total_Pesanan' => 'required',
        ];

        $pesan = [
            'ID_Rute.required' => 'Nama Rute harus diisi.',
            'ID_User.required' => 'Nama User harus diisi.',
            'ID_Tiket.required' => 'Nama Tiket harus diisi.',
            'Tanggal_Pesanan.required' => 'Tanggal Pesanan harus diisi.',
            'Waktu.required' => 'Waktu harus diisi.',
            'Total_Pesanan.required' => 'Total Pesanan harus diisi.',
        ];

        $validated = $this->validate($rules, $pesan);

        $pesanan = ModelsPesanan::find($this->pesananID);
        $pesanan->update($validated);
        $this->alert('success', 'Data Berhasil Diupdate!');

        $this->clear();
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->ID_Rute = '';
        $this->ID_User = '';
        $this->ID_Tiket = '';
        $this->Tanggal_Pesanan = '';
        $this->Waktu = '';
        $this->Total_Pesanan = '';

        $this->updateData = false;
        $this->pesananID = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function getRute($ID_Rute)
    {
        $rute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])->find($ID_Rute);
        return $rute ? "{$rute->pelabuhanAsal->Nama_Pelabuhan}, {$rute->pelabuhanAsal->Nama_Kota} - {$rute->pelabuhanTujuan->Nama_Pelabuhan}, {$rute->pelabuhanTujuan->Nama_Kota}" : 'Rute Tidak Ditemukan';
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
        $dataPesanan = ModelsPesanan::where('ID_Rute', 'like', $search)
            ->orWhere('ID_User', 'like', $search)
            ->orWhere('ID_Tiket', 'like', $search)
            ->orWhere('Tanggal_Pesanan', 'like', $search)
            ->orWhere('Waktu', 'like', $search)
            ->orWhere('Total_Pesanan', 'like', $search)
            ->orderBy('ID_Pesanan', 'desc')
            ->paginate(5);

        return view('livewire.pesanan', [
            'dataPesanan' => $dataPesanan,
            'dataRute' => $this->dataRute,
            'dataTiket' => $this->dataTiket,
            'dataUser' => $this->dataUser,
        ])->layout('components.adminPesanan');
    }
}
