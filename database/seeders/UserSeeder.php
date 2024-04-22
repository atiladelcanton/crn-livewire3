<?php

namespace Database\Seeders;

use App\Models\{Can, User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->afterCreating(function (User $user) {
                $user->givePermissionTo(Can::BE_AN_ADMIN);
            })
            ->create([
                'name'  => 'Admin do CRM',
                'email' => 'admin@crm.com',
            ]);
    }
}
