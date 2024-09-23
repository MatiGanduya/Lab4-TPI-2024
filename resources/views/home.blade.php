@extends('layouts.layout')

@section('content')

<div class="container h-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <h1 class="mb-5">Bienvenido a la Página de Inicio</h1>

        <div class="d-grid gap-3">
            <a href="{{ Route('turnos.indexTurnos') }}" class="btn btn-primary btn-lg">Turnos</a>
            <a href="{{ Route('servicios.indexServicios') }}" class="btn btn-secondary btn-lg">Servicios</a>
        </div>
    </div>
</div>

 @endsection
