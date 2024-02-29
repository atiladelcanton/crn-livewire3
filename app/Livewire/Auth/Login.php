<?php

namespace App\Livewire\Auth;

use Auth;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = null;

    public ?string $password = null;

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function tryToLogin(): void
    {
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('invalidCredentials', trans('auth.failed'));

            return;
        }
        $this->redirect(route('dashboard'));
    }
}
