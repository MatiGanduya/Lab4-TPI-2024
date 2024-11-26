@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h2>Mis Turnos</h2>
    <table class="table table-striped">
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
                <td>{{ $turno->formatted_date }}</td>
                <td>{{ $turno->service->enterprise->name }}</td>
                <td>{{ $turno->service->name }}</td>
                <td>{{ $turno->service->duration }} minutos</td>
                <td>{{ ucfirst($turno->status) }}</td>
                <td>
                    @if($turno->status != 'cancelled')
                        <form action="{{ route('turnos.cancel', $turno->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar este turno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
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
@endsection

