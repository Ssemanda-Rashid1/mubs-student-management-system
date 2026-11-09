<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::firstOrCreate(['name' => 'manage students']);
        Permission::firstOrCreate(['name' => 'manage courses']);
        Permission::firstOrCreate(['name' => 'view courses']);
        Permission::firstOrCreate(['name' => 'view registrations']);
        Permission::firstOrCreate(['name' => 'register for courses']);
        Permission::firstOrCreate(['name' => 'manage registrations']);
        Permission::firstOrCreate(['name' => 'manage users']);

        // Create Roles and assign existing permissions
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Give admin all permissions or assign student specific ones
        $adminRole->givePermissionTo(Permission::all());

        // Create an admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@mubs.ac.ug'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole($adminRole);
    }
}