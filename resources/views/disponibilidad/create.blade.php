@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Agregar Disponibilidad</h1>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario de creación de disponibilidad -->
    <form action="{{ route('disponibilidad.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="day_of_week">Día de la semana</label>
            <select name="day_of_week" id="day_of_week" class="form-control">
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_time">Hora de inicio</label>
            <input type="time" name="start_time" id="start_time" class="form-control">
        </div>

        <div class="form-group">
            <label for="end_time">Hora de fin</label>
            <input type="time" name="end_time" id="end_time" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-3">Guardar Disponibilidad</button>
        <a href="{{ route('disponibilidad.indexDisponibilidad') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>

@endsection
