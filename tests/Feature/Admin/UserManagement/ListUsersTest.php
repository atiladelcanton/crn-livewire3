<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/users', function () {
    actingAs(User::factory()->admin()->create());
    get(route('admin.users'))->assertOk();
});
