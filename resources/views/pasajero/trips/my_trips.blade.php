@extends('layouts.pasajero')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mis Viajes</h1>

    @if($myTrips->isEmpty())
        <p>No tienes viajes reservados.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hora de salida</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Costo</th>
                        <th>Cupos disponibles</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myTrips as $trip)
                    <tr>
                        <td>{{ $trip->departure_time }}</td>
                        <td>{{ $trip->origin }}</td>
                        <td>{{ $trip->destination }}</td>
                        <td>${{ number_format($trip->price, 0, ',', '.') }}</td>
                        <td>{{ $trip->available_seats }}</td>
                        <td>
                            <form action="{{ route('pasajero.trips.cancel', $trip) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar tu reserva?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
