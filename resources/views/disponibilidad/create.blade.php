@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center" style="color: #F1C40F;">Agregar Disponibilidad</h1>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: #E74C3C; color: #ECF0F1;">
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
            <label for="day_of_week" style="color: #F1C40F;">Día de la semana</label>
            <select name="day_of_week" id="day_of_week" class="form-control" style="background-color: #34495E; color: #ECF0F1; border: 1px solid #F1C40F;">
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_time" style="color: #F1C40F;">Hora de inicio</label>
            <input type="time" name="start_time" id="start_time" class="form-control" style="background-color: #34495E; color: #ECF0F1; border: 1px solid #F1C40F;">
        </div>

        <div class="form-group">
            <label for="end_time" style="color: #F1C40F;">Hora de fin</label>
            <input type="time" name="end_time" id="end_time" class="form-control" style="background-color: #34495E; color: #ECF0F1; border: 1px solid #F1C40F;">
        </div>

        <button type="submit" class="btn btn-success mt-3" style="background-color: #28B463; border: none;">Guardar Disponibilidad</button>
        <a href="{{ route('disponibilidad.indexDisponibilidad') }}" class="btn btn-secondary mt-3" style="background-color: #7F8C8D; border: none; color: #2C3E50;">Cancelar</a>
    </form>
</div>

<style>
    body {
        background-color: #2C3E50;
        color: #ECF0F1;
    }

    .form-control {
        background-color: #34495E;
        color: #ECF0F1;
        border: 1px solid #F1C40F;
    }

    .form-control:focus {
        border-color: #F1C40F;
        box-shadow: 0 0 0 0.25rem rgba(241, 196, 15, 0.25);
    }

    .btn {
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-success {
        background-color: #28B463;
        border: none;
    }

    .btn-success:hover {
        background-color: #1D8348;
    }

    .btn-secondary {
        background-color: #7F8C8D;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5D6D7E;
    }

    .alert-danger {
        background-color: #E74C3C;
        color: #ECF0F1;
    }

    .alert-danger ul {
        padding-left: 20px;
    }

    .alert-danger li {
        list-style-type: circle;
    }
</style>

@endsection
