<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ConductorProfileController extends Controller
{
    public function index()
    {
        $conductor = Auth::user();

        // ⭐ Reseñas donde ESTE conductor es el driver_id
        $reviews = Review::where('driver_id', $conductor->id)
                         ->with(['passenger', 'trip'])
                         ->get();

        return view('conductor.perfil', compact('conductor', 'reviews'));
    }
}
