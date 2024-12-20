@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-4">
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
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción de la empresa"
                                {{ isset($empresa) ? '' : 'disabled' }}>{{ isset($empresa) ? $empresa->description : '' }}</textarea>
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

                        <input type="text" id="country" name="country" placeholder="País" required
                            value="{{ isset($empresa) ? $empresa->location->country : '' }}">
                        <input type="text" id="state" name="state" placeholder="Provincia/Estado" required
                            value="{{ isset($empresa) ? $empresa->location->province : '' }}">
                        <input type="text" id="city" name="city" placeholder="Ciudad" required
                            value="{{ isset($empresa) ? $empresa->location->city : '' }}">
                        <input type="text" id="postalCode" name="postalCode" placeholder="Código Postal"
                            value="{{ isset($empresa) ? $empresa->location->postal_code : '' }}">
                        <input type="hidden" name="latitude" id="latitude" value="{{ isset($empresa) ? $empresa->location->latitude : '' }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ isset($empresa) ? $empresa->location->longitude : '' }}">
                        <input type="hidden" name="id" value="{{ isset($empresa) ? $empresa->id : '' }}" id="empresaId">

                        <button class="btn btn-primary" id="saveEmpresa" style="display: none;">Guardar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Colaboradores</span>
                    @if(auth()->user()->user_type === 'admin')
                        <!-- Mostrar el botón solo si el usuario autenticado es admin -->
                        <button class="btn btn-outline-secondary btn-sm" id="addCollaboratorButton"
                                {{ !$empresa ? 'disabled' : '' }} style="{{ !$empresa ? 'opacity: 0.5;' : '' }}">
                            +
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($empresa)
                        <!-- Input oculto para el ID de la empresa -->
                        <input type="hidden" id="empresaId" value="{{ $empresa->id }}">

                        <ul class="list-group" id="collaboratorsList">
                            <!-- Iterar sobre los colaboradores -->
                            @foreach ($empresa->users as $user)
                                <li id="collaborator-{{ $user->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="flex-grow-1">{{ $user->name }}</span>

                                    <!-- Botón para eliminar -->
                                    @if($user->user_type === 'employee' && auth()->user()->user_type === 'admin')
                                        <button class="btn btn-sm btn-danger d-inline-flex align-items-center"
                                                onclick="eliminarColaborador({{ $user->id }})" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
                                        </button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No tienes colaboradores asignados.</p>
                    @endif
                </div>
            </div>
        </div>





        <!-- Tarjeta Mis Servicios -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mis servicios</span>
                    @if(auth()->user()->user_type === 'admin')
                    <button class="btn btn-outline-secondary btn-sm" id="addServiceButton"
                        {{ !$empresa ? 'disabled' : '' }} style="{{ !$empresa ? 'opacity: 0.5;' : '' }}">+</button>
                    @endif
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
                                        <span class="text-muted">Duración: {{ date('H:i', strtotime($servicio->duration)) }}</span>
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

                    <!-- Selector de Duración -->
                    <div class="form-group">
                        <label for="serviceDuration">Duración</label>
                        <div class="row">
                            <!-- Selector de horas -->
                            <div class="col-6">
                                <label for="serviceHours">Horas</label>
                                <select class="form-control" id="serviceHours" name="hours" required>
                                    <option value="0">0</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Selector de minutos -->
                            <div class="col-6">
                                <label for="serviceMinutes">Minutos</label>
                                <select class="form-control" id="serviceMinutes" name="minutes" required>
                                    <option value="0">0 minutos</option>
                                    <option value="15">15 minutos</option>
                                    <option value="30">30 minutos</option>
                                    <option value="45">45 minutos</option>
                                </select>
                            </div>
                        </div>
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
                        <select class="form-control" id="editServiceDuration" name="duration" required>
                            <!-- Aquí se llenan los intervalos de tiempo -->
                            @foreach(range(0, 23) as $hour)
                                @foreach([0, 15, 30, 45] as $minute)
                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                                        {{ sprintf('%02d:%02d', $hour, $minute) }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
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

<!-- Modal de selección de colaboradores -->
<div class="modal fade" id="addCollaboratorModal" tabindex="-1" aria-labelledby="addCollaboratorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCollaboratorModalLabel">Seleccionar Colaborador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de búsqueda -->
                <input type="text" id="searchUser" class="form-control" placeholder="Buscar usuario..." onkeyup="buscarUsuarios()">
                <ul class="list-group mt-2" id="userList">
                    <!-- Los usuarios se cargarán dinámicamente aquí -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="dataContainer" data-url="{{ route('usuarios.noAsignados') }}"></div>
<div id="dataContainer1" data-url="{{ route('colaborador.agregar') }}"></div>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
        body {
        background-color: #2C3E50;
        color: #ECF0F1;
    }
</style>

@endsection
