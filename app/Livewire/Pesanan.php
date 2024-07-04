<?php

namespace App\Livewire;

use App\Models\Pesanan as ModelsPesanan;
use App\Models\Penumpang as ModelsPenumpang;
use App\Models\User as ModelsUser;
use Livewire\WithPagination;
use Livewire\Component;

use Jantinnerezo\LivewireAlert\LivewireAlert;

class Pesanan extends Component
{
    use WithPagination;

    public function mount()
    {
        $users = ModelsPenumpang::all();
    }
    public function render()
    {
    
        return view('livewire.pesanan', [
            'dataUser' => ModelsUser::all(),
            'dataPenumpang' => ModelsPenumpang::all(),
        ])->layout('components.adminPesanan');
    }
}