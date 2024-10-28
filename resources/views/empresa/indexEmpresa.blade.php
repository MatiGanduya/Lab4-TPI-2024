@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mi Empresa</span>
                    <button class="btn btn-outline-secondary btn-sm" id="editEmpresa">
                        {{ isset($empresa) ? 'Editar' : '+' }}
                    </button>
                </div>
                <div class="card-body">

                    <form action="{{ route('empresa.guardar') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la empresa"
                                value="{{ isset($empresa) ? $empresa->name : '' }}"
                                {{ isset($empresa) ? '' : 'disabled' }}>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección"
                                value="{{ isset($empresa) ? $empresa->location->address : '' }}"
                                {{ isset($empresa) ? '' : 'disabled' }}>
                        </div>

                        <div class="mb-3">
                            <div id="map" style="width: 100%; height: 200px; background-color: #eaeaea;"></div>
                        </div>

                        <input type="text" id="country" name="country" placeholder="País" required readonly
                            value="{{ isset($empresa) ? $empresa->location->country : '' }}">
                        <input type="text" id="state" name="state" placeholder="Provincia/Estado" required readonly
                            value="{{ isset($empresa) ? $empresa->location->province : '' }}">
                        <input type="text" id="city" name="city" placeholder="Ciudad" required readonly
                            value="{{ isset($empresa) ? $empresa->location->city : '' }}">
                        <input type="text" id="postalCode" name="postalCode" placeholder="Código Postal" required
                            value="{{ isset($empresa) ? $empresa->location->postal_code : '' }}">
                        <input type="hidden" name="latitude" id="latitude" value="{{ isset($empresa) ? $empresa->location->latitude : '' }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ isset($empresa) ? $empresa->location->longitude : '' }}">
                        <input type="hidden" name="id" value="{{ isset($empresa) ? $empresa->id : '' }}">


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
                    <button class="btn btn-outline-secondary btn-sm" id="addServiceButton">+</button>
                </div>
                <div class="card-body">
                    @php
                    // Obtén la empresa asociada al usuario autenticado
                    $empresa = auth()->user()->enterprises->first();
                    @endphp
                    <!-- Cargar y Mostrar Servicios -->
                    @if($empresa && $empresa->services->isNotEmpty())
                    <div class="row">
                        @foreach($empresa->services as $servicio)
                        <div class="col-md-12 mb-3">
                            <div class="card service-card" data-id="{{ $servicio->id }}" data-name="{{ $servicio->name }}" data-description="{{ $servicio->description }}" data-price="{{ $servicio->price }}" data-duration="{{ $servicio->duration }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $servicio->name }}</h5>
                                    <p class="card-text">{{ $servicio->description }}</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Duración: {{ $servicio->duration }}</span>
                                        <span class="text-success font-weight-bold">${{ $servicio->price }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>No tienes servicios disponibles.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal para agregar nuevo servicio -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('servicios.guardar') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Agregar Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="serviceName">Nombre del Servicio</label>
                        <input type="text" class="form-control" id="serviceName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="serviceDescription">Descripción</label>
                        <textarea class="form-control" id="serviceDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="servicePrice">Precio</label>
                        <input type="number" class="form-control" id="servicePrice" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="serviceDuration">Duración</label>
                        <input type="text" class="form-control" id="serviceDuration" name="duration" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar servicio -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('servicios.actualizar') }}" method="POST" id="editServiceForm">
                @csrf
                <input type="hidden" name="_method" value="PUT"> <!-- Método PUT -->
                <input type="hidden" id="editServiceId" name="id"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Editar Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editServiceName">Nombre del Servicio</label>
                        <input type="text" class="form-control" id="editServiceName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editServiceDescription">Descripción</label>
                        <textarea class="form-control" id="editServiceDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editServicePrice">Precio</label>
                        <input type="number" class="form-control" id="editServicePrice" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="editServiceDuration">Duración</label>
                        <input type="text" class="form-control" id="editServiceDuration" name="duration" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts para manejar la lógica de los modales -->
<script>
    document.getElementById('addServiceButton').addEventListener('click', function() {
        $('#addServiceModal').modal('show');
    });

    // Mostrar el modal para editar un servicio
    document.querySelectorAll('.service-card').forEach(function(card) {
        card.addEventListener('click', function() {
            const id = this.getAttribute('data-id'); // ID del servicio
            const name = this.getAttribute('data-name'); // Nombre del servicio
            const description = this.getAttribute('data-description'); // Descripción del servicio
            const price = this.getAttribute('data-price'); // Precio del servicio
            const duration = this.getAttribute('data-duration'); // Duración del servicio

            // Asignar los valores al formulario de edición
            document.getElementById('editServiceId').value = id;
            document.getElementById('editServiceName').value = name;
            document.getElementById('editServiceDescription').value = description;
            document.getElementById('editServicePrice').value = price;
            document.getElementById('editServiceDuration').value = duration;

            $('#editServiceModal').modal('show');
        });
    });

    // Manejador para habilitar el formulario de empresa
    document.getElementById('editEmpresa').addEventListener('click', function() {
        document.getElementById('nombre').disabled = false;
        document.getElementById('direccion').disabled = false;
        document.getElementById('saveEmpresa').style.display = 'block';
    });
</script>

@endsection
