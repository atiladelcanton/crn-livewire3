<?php

namespace App\Livewire\Auth;

use App\Models\User;
use DB;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{Hash, Password};
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public ?string $email = null;

    public ?string $email_confirmation = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenNotValid()) {
            session()->flash('status', 'Token invalid');
            $this->redirectRoute('login');
        }
    }

    private function tokenNotValid(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        foreach ($tokens as $token) {
            if (Hash::check($this->token, $token->token)) {
                return false;
            }
        }

        return true;
    }

    public function render()
    {
        return view('livewire.auth.reset');
    }

    public function updatePassword(): void
    {
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password       = $password;
                $user->remember_token = \Str::random(60);
                $user->save();
                event(new PasswordReset($user));
            }
        );
        session()->flash('status', __($status));
        $this->redirectRoute('dashboard');
    }
}
