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

}
