<?php

namespace Database\Seeders;

use App\Models\{User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->afterCreating(function (User $user) {
                $user->givePermissionTo('be an admin');
            })
            ->create([
                'name'  => 'Admin do CRM',
                'email' => 'admin@crm.com',
            ]);
    }
}
