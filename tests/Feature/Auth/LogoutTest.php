<?php

use App\Livewire\Auth\{Login, Logout};
use App\Models\User;

it('should be able to logout of the application', function () {
    $user = User::factory()->create([
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'password' => 12345678,
    ]);
    \Pest\Laravel\actingAs($user);

    \Livewire\Livewire::test(Logout::class)
        ->call('logout')
        ->assertRedirect(route('login'));
    expect(auth()->guest())->toBeTrue();

});
