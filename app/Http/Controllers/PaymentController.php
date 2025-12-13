<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaCreadaMail;

class PaymentController extends Controller
{
    public function checkout($trip_id)
    {

        dd([
        'ENV' => env('STRIPE_SECRET'),
        'CONFIG' => config('services.stripe.secret'),
        'SERVICES' => config('services'),
        ]);
    
        $trip = Trip::findOrFail($trip_id);
    
        Stripe::setApiKey(config('services.stripe.secret'));

            
        $trip = Trip::findOrFail($trip_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        // Enviar metadata para saber quién pagó y qué viaje es
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'cop',
                    'product_data' => [
                        'name' => 'Reserva de viaje #' . $trip->id,
                    ],
                    'unit_amount' => $trip->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
            ],
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        // Obtener session_id enviado por Stripe
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Error validando el pago.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        // Recuperar metadata enviada
        $trip_id = $session->metadata->trip_id;
        $user_id = $session->metadata->user_id;

        $trip = Trip::findOrFail($trip_id);
        $user = User::findOrFail($user_id);

        // PREVENIR reservas duplicadas
        $exists = DB::table('trip_user')
            ->where('trip_id', $trip->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$exists) {
            // Registrar pago y reserva
            DB::table('trip_user')->insert([
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'status' => 'pagado',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Disminuir cupo
            $trip->decrement('available_seats');

            // ENVIAR CORREO AL CONDUCTOR
            if ($trip->driver && $trip->driver->email) {
                Mail::to($trip->driver->email)->send(
                    new ReservaCreadaMail($trip, $user)
                );
            }
        }

        return redirect()->route('pasajero.trips.my_trips')
            ->with('success', 'Pago exitoso. Tu reserva fue confirmada.');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Pago cancelado.');
    }
}
