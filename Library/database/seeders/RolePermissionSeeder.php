<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_roles',
            'view_members',
            'manage_users',
            'manage_books',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $librarianRole = Role::firstOrCreate(['name' => 'Librarian', 'guard_name' => 'web']);
        $memberRole = Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $librarianRole->syncPermissions(['view_members', 'manage_books']);
        $memberRole->syncPermissions([]);
    }
}
