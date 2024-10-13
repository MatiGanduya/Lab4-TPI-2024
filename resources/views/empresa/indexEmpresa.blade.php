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
                    <!-- Formulario para editar y guardar la empresa -->
                    <form action="{{ route('empresa.guardar') }}" method="POST">
                        @csrf <!-- Token de seguridad para formularios en Laravel -->

                        <!-- Nombre de la empresa -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la empresa" disabled>
                        </div>

                        <!-- Dirección de la empresa -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" disabled>
                        </div>

                        <!-- Mapa interactivo -->
                        <div class="mb-3">
                            <div id="map" style="width: 100%; height: 200px; background-color: #eaeaea;"></div>
                        </div>

                        <!-- Campos ocultos para las coordenadas -->
                        <input type="text" id="country" name="country" placeholder="País" required readonly>
                        <input type="text" id="state" name="state" placeholder="Provincia/Estado" required readonly>
                        <input type="text" id="city" name="city" placeholder="Ciudad" required readonly>
                        <input type="text" id="postalCode" name="postalCode" placeholder="Código Postal" required>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <!-- Botón para guardar los cambios -->
                        <button class="btn btn-primary" id="saveEmpresa" style="display: none;">Guardar</button>
                    </form>
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
