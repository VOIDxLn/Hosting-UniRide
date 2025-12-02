<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Reserva Confirmada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 25px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c89e8;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 15px;
        }
        p {
            color: #333;
            font-size: 15px;
        }
        .highlight {
            font-weight: bold;
        }
        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>¡Nueva Reserva Realizada! 🚗💨</h2>

    <p>Hola <strong>{{ $trip->driver->name }}</strong>,</p>

    <p>El pasajero <strong>{{ $passenger->name }}</strong> ha realizado una reserva para uno de tus viajes.</p>

    <div class="section">
        <h3>Detalles del viaje:</h3>
        <p><span class="highlight">Origen:</span> {{ $trip->origin }}</p>
        <p><span class="highlight">Destino:</span> {{ $trip->destination }}</p>
        <p><span class="highlight">Fecha y hora:</span> {{ \Carbon\Carbon::parse($trip->departure_time)->format('d/m/Y H:i') }}</p>
        <p><span class="highlight">Precio:</span> ${{ number_format($trip->price, 0, ',', '.') }} COP</p>
    </div>

    <div class="section">
        <h3>Información del pasajero:</h3>
        <p><span class="highlight">Nombre:</span> {{ $passenger->name }}</p>
        <p><span class="highlight">Correo:</span> {{ $passenger->email }}</p>
        {{-- Si tienes teléfono en users, puedes mostrarlo aquí --}}
        {{-- <p><span class="highlight">Teléfono:</span> {{ $passenger->phone }}</p> --}}
    </div>

    <p>Puedes ver detalles de tus viajes en tu panel de conductor.</p>

    <div class="footer">
        <p>Gracias por usar UniRide 🚀</p>
    </div>
</div>

</body>
</html>
