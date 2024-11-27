@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center text-white">Gestión de Turnos</h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success custom-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Columna de Turnos Solicitados -->
        <div class="col-md-6">
            <h2 class="text-white">Solicitudes de Turnos</h2>

            @if($solicitudes->where('status', 'pending')->isEmpty())
                <p class="text-white">No hay solicitudes de turnos pendientes.</p>
            @else
                <table class="table table-striped custom-table">
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
                                    <button type="submit" name="status" value="confirmed" class="btn custom-btn-accept btn-sm">Aceptar</button>
                                    <button type="submit" name="status" value="cancelled" class="btn custom-btn-reject btn-sm">Rechazar</button>
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
            <h2 class="text-white">Turnos Aceptados</h2>

            @if($solicitudes->where('status', 'confirmed')->isEmpty())
                <p class="text-white">No hay turnos aceptados.</p>
            @else
                <table class="table table-striped custom-table">
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

<style>
    body {
        background-color: #2C3E50;
    }

    h1, h2 {
        color: #ecf0f1;
    }

    .custom-table {
        background-color: #34495E;
        color: #ecf0f1;
    }

    .custom-table thead {
        background-color: #1abc9c;
    }

    .custom-table tbody tr {
        background-color: #34495E;
    }

    .custom-btn-accept {
        background-color: #27ae60;
        color: white;
    }

    .custom-btn-reject {
        background-color: #e74c3c;
        color: white;
    }

    .alert {
        background-color: #16a085;
        color: white;
    }

    .alert-success {
        background-color: #2ecc71;
    }

    .btn {
        font-size: 14px;
    }

    .badge.bg-success {
        background-color: #27ae60;
    }
</style>

@endsection
