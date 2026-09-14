<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Modul Admin
        Permission::firstOrCreate(['name' => 'access admin', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'web']);

        // 2. Modul Sigap
        Permission::firstOrCreate(['name' => 'access sigap', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage sigap categories', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view sigap documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create sigap documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit sigap documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete sigap documents', 'guard_name' => 'web']);

        // 3. Modul Siparsi / SIPARSI
        Permission::firstOrCreate(['name' => 'access siparsi', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage siparsi groups', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage siparsi categories', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage siparsi ep', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view siparsi documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create siparsi documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit siparsi documents', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete siparsi documents', 'guard_name' => 'web']);

        // Hak Akses Spesifik Standar SIPARSI (Hanya Upload)
        $standarAcr = ['TKRS', 'KPS', 'MFK', 'PMKP', 'MRMIK', 'PPI', 'PPK', 'AKP', 'HPK', 'PP', 'PAP', 'PAB', 'PKPO', 'KE', 'SKP', 'PROGNAS'];
        foreach ($standarAcr as $acr) {
            Permission::firstOrCreate(['name' => 'upload siparsi ' . strtolower($acr), 'guard_name' => 'web']);
        }

        // Create Super Admin Role
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        // Super Admin gets all permissions
        $roleSuperAdmin->syncPermissions(Permission::all());

        // Create Default Admin User if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@sigap.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');
    }
}
