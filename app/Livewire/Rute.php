<?php

namespace App\Livewire;

use App\Models\Kapal as ModelsKapal;
use App\Models\Pelabuhan as ModelsPelabuhan;
use App\Models\Pesanan as ModelsPesanan;
use App\Models\Rute as ModelsRute;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Rute extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedKapal;
    public $selectedPelabuhanAsal;
    public $selectedPelabuhanTujuan;
    public $tanggal;
    public $jam;

    public $search;
    public $updateData = false;
    public $ruteId;

    protected function rules(): array
    {
        return [
            'selectedKapal' => ['required', 'integer', 'exists:kapal,ID_Kapal'],
            'selectedPelabuhanAsal' => ['required', 'integer', 'exists:pelabuhan,ID_Pelabuhan'],
            'selectedPelabuhanTujuan' => ['required', 'integer', 'exists:pelabuhan,ID_Pelabuhan', 'different:selectedPelabuhanAsal'],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam' => ['required', 'date_format:H:i'],
        ];
    }

    protected function messages(): array
    {
        return [
            'selectedKapal.required' => 'Nama Kapal Harus Dipilih',
            'selectedKapal.exists' => 'Kapal tidak ditemukan',
            'selectedPelabuhanAsal.required' => 'Pelabuhan Asal Harus Dipilih',
            'selectedPelabuhanTujuan.required' => 'Pelabuhan Tujuan Harus Dipilih',
            'selectedPelabuhanTujuan.different' => 'Pelabuhan Tujuan harus berbeda dengan Pelabuhan Asal',
            'tanggal.required' => 'Tanggal Berangkat Harus Dipilih',
            'tanggal.date_format' => 'Format tanggal tidak valid',
            'tanggal.after_or_equal' => 'Tanggal Berangkat tidak boleh sebelum hari ini',
            'jam.required' => 'Jam Berangkat Harus Dipilih',
            'jam.date_format' => 'Format jam harus JJ:MM',
        ];
    }

    private function data(): array
    {
        return [
            'ID_Kapal' => (int) $this->selectedKapal,
            'Pelabuhan_Asal' => (int) $this->selectedPelabuhanAsal,
            'Pelabuhan_Tujuan' => (int) $this->selectedPelabuhanTujuan,
            'Tanggal' => $this->tanggal,
            'Jam' => $this->jam . ':00',
        ];
    }

    /** Jadwal hari ini yang jamnya sudah lewat juga ditolak. */
    private function cekBelumLewat(): bool
    {
        if (\Carbon\Carbon::parse($this->tanggal . ' ' . $this->jam)->isPast()) {
            $this->addError('jam', 'Waktu keberangkatan sudah lewat');

            return false;
        }

        return true;
    }

    public function store()
    {
        $this->validate();
        if (! $this->cekBelumLewat()) {
            return;
        }

        ModelsRute::create($this->data());

        $this->alert('success', 'Data berhasil ditambahkan');
        $this->clear();
    }

    public function edit($ID_Rute)
    {
        $rute = ModelsRute::find($ID_Rute);
        if (! $rute) {
            $this->alert('error', 'Data tidak ditemukan');

            return;
        }

        $this->ruteId = $rute->ID_Rute;
        $this->selectedKapal = $rute->ID_Kapal;
        $this->selectedPelabuhanAsal = $rute->Pelabuhan_Asal;
        $this->selectedPelabuhanTujuan = $rute->Pelabuhan_Tujuan;
        $this->tanggal = \Carbon\Carbon::parse($rute->Tanggal)->format('Y-m-d');
        $this->jam = substr((string) $rute->Jam, 0, 5);
        $this->updateData = true;
    }

    public function update()
    {
        $this->validate();
        if (! $this->cekBelumLewat()) {
            return;
        }

        $rute = ModelsRute::find($this->ruteId);
        if (! $rute) {
            $this->alert('error', 'Data tidak ditemukan');
            $this->clear();

            return;
        }

        // Rute yang sudah dipesan boleh diubah jadwalnya (tanggal/jam/kapal pengganti),
        // tetapi pelabuhan asal/tujuan tidak, karena pembeli membayar untuk lintasan itu.
        $gantiLintasan = (int) $rute->Pelabuhan_Asal !== (int) $this->selectedPelabuhanAsal
            || (int) $rute->Pelabuhan_Tujuan !== (int) $this->selectedPelabuhanTujuan;
        if ($gantiLintasan && $this->punyaPesananAktif($rute)) {
            $this->addError('selectedPelabuhanAsal', 'Rute ini sudah dipesan, pelabuhan asal/tujuan tidak bisa diganti.');

            return;
        }

        $rute->update($this->data());

        $this->alert('success', 'Data berhasil diupdate');
        $this->clear();
    }

    public function delete($ID_Rute)
    {
        $rute = ModelsRute::find($ID_Rute);
        if (! $rute) {
            $this->alert('error', 'Data Tidak Ditemukan!');

            return;
        }

        if ($rute->pesanans()->exists()) {
            $this->alert('error', 'Rute sudah memiliki pesanan dan tidak bisa dihapus.');

            return;
        }

        // Belum ada pesanan: harga & tiket milik rute ini ikut dihapus agar tidak jadi data yatim
        DB::transaction(function () use ($rute) {
            $rute->tiket()->delete();
            $rute->harga()->delete();
            $rute->delete();
        });

        $this->alert('success', 'Data Berhasil Dihapus!');
    }

    private function punyaPesananAktif(ModelsRute $rute): bool
    {
        return $rute->pesanans()
            ->whereIn('Status', [ModelsPesanan::STATUS_MENUNGGU_PEMBAYARAN, ModelsPesanan::STATUS_LUNAS])
            ->exists();
    }

    public function cancel()
    {
        $this->clear();
    }

    public function clear()
    {
        $this->reset('selectedKapal', 'selectedPelabuhanAsal', 'selectedPelabuhanTujuan', 'tanggal', 'jam', 'ruteId', 'updateData');
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
        $data = ModelsRute::with(['kapal', 'pelabuhanAsal', 'pelabuhanTujuan'])
            ->where(function ($q) use ($search) {
                $q->whereHas('kapal', fn ($k) => $k->where('Nama_Kapal', 'like', $search))
                    ->orWhereHas('pelabuhanAsal', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search))
                    ->orWhereHas('pelabuhanTujuan', fn ($p) => $p->where('Nama_Pelabuhan', 'like', $search))
                    ->orWhere('Tanggal', 'like', $search);
            })
            ->orderBy('Tanggal', 'desc')
            ->orderBy('Jam', 'desc')
            ->paginate(5);

        // Daftar pilihan diambil saat render (tidak disimpan di state Livewire,
        // supaya payload tiap klik tetap kecil)
        return view('livewire.rute', [
            'dataRute' => $data,
            'kapalList' => ModelsKapal::orderBy('Nama_Kapal')->get(['ID_Kapal', 'Nama_Kapal']),
            'pelabuhanList' => ModelsPelabuhan::orderBy('Nama_Pelabuhan')->get(['ID_Pelabuhan', 'Nama_Pelabuhan', 'Nama_Kota']),
        ])->layout('components.adminRute');
    }
}
