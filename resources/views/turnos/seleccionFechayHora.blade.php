@extends('layouts.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/styleCalendar.css') }}"> <!-- Incluye el archivo CSS personalizado -->
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <h4>Selecciona una Fecha</h4>
            <div id="calendar"></div>
        </div>
        <div class="col-md-8">
            <h3>Selecciona Fecha y Hora para el Servicio: {{ $servicio->name }}</h3>
            <div id="horasDisponibles"></div>


            <!-- Aquí mostrarás los bloques de horarios disponibles -->
            <div id="hora-container" class="mt-4">
                <!-- Los bloques de horas se cargarán dinámicamente aquí -->
            </div>

            <!-- Formulario para confirmar el turno -->
            <form action="{{ route('turnos.reservar', ['servicio_id' => $servicio->id]) }}" method="POST" id="turno-form">
                @csrf
                <input type="hidden" id="fecha" name="fecha">
                <input type="hidden" id="hora" name="hora">

                <button type="submit" class="btn btn-primary">Confirmar Turno</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.disponibilidad = @json($bloques);
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script> <!-- Incluye el archivo JavaScript personalizado para el calendario -->
@endsection
