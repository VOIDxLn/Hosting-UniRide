<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    // Cargar relaciones: roles + vehicles
    $user = Auth::user()->load(['roles', 'vehicles']);

    // Obtener el rol (el usuario solo tiene 1, según tu tabla)
    $rol = $user->roles->first()->name ?? null;

    switch ($rol) {
        case 'Admin':
            return view('layouts.admin', compact('user'));

        case 'Conductor':
            return view('layouts.conductor', compact('user'));

        case 'Pasajero':
            return view('layouts.pasajero', compact('user'));

        default:
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tu cuenta no tiene un rol válido.');
    }
}

}


