<?php

use function Pest\Laravel\{actingAs, get};

it('should block access to users without the permission _be an admin_', function () {
    $user = \App\Models\User::factory()->create();
    actingAs($user);

    Livewire::test(\App\Livewire\Admin\Dashboard::class)
        ->assertForbidden();

    get(route('admin.dashboard'))
        ->assertForbidden();
});
