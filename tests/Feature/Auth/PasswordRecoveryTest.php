<?php

test('needs to have a route to password recovery', function () {
    \Pest\Laravel\get(route('auth.password.recovery'))->assertOk();
});
