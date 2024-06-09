<?php

namespace App\Livewire;

use App\Models\Tiket as ModelsTiket;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Tiket extends Component
{
    public function render()
    {
        return view('livewire.tiket')
            ->layout('components.adminTiket');
    }
}
