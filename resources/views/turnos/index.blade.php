@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center" style="color: #F1C40F;">Mis Turnos</h2>
    <table class="table table-striped" style="background-color: #2C3E50; color: #ECF0F1;">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Empresa</th>
                <th>Servicio</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turnos as $turno)
            <tr>
                <td style="color: #363434;">{{ $turno->formatted_date }}</td>
                <td style="color: #363434;">{{ $turno->service->enterprise->name }}</td>
                <td style="color: #363434;">{{ $turno->service->name }}</td>
                <td style="color: #363434;">{{ $turno->service->duration }} minutos</td>
                <td style="color: #363434;">{{ ucfirst($turno->status) }}</td>
                <td>
                    @if($turno->status != 'cancelled')
                        <form action="{{ route('turnos.cancel', $turno->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar este turno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="background-color: #E74C3C; border: none;">Cancelar</button>
                        </form>
                    @else
                        <span class="text-muted">Cancelado</span>
                    @endif
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

    .table-striped tbody tr:nth-child(odd) {
        background-color: #34495E;
    }

    .btn {
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .btn-danger {
        background-color: #E74C3C;
    }

    .btn-danger:hover {
        background-color: #C0392B;
    }

    .text-muted {
        color: #7F8C8D;
    }

    .table th {
        color: #F1C40F;
    }

    .table td {
        color: #ECF0F1;
    }
</style>
@endsection

