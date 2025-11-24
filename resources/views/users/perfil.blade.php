{{-- Selección dinámica de layout según el rol --}}
@php
    $role = strtolower(auth()->user()->roles->first()->name ?? 'pasajero');

    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'conductor' => 'layouts.conductor',
        'pasajero' => 'layouts.pasajero',
        default => 'layouts.pasajero',
    };
@endphp

@extends($layout)

{{-- Abrimos la sección correcta según el rol --}}
@if($role === 'admin')
    @section('contenido')
@else
    @section('content')
@endif

<div class="container-fluid d-flex justify-content-center">
    <div class="card shadow mt-5 p-4" style="max-width: 900px; width: 100%; background-color: #e3e3e3;">

        {{-- Información usuario --}}
        <div class="d-flex align-items-center gap-4">
            <div class="rounded-circle bg-white border border-secondary d-flex align-items-center justify-content-center"
                style="width: 120px; height: 120px;">
                <i class="fas fa-user fa-4x text-secondary"></i>
            </div>
            <h2 class="text-secondary">{{ Auth::user()->name }}</h2>
        </div>

        <hr>

        <div class="mt-3">
            <p><strong>Correo electrónico:</strong><br>
                <a href="#" class="text-primary">{{ Auth::user()->email }}</a>
            </p>

            <p><strong>Contraseña:</strong><br>
                ************
            </p>

            <p><strong>Teléfono:</strong><br>
                {{ Auth::user()->telefono ?? 'Sin registrar' }}
            </p>

            <p><strong>Rol:</strong><br>
                {{ ucfirst($role) }}
            </p>

            {{-- Vehículos solo si es conductor --}}
            @if(Auth::user()->hasRole('conductor'))
                <h4 class="mt-4">Vehículos registrados</h4>
                @if(Auth::user()->vehicles->isEmpty())
                    <p class="text-muted">No tienes vehículos registrados.</p>
                @else
                    @foreach(Auth::user()->vehicles as $vehicle)
                        <div class="mt-3 p-3 border rounded" style="background: #f8f8f8;">
                            <p><strong>Marca:</strong> {{ $vehicle->brand }}</p>
                            <p><strong>Modelo:</strong> {{ $vehicle->model }}</p>
                            <p><strong>Placa:</strong> {{ $vehicle->plate }}</p>
                            <p><strong>Color:</strong> {{ $vehicle->color }}</p>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>

        <button class="btn btn-primary px-4 mt-3" type="button" data-toggle="modal" data-target="#editarPerfil">
            Editar Perfil
        </button>

        <!--Seccion de reseñas en perfil conductor: -->

        <hr class="mt-4">

        {{-- ⭐ Reseñas del conductor --}}
        @if(isset($reviews) && $reviews->count() > 0)
            <h4 class="mt-4">Reseñas recibidas</h4>

            {{-- Calificación promedio --}}
            @php
                $promedio = round($reviews->avg('rating'), 1);
            @endphp

            <p><strong>Calificación promedio:</strong>
                {{ $promedio }} / 5 ⭐
            </p>

            {{-- Lista de reseñas --}}
            @foreach($reviews as $review)
                <div class="p-3 mt-3 border rounded" style="background: #f5f5f5;">
                    <p><strong>Pasajero:</strong> {{ $review->passenger->name ?? 'Desconocido' }}</p>
                    <p><strong>Calificación:</strong> {{ $review->rating }} ⭐</p>
                    <p><strong>Comentario:</strong> {{ $review->comment ?? 'Sin comentario' }}</p>

                    @if($review->trip)
                        <p class="text-muted" style="font-size: 14px;">
                            Viaje: {{ $review->trip->origin }} → {{ $review->trip->destination }}
                        </p>
                    @endif

                    <span style="font-size: 12px;" class="text-muted">
                        Publicado el: {{ $review->created_at->format('Y-m-d') }}
                    </span>
                </div>
            @endforeach

        @else
            <h4 class="mt-4">Reseñas recibidas</h4>
            <p class="text-muted">Aún no tienes reseñas.</p>
        @endif


    </div>
</div>

{{-- Modal editar perfil --}}
<div class="modal fade" id="editarPerfil" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('perfil.update') }}">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ auth()->user()->telefono }}">
                    </div>

                    {{-- Nueva contraseña --}}
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control">

                        {{-- Mensaje de advertencia --}}
                        <small class="text-primary mt-1 d-block">
                            La contraseña debe tener 6 o más caracteres.
                        </small>
                    </div>

                    {{-- Confirmación --}}
                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
