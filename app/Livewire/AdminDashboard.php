<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    public function render()
    {
        // echo "<h1>" . Auth::user()->name . "</h1>";
        return view('livewire.admin-dashboard', ['user' => Auth::user()])
            ->layout('components.AdminDashboard');
    }
}
