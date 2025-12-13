<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function perfil()
    {
    $user = auth()->user();

    // Si el usuario es conductor → cargar reseñas recibidas
    if ($user->hasRole('conductor')) {
        $reviews = \App\Models\Review::where('driver_id', $user->id)
            ->with(['passenger', 'trip'])
            ->get();
    } else {
        $reviews = collect(); // vacío para pasajero/admin
    }

    return view('users.perfil', compact('user', 'reviews'));
    }


    // Visualizar perfil publico de un usuario
    public function perfilPublico($id)
    {
        // Buscar el usuario
        $user = \App\Models\User::with(['vehicles'])->findOrFail($id);

        // Cargar reseñas SOLO si es conductor
        if ($user->hasRole('conductor')) {
            $reviews = \App\Models\Review::where('driver_id', $user->id)
                ->with(['passenger', 'trip'])
                ->get();
        } else {
            $reviews = collect();
        }

        // Vista que mostrará el perfil público
        return view('users.perfil_publico', compact('user', 'reviews'));
    }    

}
