<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $passenger;

    public function __construct($trip, $passenger)
    {
        $this->trip = $trip;
        $this->passenger = $passenger;
    }

    public function build()
    {
        return $this->subject('Nueva reserva confirmada')
                    ->view('emails.notificacionreserva')
                    ->with([
                        'trip' => $this->trip,
                        'passenger' => $this->passenger,
                    ]);
    }
}
