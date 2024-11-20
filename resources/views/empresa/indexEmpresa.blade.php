@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mi Empresa</span>
                    <!-- Botón en la tarjeta "Mi Empresa" -->
                    <button class="btn btn-outline-secondary btn-sm" id="addCollaboratorButton"
                        {{ !$empresa ? 'disabled' : '' }}
                        style="{{ !$empresa ? 'opacity: 0.5;' : '' }}"
                        data-bs-toggle="modal"
                        data-bs-target="#manageCollaboratorsModal">
                        Colaboradores
                    </button>
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
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción de la empresa"
                                {{ isset($empresa) ? 'disabled' : 'disabled' }}>{{ isset($empresa) ? $empresa->description : '' }}</textarea>
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
                    <button class="btn btn-outline-secondary btn-sm" id="addServiceButton"
                        {{ !$empresa ? 'disabled' : '' }} style="{{ !$empresa ? 'opacity: 0.5;' : '' }}">+</button>
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
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('servicios.guardar') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Agregar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar colaborador -->
<div class="modal fade" id="manageCollaboratorsModal" tabindex="-1" aria-labelledby="manageCollaboratorsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageCollaboratorsModalLabel">Gestionar Colaboradores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar colaborador -->
                <form id="addCollaboratorForm">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Seleccionar Cliente</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="" disabled selected>Selecciona un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->name }} ({{ $cliente->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="enterprise_id" value="{{ $empresa->id }}">
                    <button type="submit" class="btn btn-primary">Agregar Colaborador</button>
                </form>
                <hr>
                <!-- Tabla para listar colaboradores -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <h6>Lista de Colaboradores</h6>
                <table class="table table-bordered" id="collaboratorsTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collaborators as $colaborador)
                            <tr>
                                <td>{{ $colaborador->name }}</td>
                                <td>{{ $colaborador->email }}</td>
                                <td>
                                    <form action="{{ route('deleteCollaborator') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $colaborador->id }}">
                                        <input type="hidden" name="enterprise_id" value="{{ $empresa->id }}">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para manejar la lógica de los modales -->
<script>
    // Mostrar el modal para editar un servicio
    document.getElementById('addCollaboratorForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita el envío normal del formulario

    const formData = new FormData(this); // Recoge los datos del formulario

    fetch("{{ route('addCollaborator') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest", // Indica que es una solicitud AJAX
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Muestra el mensaje
                location.reload(); // Recarga la página para asegurar que todo esté actualizado
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al agregar el colaborador.');
        });


    });

        // Evento para eliminar colaborador
        document.querySelectorAll('.delete-collaborator-form').forEach(form => {
    form.addEventListener('submit', async function (e) {
        e.preventDefault(); // Detener el envío tradicional del formulario

        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            });

            if (!response.ok) throw new Error('Error en la solicitud');

            const data = await response.json();

            if (data.success) {
                alert(data.message); // Mostrar mensaje de éxito
                const userId = formData.get('user_id');
                const row = document.getElementById(`row-${userId}`);
                if (row) row.remove(); // Eliminar la fila de la tabla
            } else {
                alert(data.message); // Mostrar mensaje de error
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Hubo un error al eliminar el colaborador.');
        }
    })
    });
</script>
@endsection
