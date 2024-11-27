@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Cuadro de Empresas -->
        <div class="col-md-6">
            <h3 class="text-center" style="color: #ffffff;">Empresas</h3>
            <table class="table table-bordered">
                <thead style="background-color: #0069d9; color: white;">
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
                               class="btn btn-outline-primary custom-btn">
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
            <h3 class="text-center" style="color: #ffffff;">Servicios</h3>
            @if($empresaSeleccionada)
                <table class="table table-bordered">
                    <thead style="background-color: #0069d9; color: white;">
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
                            <td>
                                <button class="btn custom-btn" data-bs-toggle="modal"
                                        data-bs-target="#modalColaboradores"
                                        data-servicio-id="{{ $servicio->id }}"
                                        data-empresa-id="{{ $empresaSeleccionada->id }}">
                                        Solicitar turno
                                </button>
                            </td>
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

<!-- Modal para Colaboradores -->
<div class="modal fade" id="modalColaboradores" tabindex="-1" aria-labelledby="modalColaboradoresLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2C3E50; color: white;">
                <h5 class="modal-title" id="modalColaboradoresLabel">Colaboradores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Aquí puedes incluir información adicional sobre los colaboradores o el servicio seleccionado.</p>
                <!-- Puedes cargar colaboradores dinámicamente con JavaScript o backend -->
            </div>
            <div class="modal-footer" style="background-color: #2C3E50; color: white;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="confirmTurno" class="btn custom-btn">Confirmar turno</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Colores personalizados acordados */
    :root {
        --primary-color: #0069d9;
        --secondary-color: #1d72b8;
        --text-color: #333;
        --button-bg-color: #0069d9;
        --button-text-color: #ffffff;
        --button-hover-bg-color: #0056b3;
        --background-color: #2C3E50; /* Fondo principal */
    }

    /* Fondo global */
    body {
        background-color: var(--background-color);
        color: white;
    }

    .custom-btn {
        background-color: var(--button-bg-color);
        color: var(--button-text-color);
        border: none;
        transition: background-color 0.3s ease;
    }

    .custom-btn:hover {
        background-color: var(--button-hover-bg-color);
    }

    table th, table td {
        text-align: center;
        vertical-align: middle;
    }

    .modal-header, .modal-footer {
        background-color: var(--primary-color);
    }

    .modal-title {
        color: white;
    }

    .text-center {
        color: var(--text-color);
    }

    h3 {
        color: white; /* Color para los encabezados */
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalColaboradores = document.getElementById('modalColaboradores');
        const modalBody = modalColaboradores.querySelector('.modal-body');
        let selectedColaborador = null;
        let servicioId = ""; // Inicializamos como vacío

        modalColaboradores.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const empresaId = button.getAttribute('data-empresa-id');
            servicioId = button.getAttribute('data-servicio-id'); // Asignamos el servicio_id cuando se hace clic en "Solicitar turno"
            modalBody.innerHTML = '<p>Cargando colaboradores...</p>'; // Placeholder mientras se cargan los datos

            // Cargar los colaboradores de la empresa
            fetch(`/colaboradores/${empresaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Crear una lista de colaboradores con opción para seleccionar
                        const list = data.map(colaborador => {
                            return `
                                <div class="colaborador-item" data-id="${colaborador.id}" data-name="${colaborador.name}">
                                    <button class="btn btn-outline-primary" style="width: 100%">${colaborador.name}</button>
                                </div>
                            `;
                        }).join('');
                        modalBody.innerHTML = list;

                        // Añadir evento de selección a cada colaborador
                        const colaboradorItems = modalBody.querySelectorAll('.colaborador-item');
                        colaboradorItems.forEach(item => {
                            item.addEventListener('click', () => {
                                // Marcar al colaborador como seleccionado
                                if (selectedColaborador) {
                                    selectedColaborador.classList.remove('selected');
                                }
                                selectedColaborador = item;
                                selectedColaborador.classList.add('selected'); // Resaltar el seleccionado
                                const colaboradorName = item.getAttribute('data-name');
                                const colaboradorId = item.getAttribute('data-id');
                                document.getElementById('confirmTurno').setAttribute('data-colaborador-id', colaboradorId);
                                document.getElementById('confirmTurno').textContent = `Confirmar turno con ${colaboradorName}`;

                                // Cambiar el comportamiento del botón para redirigir
                                document.getElementById('confirmTurno').addEventListener('click', () => {
                                    if (servicioId && colaboradorId) {
                                        // Redirigir a la ruta seleccionada con los parámetros servicio_id y usuario_colaborador_id
                                        window.location.href = `/turnos/seleccion-fecha-hora/${servicioId}/${colaboradorId}`;
                                    } else {
                                        alert('Por favor selecciona un servicio y colaborador.');
                                    }
                                });
                            });
                        });
                    } else {
                        modalBody.innerHTML = '<p>No hay colaboradores disponibles.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los colaboradores:', error);
                    modalBody.innerHTML = '<p>Error al cargar los colaboradores.</p>';
                });
        });
    });
</script>


@endsection
