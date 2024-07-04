<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User as ModelsPengguna;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Pengguna extends Component
{

    use WithPagination;
    use LivewireAlert;

    public $search;
    public $updateData = false;

    public $name;
    public $email;
    public $role;
    public $created_at;

    public function render()
    {
        $search = '%' . $this->search . '%';
        $data = ModelsPengguna::where('name', 'like', $search)
            ->orWhere('email', 'like', $search)
            ->orWhere('role', 'like', $search)
            ->orWhere('created_at', 'like', $search)
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.pengguna', ['dataPengguna' => $data])
            ->layout('components.adminPengguna');
    }
}
