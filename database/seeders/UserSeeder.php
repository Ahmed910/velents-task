<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(['name' => UserRoles::ADMIN->value] ,['name' => UserRoles::ADMIN->value]);
        $clientRole = Role::updateOrCreate(['name' => UserRoles::CLIENT->value] ,['name' => UserRoles::CLIENT->value]);
        /** @var User $admin */
        $admin = User::updateOrCreate(['email' => 'admin@info.com',], [
            'name' => 'velents admin',
            'email' => 'admin@info.com',
            'password' => '12345678',
        ]);

        $admin->assignRole($adminRole);

        $client = User::updateOrCreate(['email' => 'client@info.com'], [
            'name' => 'velents client',
            'email' => 'client@info.com',
            'password' => '12345678',
        ]);

        $client->assignRole($clientRole);

    }
}
