<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kapal as ModelsKapal;
use App\Models\Pelabuhan as ModelsPelabuhan;
use App\Models\Rute as ModelsRute;
use App\Models\Tiket as ModelsTiket;
use App\Models\User as ModelsPengguna;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    use WithPagination;

    public $usercount;
    public $tiketcount;
    public $rutecount;

    public function mount()
    {
        $this->usercount = ModelsPengguna::count();
        $this->tiketcount = ModelsTiket::count();
        $this->rutecount = ModelsRute::count();
    }
    public function render()
    {
        return view('livewire.admin-dashboard', [
            'user' => Auth::user(),
            'dataKapal' => ModelsKapal::paginate(5),
            'dataPelabuhan' => ModelsPelabuhan::paginate(5),
            'dataRute' => ModelsRute::paginate(5),
            'dataTiket' => ModelsTiket::paginate(5),
            'dataUser' => ModelsPengguna::paginate(5),
        ])->layout('components.AdminDashboard');
    }
}
