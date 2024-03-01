<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
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

        if(RateLimiter::tooManyAttempts(Str::lower($this->email), 5)) {
            $this->addError('rateLimiter', trans('auth.throttle', []));

            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('invalidCredentials', trans('auth.failed'));
            RateLimiter::hit(Str::lower($this->email));

            return;
        }
        $this->redirect(route('dashboard'));
    }
}
