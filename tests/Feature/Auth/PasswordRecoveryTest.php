<?php

use App\Livewire\Auth\Livewire\Recovery;
use App\Models\User;

use App\Notifications\PasswordRecoveryNotification;

use function Pest\Laravel\get;

test('needs to have a route to password recovery', function () {
    get(route('auth.password.recovery'))->assertOk();
});

it('should be able to request for password recovery sending notification to the user', function () {
    \Illuminate\Support\Facades\Notification::fake();
    /** @var User $user */
    $user = User::factory()->create();
    Livewire::test(Recovery::class)
        ->assertDontSee('You will receive an email with the password recovery link')
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee('You will receive an email with the password recovery link');
    \Illuminate\Support\Facades\Notification::assertSentTo($user, PasswordRecoveryNotification::class);
});
