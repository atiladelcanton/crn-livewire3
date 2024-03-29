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

test('checking form rules', function ($field, $value, $rule) {
    \Illuminate\Support\Facades\Notification::fake();
    $user = User::factory()->create();
    Livewire::test(Recovery::class)->set('email', $user->email)->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (
        ResetPassword $notification
    ) use ($user, $field, $value, $rule) {
        Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
            ->set($field, $value)
            ->call('updatePassword')
            ->assertHasErrors([$field => $rule]);

        return true;
    });

})->with([
    'email:required'     => ['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email:confirmed'    => ['field' => 'email', 'value' => 'email@email.com', 'rule' => 'confirmed'],
    'email:email'        => ['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'password:required'  => ['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password:confirmed' => ['field' => 'password', 'value' => 'any-password', 'rule' => 'confirmed'],

]);

test('need to show an obfuscate email to the user', function () {

    $email           = 'jeremias@example.com';
    $obfuscatedEmail = obfuscate_email($email);
    expect($obfuscatedEmail)
        ->toBe('je******@********com');

    // ---

    \Illuminate\Support\Facades\Notification::fake();
    $user = User::factory()->create();
    Livewire::test(Recovery::class)->set('email', $user->email)->call('startPasswordRecovery');

    Notification::assertSentTo($user, ResetPassword::class, function (
        ResetPassword $notification
    ) use ($user) {
        Livewire::test(Reset::class, ['token' => $notification->token, 'email' => $user->email])
            ->set('obfuscatedEmail', obfuscate_email($user->email))
            ->call('updatePassword');

        return true;
    });
});
