<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders SIEMPRE seguros (local + producción)
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Seeders SOLO para desarrollo local
        if (app()->environment('local')) {
            $this->call([
                ProductSeeder::class,
                DemoUsersSeeder::class,
            ]);
        }
    }
}
