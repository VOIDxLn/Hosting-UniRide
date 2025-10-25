<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{
    public function checkout($trip_id)
    {
        $trip = Trip::findOrFail($trip_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'cop',
                    'product_data' => [
                        'name' => 'Reserva de viaje #' . $trip->id,
                    ],
                    'unit_amount' => $trip->price * 100, // en centavos
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['trip_id' => $trip->id]),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
{
    $trip_id = $request->trip_id; // Obtener de la query string
    $trip = Trip::findOrFail($trip_id);

    // Registrar el pago
    DB::table('trip_user')->insert([
        'trip_id' => $trip->id,
        'user_id' => Auth::id(),
        'status' => 'pagado',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Descontar un cupo
    $trip->decrement('available_seats');

    return redirect()->route('pasajero.trips.my_trips')->with('success', 'Pago exitoso, tu reserva fue confirmada ');
}


    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Pago cancelado ');
    }
}
