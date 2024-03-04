<?php

namespace App\Livewire\Auth\Livewire;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    #[Rule(['required', 'email'])]
    public ?string $email = null;
    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.livewire.recovery');
    }

    public function startPasswordRecovery(): void
    {
        $this->validate();
        $user = User::whereEmail($this->email)->first();

        $user?->notify(new PasswordRecoveryNotification());
        $this->message = 'You will receive an email with the password recovery link';
    }
}
