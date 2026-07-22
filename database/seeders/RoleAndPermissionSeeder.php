<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        Artisan::call('cache:clear');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'upload_content',
            'enroll_course',
            'submit_assignment',
            'create_course',
            'audit_security'
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Create roles and assign permissions
        $courseAdmin = Role::findOrCreate('course_admin', 'web');
        $courseAdmin->givePermissionTo(Permission::all());

        $instructor = Role::findOrCreate('instructor', 'web');
        $instructor->givePermissionTo('upload_content');

        $student = Role::findOrCreate('student', 'web');
        $student->givePermissionTo(['enroll_course', 'submit_assignment']);

        // Seed default users
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@study.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Secret123!'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $adminUser->syncRoles([$courseAdmin]);

        $instructorUser = User::updateOrCreate(
            ['email' => 'instructor@study.com'],
            [
                'name' => 'Instructor User',
                'password' => Hash::make('Secret123!'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $instructorUser->syncRoles([$instructor]);

        $studentUser = User::updateOrCreate(
            ['email' => 'student@study.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('Secret123!'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $studentUser->syncRoles([$student]);

        // Add a second student for scoping tests
        $studentUser2 = User::updateOrCreate(
            ['email' => 'student2@study.com'],
            [
                'name' => 'Student Two',
                'password' => Hash::make('Secret123!'),
                'email_verified_at' => Carbon::now(),
            ]
        );
        $studentUser2->syncRoles([$student]);
    }
}
