<?php

use App\Livewire\Auth\{Logout};
use App\Models\User;

use function Pest\Laravel\actingAs;

it('should be able to logout of the application', function () {
    $user = User::factory()->create([
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'password' => 12345678,
    ]);
    actingAs($user);

    \Livewire\Livewire::test(Logout::class)->call('logout')->assertRedirect(route('login'));
    expect(auth()->guest())->toBeTrue();
});
