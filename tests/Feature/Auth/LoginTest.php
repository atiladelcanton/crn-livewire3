<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)->assertOk();
});

it('should be abre to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)->set('email', 'joe@doe.com')->set(
        'password',
        'password'
    )->call('tryToLogin')->assertHasNoErrors()->assertRedirect(route('dashboard'));

    expect(auth()->check())->toBeTrue()->and(auth()->user())->id->toBe($user->id);
});

it('should make sure to inform the user an error when email and password doesnt work', function () {
    Livewire::test(Login::class)->set('email', 'joe@doe.com')->set(
        'password',
        'password'
    )->call('tryToLogin')->assertHasErrors(['invalidCredentials'])->assertSee(trans('auth.failed'));
});

it('should make sure that the rate limiting is blocking after 5 attempts', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', '34retwertg')
            ->call('tryToLogin')
            ->assertHasErrors(['invalidCredentials']);
    }
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', '34retwertg')
        ->call('tryToLogin')
        ->assertHasErrors(['rateLimiter']);
});
