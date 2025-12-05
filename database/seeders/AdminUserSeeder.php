<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@uniride.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );

        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
