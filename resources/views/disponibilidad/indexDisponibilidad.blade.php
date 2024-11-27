@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center" style="color: #F1C40F;">Disponibilidad de {{ $user->name }}</h1>

    <!-- Botón para agregar disponibilidad -->
    <a href="{{ route('disponibilidad.create') }}" class="btn btn-primary mb-3" style="background-color: #F1C40F; border: none; color: #2C3E50;">Agregar Disponibilidad</a>

    <table class="table" style="background-color: #2C3E50; color: #ECF0F1;">
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
                <td style="color: #363434;">{{ $disponibilidad->day_of_week }}</td>
                <td style="color: #363434;">{{ $disponibilidad->start_time }}</td>
                <td style="color: #363434;">{{ $disponibilidad->end_time }}</td>
                <td>
                    <!-- Botón para eliminar -->
                    <form action="{{ route('disponibilidad.destroy', $disponibilidad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta disponibilidad?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="background-color: #E74C3C; border: none;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    body {
        background-color: #2C3E50;
        color: #ECF0F1;
    }

    .btn {
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #F1C40F;
        border: none;
    }

    .btn-primary:hover {
        background-color: #D4AC0D;
    }

    .btn-danger {
        background-color: #E74C3C;
    }

    .btn-danger:hover {
        background-color: #C0392B;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #34495E;
    }

    .table-striped tbody tr {
        color: #ECF0F1;
    }

    .table th {
        color: #F1C40F;
    }

    .table td {
        color: #ECF0F1;
    }
</style>

@endsection
