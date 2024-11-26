@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1>Gestión de Turnos</h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Columna de Turnos Solicitados -->
        <div class="col-md-6">
            <h2>Solicitudes de Turnos</h2>

            @if($solicitudes->where('status', 'pending')->isEmpty())
                <p>No hay solicitudes de turnos pendientes.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Cliente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes->where('status', 'pending') as $solicitud)
                        <tr>
                            <td>{{ $solicitud->id }}</td>
                            <td>{{ $solicitud->formatted_date ?? 'N/A' }}</td>
                            <td>{{ $solicitud->service->name ?? 'Sin servicio' }}</td>
                            <td>{{ $solicitud->user->name ?? 'Sin cliente' }}</td>
                            <td>
                                <!-- Un único formulario para aceptar o rechazar -->
                                <form action="{{ route('solicitudes.updateStatus', $solicitud->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="status" value="confirmed" class="btn btn-success btn-sm">Aceptar</button>
                                    <button type="submit" name="status" value="cancelled" class="btn btn-danger btn-sm">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Columna de Turnos Aceptados -->
        <div class="col-md-6">
            <h2>Turnos Aceptados</h2>

            @if($solicitudes->where('status', 'confirmed')->isEmpty())
                <p>No hay turnos aceptados.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes->where('status', 'confirmed') as $solicitud)
                        <tr>
                            <td>{{ $solicitud->id }}</td>
                            <td>{{ $solicitud->formatted_date ?? 'N/A' }}</td>
                            <td>{{ $solicitud->service->name ?? 'Sin servicio' }}</td>
                            <td>{{ $solicitud->user->name ?? 'Sin cliente' }}</td>
                            <td>
                                <span class="badge bg-success">Aceptado</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
