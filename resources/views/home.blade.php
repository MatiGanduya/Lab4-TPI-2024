@extends('layouts.layout')

@section('content')

<div class="container h-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="d-grid gap-3">
            <a href="{{ Route('servicios.indexServicios') }}" class="btn btn-primary btn-lg">Solicitar un turno</a>
            <a href="#" class="btn btn-primary btn-lg">Mis turnos</a>
            <a href="{{ Route('empresa.indexEmpresa') }}" class="btn btn-info btn-lg">Mi Empresa y Servicios</a>
        </div>
    </div>
</div>

 @endsection
