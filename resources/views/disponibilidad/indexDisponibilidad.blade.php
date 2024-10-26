@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Disponibilidad de {{ $user->name }}</h1>

    <!-- Botón para agregar disponibilidad -->
    <a href="{{ route('disponibilidad.create') }}" class="btn btn-primary mb-3">Agregar Disponibilidad</a>

    <table class="table">
        <thead>
            <tr>
                <th>Día de la semana</th>
                <th>Hora de inicio</th>
                <th>Hora de fin</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disponibilidades as $disponibilidad)
            <tr>
                <td>{{ $disponibilidad->day_of_week }}</td>
                <td>{{ $disponibilidad->start_time }}</td>
                <td>{{ $disponibilidad->end_time }}</td>
                <td>
                    <!-- Botón para eliminar -->
                    <form action="{{ route('disponibilidad.destroy', $disponibilidad->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection
