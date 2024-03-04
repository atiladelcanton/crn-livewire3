<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function render(): string
    {
        return <<<BLADE
            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Logoff" wire:click="logout" />
        BLADE;

    }
    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }
}
