<?php

use App\Livewire\Auth\Recovery;
use App\Models\User;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, get};

test('needs to have a route to password recovery', function () {
    get(route('password.recovery'))->assertOk();
});

it('should be able to request for password recovery sending notification to the user', function () {
    \Illuminate\Support\Facades\Notification::fake();
    /** @var User $user */
    $user = User::factory()->create();
    Livewire::test(Recovery::class)
        ->assertDontSee('You will receive an email with the password recovery link')
        ->set('email', $user->email)->call('startPasswordRecovery')->assertSee('You will receive an email with the password recovery link');
    \Illuminate\Support\Facades\Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
});

test('making sure the email is real', function ($value, $rule) {
    Livewire::test(Recovery::class)->set(
        'email',
        $value
    )->call('startPasswordRecovery')->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'any email', 'rule' => 'email'],
]);

test('needs to create a token when requesting a password recovery', function () {
    /** @var User $user */
    $user = User::factory()->create();

    Livewire::test(Recovery::class)->set('email', $user->email)->call('startPasswordRecovery');

    assertDatabaseCount('password_reset_tokens', 1);
    assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
});
