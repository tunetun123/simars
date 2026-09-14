<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Permissions untuk akses modul
        $permSigap = Permission::firstOrCreate(['name' => 'access sigap']);
        $permAdmin = Permission::firstOrCreate(['name' => 'access admin']);

        // Buat role Admin
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->syncPermissions([$permSigap, $permAdmin]);
        
        // Buat atau update User Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sigap.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'), // Password default
                'email_verified_at' => now(),
            ]
        );

        // Assign Role ke Admin
        $admin->assignRole($roleAdmin);
    }
}
