@extends('layouts.layout')

@section('content')
<div class="container mt-5">

    <a href="#" class="btn btn-outline-success">Mis turnos</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Servicio</th>
                <th>Empresa</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Duración</th>
                <th></th> <!-- Columna para el botón -->
            </tr>
        </thead>
        <tbody>
            @foreach($servicios as $servicio)
            <tr>
                <td>{{ $servicio->service_name }}</td>
                <td>{{ $servicio->enterprise_name }}</td>
                <td>{{ $servicio->description }}</td>
                <td>${{ number_format($servicio->price, 2) }}</td> <!-- Formateo de precio -->
                <td>{{ $servicio->duration }}</td>
                <td>
                    <!-- Botón para solicitar turno -->
                    <a href="#" class="btn btn-outline-primary">Solicitar turno</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
