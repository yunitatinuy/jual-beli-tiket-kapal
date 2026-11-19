<?php

namespace App\Livewire;

use App\Models\Pesanan as ModelsPesanan;
use App\Services\BookingService;
use App\Services\PaymentService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/** Daftar pesanan untuk admin: filter, pencarian, detail penumpang (untuk check-in). */
class Pesanan extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public string $status = '';

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $tanggal = '';

    public ?int $detailId = null;

    public function updating($name)
    {
        if (in_array($name, ['status', 'search', 'tanggal'], true)) {
            $this->resetPage();
        }
    }

    public function lihat(int $id)
    {
        $this->detailId = $id;
    }

    public function tutup()
    {
        $this->detailId = null;
    }

    public function resetFilter()
    {
        $this->reset('status', 'search', 'tanggal');
        $this->resetPage();
    }

    /** Admin membatalkan pesanan yang belum dibayar (mis. atas permintaan pembeli). */
    public function batalkan(int $id, PaymentService $payment, BookingService $booking)
    {
        $pesanan = ModelsPesanan::find($id);
        if (! $pesanan) {
            $this->alert('error', 'Pesanan tidak ditemukan.');

            return;
        }

        // Pastikan belum terbayar di Midtrans sebelum membatalkan
        $pesanan = $payment->syncStatus($pesanan);

        if ($booking->cancel($pesanan, 'Dibatalkan oleh admin')) {
            $this->alert('success', 'Pesanan dibatalkan dan kursi dikembalikan.');
        } else {
            $this->alert('error', 'Pesanan ini tidak bisa dibatalkan (sudah lunas atau sudah batal).');
        }
    }

    public function render()
    {
        $search = trim($this->search);

        $query = ModelsPesanan::with(['user', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'rute.kapal'])
            ->withCount('penumpangs')
            ->when(array_key_exists($this->status, ModelsPesanan::STATUS_LABEL), fn ($q) => $q->where('Status', $this->status))
            ->when($this->tanggal !== '', fn ($q) => $q->whereHas('rute', fn ($r) => $r->where('Tanggal', $this->tanggal)))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(fn ($w) => $w->where('Kode_Booking', 'like', $like)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like))
                    ->orWhereHas('penumpangs', fn ($p) => $p->where('Nama_Lengkap', 'like', $like)));
            })
            ->orderByDesc('ID_Pesanan');

        $detail = $this->detailId
            ? ModelsPesanan::with(['user', 'penumpangs', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'rute.kapal'])->find($this->detailId)
            : null;

        return view('livewire.pesanan', [
            'dataPesanan' => $query->paginate(15),
            'detail' => $detail,
            'statusOptions' => ModelsPesanan::STATUS_LABEL,
        ])->layout('components.adminPesanan');
    }
}
