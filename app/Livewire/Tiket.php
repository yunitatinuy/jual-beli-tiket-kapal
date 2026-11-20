<?php

namespace App\Livewire;

use App\Models\Harga as ModelsHarga;
use App\Models\Rute as ModelsRute;
use App\Models\Tiket as ModelsTiket;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Tiket extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $ID_Rute;
    public $ID_Harga;
    public $Jumlah_Tiket;

    public $search;
    public $updateData = false;
    public $tiketID;

    /** Tanggal untuk menyaring pilihan rute (dropdown hanya berisi jadwal pada tanggal ini). */
    public $tanggalRute;

    public function mount()
    {
        $this->tanggalRute = now()->toDateString();
    }

    public function updatedTanggalRute()
    {
        $this->ID_Rute = null;
        $this->ID_Harga = null;
    }

    protected function rules(): array
    {
        return [
            'ID_Rute' => ['required', 'integer', 'exists:rute,ID_Rute'],
            'ID_Harga' => [
                'required',
                'integer',
                // Harga harus milik rute yang dipilih
                Rule::exists('harga', 'ID_Harga')->where('ID_Rute', $this->ID_Rute),
                // Satu harga (tipe + kelas) hanya punya satu stok tiket
                Rule::unique('tiket', 'ID_Harga')->ignore($this->tiketID, 'ID_Tiket'),
            ],
            'Jumlah_Tiket' => ['required', 'integer', 'min:0', 'max:10000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'ID_Rute.required' => 'Rute harus dipilih.',
            'ID_Rute.exists' => 'Rute tidak ditemukan.',
            'ID_Harga.required' => 'Tipe penumpang & kelas harus dipilih.',
            'ID_Harga.exists' => 'Harga tidak cocok dengan rute yang dipilih.',
            'ID_Harga.unique' => 'Tiket untuk harga ini sudah ada. Ubah jumlahnya lewat tombol edit.',
            'Jumlah_Tiket.required' => 'Jumlah Tiket harus diisi.',
            'Jumlah_Tiket.integer' => 'Jumlah Tiket harus berupa angka bulat.',
            'Jumlah_Tiket.min' => 'Jumlah Tiket tidak boleh negatif.',
            'Jumlah_Tiket.max' => 'Jumlah Tiket maksimal 10.000.',
        ];
    }

    /** Ganti rute = pilihan harga berubah, jadi pilihan lama dikosongkan. */
    public function updatedIDRute()
    {
        $this->ID_Harga = null;
        $this->resetValidation('ID_Harga');
    }

    private function data(): array
    {
        return [
            'ID_Rute' => (int) $this->ID_Rute,
            'ID_Harga' => (int) $this->ID_Harga,
            'Jumlah_Tiket' => (int) $this->Jumlah_Tiket,
        ];
    }

    public function store()
    {
        $this->validate();

        ModelsTiket::create($this->data());

        $this->alert('success', 'Data Berhasil Ditambahkan!');
        $this->clear();
    }

    public function edit($ID_Tiket)
    {
        $data = ModelsTiket::find($ID_Tiket);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        $this->tiketID = $data->ID_Tiket;
        $this->ID_Rute = $data->ID_Rute;
        $this->tanggalRute = $data->rute ? \Carbon\Carbon::parse($data->rute->Tanggal)->toDateString() : $this->tanggalRute;
        $this->ID_Harga = $data->ID_Harga;
        $this->Jumlah_Tiket = $data->Jumlah_Tiket;
        $this->updateData = true;
    }

    public function update()
    {
        $this->validate();

        $data = ModelsTiket::find($this->tiketID);
        if (! $data) {
            $this->alert('error', 'Data Tidak Ditemukan!');
            $this->clear();

            return;
        }

        $pindah = (int) $data->ID_Rute !== (int) $this->ID_Rute || (int) $data->ID_Harga !== (int) $this->ID_Harga;
        if ($pindah && $data->penumpangs()->exists()) {
            $this->addError('ID_Harga', 'Tiket ini sudah dipesan, rute/harganya tidak bisa diganti. Hanya jumlah tiket yang bisa diubah.');

            return;
        }

        $data->update($this->data());

        $this->alert('success', 'Data Berhasil Diupdate!');
        $this->clear();
    }

    public function delete($ID_Tiket)
    {
        $tiket = ModelsTiket::find($ID_Tiket);
        if (! $tiket) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        if ($tiket->penumpangs()->exists()) {
            $this->alert('error', 'Tiket sudah pernah dipesan dan tidak bisa dihapus. Ubah jumlahnya menjadi 0 untuk menutup penjualan.');

            return;
        }

        $tiket->delete();
        $this->alert('success', 'Data Berhasil Dihapus!');
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->reset('ID_Rute', 'ID_Harga', 'Jumlah_Tiket', 'tiketID', 'updateData');
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
        $dataTiket = ModelsTiket::with(['rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'harga'])
            ->where(function ($q) use ($search) {
                $q->whereHas('rute.pelabuhanAsal', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search))
                    ->orWhereHas('rute.pelabuhanTujuan', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search))
                    ->orWhere('Jumlah_Tiket', 'like', $search)
                    ->orWhereHas('harga', fn ($h) => $h->where('Harga', 'like', $search)->orWhere('Kelas', 'like', $search));
            })
            ->orderBy('ID_Tiket', 'desc')
            ->paginate(5);

        // Disaring per tanggal supaya dropdown tidak berisi ribuan jadwal hasil generator
        $dataRute = ModelsRute::with(['pelabuhanAsal', 'pelabuhanTujuan'])
            ->where(fn ($q) => $q->where('Tanggal', $this->tanggalRute ?: now()->toDateString())
                ->when($this->ID_Rute, fn ($w) => $w->orWhere('ID_Rute', $this->ID_Rute)))
            ->orderBy('Tanggal')->orderBy('Jam')
            ->get();

        // Hanya harga milik rute terpilih yang belum punya tiket (kecuali yang sedang diedit)
        $dataHarga = $this->ID_Rute
            ? ModelsHarga::where('ID_Rute', $this->ID_Rute)
                ->where(fn ($q) => $q->whereDoesntHave('tikets')->when($this->ID_Harga, fn ($w) => $w->orWhere('ID_Harga', $this->ID_Harga)))
                ->orderBy('Kelas')->orderBy('Tipe_Penumpang')
                ->get()
            : collect();

        return view('livewire.tiket', [
            'dataTiket' => $dataTiket,
            'dataRute' => $dataRute,
            'dataHarga' => $dataHarga,
        ])->layout('components.adminTiket');
    }
}
