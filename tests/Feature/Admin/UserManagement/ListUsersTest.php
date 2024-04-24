<?php

use App\Livewire\Admin;
use App\Models\User;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/users', function () {
    actingAs(User::factory()->admin()->create());
    get(route('admin.users'))->assertOk();
});

test('making sure that the route is protected by the permission BE_AN_ADMIN', function () {
    actingAs(User::factory()->create());
    get(route('admin.users'))->assertForbidden();
});

test("let's create a livewire component to list all users in the page", function () {

    $users = User::factory()->count(10)->create();
    $lw    = \Livewire\Livewire::test(Admin\Users\Index::class);
    $lw->assertSet('users', function ($users) {
        expect($users)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($users as $user) {
        $lw->assertSee($user->name);
    }
});
