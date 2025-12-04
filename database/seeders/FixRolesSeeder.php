<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class FixRolesSeeder extends Seeder
{
    public function run()
    {
        // Crear roles si no existen
        $roles = ['admin', 'conductor', 'pasajero'];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Asignar rol "pasajero" a todos los usuarios sin rol
        $pasajero = Role::where('name', 'pasajero')->first();

        foreach (User::all() as $u) {
            if ($u->roles()->count() === 0) {
                $u->roles()->attach($pasajero->id);
            }
        }
    }
}
