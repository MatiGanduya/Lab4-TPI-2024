@extends('layouts.layout')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Tarjeta Mi Empresa -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mi Empresa</span>
                    <button class="btn btn-outline-secondary btn-sm" id="editEmpresa">+</button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" placeholder="Nombre de la empresa" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" placeholder="Dirección" disabled>
                    </div>
                    <!-- Espacio para un mapa interactivo -->
                    <div class="mb-3">
                        <div id="map" style="width: 100%; height: 200px; background-color: #eaeaea;"></div>
                    </div>
                    <button class="btn btn-primary" id="saveEmpresa" style="display: none;">Guardar</button>
                </div>
            </div>
        </div>

        <!-- Tarjeta Mis Servicios -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mis servicios</span>
                    <button class="btn btn-outline-secondary btn-sm">+</button>
                </div>
                <div class="card-body">
                    <!-- Aquí se irán cargando los servicios dinámicamente -->
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Servicio 1" disabled>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Servicio 2" disabled>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Servicio 3" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
