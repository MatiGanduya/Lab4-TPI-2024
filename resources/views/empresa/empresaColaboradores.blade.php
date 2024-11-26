@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Gestionar Colaboradores</h1>

            <!-- Formulario para agregar colaborador -->
            <form id="addCollaboratorForm" action="{{ route('addCollaborator') }}" method="POST" >
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

            <!-- Mensajes de éxito/error -->
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

            <!-- Tabla para listar colaboradores -->
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
                        <tr id="row-{{ $colaborador->id }}">
                            <td>{{ $colaborador->name }}</td>
                            <td>{{ $colaborador->email }}</td>
                            <td>
                                <form class="delete-collaborator-form" action="{{ route('deleteCollaborator') }}" method="POST">
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
@endsection

    <script>
    document.getElementById('addCollaboratorForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("{{ route('addCollaborator') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al agregar el colaborador.');
            });
    });
</script>
<script>

    // Eliminar colaborador
    document.querySelectorAll('.delete-collaborator-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

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
                    alert(data.message);
                    const userId = formData.get('user_id');
                    const row = document.getElementById(`row-${userId}`);
                    if (row) row.remove();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Hubo un error al eliminar el colaborador.');
            }
        });
    });
</script>
