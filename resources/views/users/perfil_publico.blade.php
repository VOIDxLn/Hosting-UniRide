@extends('layouts.pasajero')

@section('content')
<div class="container-fluid">

    <div class="row justify-content-center mt-4">
        <div class="col-md-10">

            <div class="card shadow-sm p-4" style="background: #e5e5e5;">
                <div class="row">

                    {{-- FOTO + NOMBRE --}}
                    <div class="col-md-3 text-center d-flex flex-column align-items-center">
                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                            style="width: 120px; height: 120px; border: 2px solid #ccc;">
                            <i class="fas fa-user fa-4x text-secondary"></i>
                        </div>
                    </div>

                    <div class="col-md-9 d-flex align-items-center">
                        <h2 class="text-secondary mb-0">{{ $user->name }}</h2>
                    </div>
                </div>

                <hr>

                {{-- DATOS PERSONALES --}}
                <p><strong>Correo electrónico:</strong><br> {{ $user->email }}</p>

                <p><strong>Teléfono:</strong><br> {{ $user->phone ?? 'No registrado' }}</p>

                <p><strong>Rol:</strong><br>
                    {{ $user->hasRole('conductor') ? 'Conductor' : 'Usuario' }}
                </p>

                <hr>

                {{-- VEHÍCULOS --}}
                <h4 class="mt-4">Vehículos registrados</h4>

                @if($user->vehicles->count() > 0)
                    @foreach ($user->vehicles as $vehicle)
                        <div class="p-3 my-3 rounded shadow-sm" style="background: #f8f8f8;">
                            <p><strong>Marca:</strong> {{ $vehicle->brand }}</p>
                            <p><strong>Modelo:</strong> {{ $vehicle->model }}</p>
                            <p><strong>Placa:</strong> {{ $vehicle->plate }}</p>
                            <p><strong>Color:</strong> {{ $vehicle->color }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No tiene vehículos registrados.</p>
                @endif

                <hr>

                {{-- RESEÑAS --}}
                <h4 class="mt-4">Reseñas recibidas</h4>

                @if($reviews->count() > 0)
                    @php
                        $promedio = number_format($reviews->avg('rating'), 1);
                    @endphp

                    <p class="mb-4">
                        <strong>Calificación promedio:</strong>
                        {{ $promedio }} / 5 ⭐
                    </p>

                    @foreach ($reviews as $review)
                        <div class="p-3 my-3 rounded shadow-sm" style="background: #f8f8f8;">
                            <p><strong>Pasajero:</strong> {{ $review->passenger->name }}</p>

                            <p><strong>Calificación:</strong>
                                {{ $review->rating }} ⭐
                            </p>

                            <p><strong>Comentario:</strong> {{ $review->comment }}</p>

                            <p><strong>Viaje:</strong>
                                {{ $review->trip->origin }} → {{ $review->trip->destination }}
                            </p>

                            <small class="text-muted">
                                Publicado el: {{ $review->created_at->format('Y-m-d') }}
                            </small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Aún no tiene reseñas.</p>
                @endif

            </div>

        </div>
    </div>

</div>
@endsection
