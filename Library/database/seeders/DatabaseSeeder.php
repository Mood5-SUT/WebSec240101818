<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::updateOrCreate([
            'email' => 'admin@library.com',
        ], [
            'name' => 'System Admin',
            'password' => bcrypt('password'),
        ]);

        $admin->syncRoles(['Admin']);

        $member = User::updateOrCreate([
            'email' => 'member@library.com',
        ], [
            'name' => 'Default Member',
            'password' => bcrypt('password'),
        ]);

        $member->syncRoles(['Member']);
    }
}
