<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'max:255', 'email', 'confirmed'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required'])]
    public ?string $password = null;
    public function render()
    {
        return view('livewire.auth.register');
    }

    public function submit(): void
    {
        $this->validate();
        $user = User::query()
            ->create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password,
            ]);
        auth()->login($user);
    }
}
