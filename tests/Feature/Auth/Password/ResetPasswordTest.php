<?php

use App\Livewire\Auth\{Recovery, Reset};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

use function Pest\Laravel\get;
use function PHPUnit\Framework\assertTrue;

test('need to receive a valid token with a combination with the email', function () {
    \Illuminate\Support\Facades\Notification::fake();
    $user = User::factory()->create();
    Livewire::test(Recovery::class)->set('email', $user->email)->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (
        ResetPassword $notification
    ) {
        get(route('password.reset') . '?token=' . $notification->token)->assertSuccessful();

        get(route('password.reset') . '?token=any-token')->assertRedirect(route('login'));

        return true;
    });
});

test('test if is possible to reset the password with the given token', function () {
    \Illuminate\Support\Facades\Notification::fake();
    $user = User::factory()->create();
    Livewire::test(Recovery::class)->set('email', $user->email)->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (
        ResetPassword $notification
    ) use ($user) {
        Livewire::test(
            Reset::class,
            ['token' => $notification->token, 'email' => $user->email]
        )
            ->set('email_confirmation', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));
        $user->refresh();
        assertTrue(Hash::check('new-password', $user->password));

        return true;
    });
});
