<?php

use App\Livewire\Auth\Register;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(Register::class)->assertStatus(200);
});

it('should be able to register a new user in the system', function () {
    Livewire::test(Register::class)->set('name', 'Joe doe')->set('email', 'joe@doe.com')->set(
        'email_confirmation',
        'joe@doe.com'
    )->set('password', 'password')->call('submit')->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'Joe doe',
        'email' => 'joe@doe.com',
    ]);

    assertDatabaseCount('users', 1);
});
