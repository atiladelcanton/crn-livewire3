<?php

use App\Livewire\Auth\{Recovery,Reset};
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

use function Pest\Laravel\get;

test('need to receive a valid token with a combination with the email', function () {
    \Illuminate\Support\Facades\Notification::fake();
    $user = User::factory()->create();
    Livewire::test(Recovery::class)->set(
        'email',
        $user->email
    )->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (
        ResetPassword $notification
    ) {

        get(route('password.reset') . '?token=' . $notification->token)
        ->assertSuccessful();

        get(route('password.reset') . '?token=any-token')
            ->assertRedirect(route('login'));

        return true;
    });

});
