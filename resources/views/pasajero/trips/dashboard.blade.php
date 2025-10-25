@extends('layouts.pasajero')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5>Bienvenido, {{ auth()->user()->name }}</h5>
                    <p>Tu rol: Pasajero</p>
                    <p>Viajes reservados: {{ auth()->user()->tripsAsPassenger->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Puedes agregar aquí más resúmenes o estadísticas del usuario -->
    </div>

</div>
@endsection
