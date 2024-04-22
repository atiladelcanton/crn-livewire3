<?php

namespace App\Livewire\Admin;

use App\Enum\Can;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
