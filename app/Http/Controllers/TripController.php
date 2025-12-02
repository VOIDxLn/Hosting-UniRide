<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 📋 Mostrar todos los viajes disponibles
    public function index()
    {
        $trips = Trip::orderBy('departure_time')->get();
        return view('trips.index', compact('trips'));
    }

    // ➕ Crear un nuevo viaje
    public function store(Request $request)
    {
    $request->validate([
        'departure_time' => 'required|date',
        'origin' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'available_seats' => 'required|integer|min:0',
    ]);

    Trip::create([
        'user_id' => Auth::id(), // <--- ASIGNAR CONDUCTOR 🔥
        'departure_time' => $request->departure_time,
        'origin' => $request->origin,
        'destination' => $request->destination,
        'price' => $request->price,
        'available_seats' => $request->available_seats,
        'status' => 'Pendiente', // opcional pero recomendado
    ]);

    return redirect()->route('trips.index')->with('success', 'Viaje creado correctamente.');
    }


    // ✏️ Actualizar viaje existente
    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'departure_time' => 'required|date',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0',
        ]);

        $trip->update($request->all());

        return redirect()->route('trips.index')->with('success', 'Viaje actualizado correctamente.');
    }

    // 🗑️ Eliminar viaje
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Viaje eliminado correctamente.');
    }

    // ✅ Finalizar viaje (solo conductor)
    public function finalizar($id)
    {
        $trip = Trip::findOrFail($id);

        if ($trip->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para finalizar este viaje.');
        }

        $trip->status = 'Finalizado';
        $trip->save();

        return back()->with('success', '✅ El viaje ha sido marcado como finalizado.');
    }

    // 🚗 Mostrar los viajes que el usuario ha pagado
    public function myTrips()
    {
        $user = auth()->user();

        // Obtener los viajes pagados por el usuario
        $trips = $user->trips()
            ->wherePivot('status', 'pagado')
            ->with('vehicle')
            ->get();

        return view('trips.my_trips', compact('trips'));
    }
}
