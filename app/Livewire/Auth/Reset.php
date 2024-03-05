<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public function mount(): void
    {
        $this->token = request()->get('token');

        if($this->tokenNotValid()) {
            session()->flash('status', 'Token invalid');
            $this->redirectRoute('login');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset');
    }

    private function tokenNotValid(): bool
    {
        $tokens = \DB::table('password_reset_tokens')->get(['token']);

        foreach ($tokens as $token) {
            if(Hash::check($this->token, $token->token)) {
                return false;
            }
        }

        return true;
    }
}
