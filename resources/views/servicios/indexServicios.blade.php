@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Cuadro de Empresas -->
        <div class="col-md-6">
            <h3>Empresas</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Empresa</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                    <tr>
                        <td>{{ $empresa->name }}</td>
                        <td>
                            <a href="{{ route('servicios.index', ['empresa_id' => $empresa->id]) }}"
                               class="btn btn-outline-primary">
                               Ver Servicios
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Cuadro de Servicios de la Empresa Seleccionada -->
        <div class="col-md-6">
            <h3>Servicios</h3>
            @if($empresaSeleccionada)
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Turnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->name }}</td>
                            <td>{{ $servicio->description }}</td>
                            <td>${{ number_format($servicio->price, 2) }}</td>
                            <td>{{ $servicio->duration }}</td>
                            <td><a href="{{ route('turnos.seleccion', ['servicio_id' => $servicio->id]) }}" class="btn btn-outline-primary">Solicitar turno</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Selecciona una empresa para ver sus servicios.</p>
            @endif
        </div>
    </div>
</div>
@endsection
