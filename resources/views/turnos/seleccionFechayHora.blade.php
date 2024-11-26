@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Calendario FullCalendar -->
        <div class="col-md-6">
            <div id="calendar"></div>
        </div>

        <!-- Formulario de Selección de Fecha y Hora -->
        <div class="col-md-6">
            <h3>Seleccionar Fecha y Hora para el Servicio: {{ $servicio->name }}</h3>

            <form action="{{ route('turnos.confirmar', ['servicio_id' => $servicio->id, 'usuario_colaborador_id' => $colaborador]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>

                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <select class="form-control" id="hora" name="hora" required>

                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Confirmar Turno</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.15/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Vista mensual inicial
        selectable: true, // Permitir la selección de fechas
        dateClick: function(info) {
            // Al hacer clic en una fecha, mostrarla en el formulario
            document.getElementById('fecha').value = info.dateStr;

            // Obtener el ID del servicio
            var servicio_id = {{ $servicio->id }};  // El ID del servicio
            var usuario_colaborador_id = {{ $colaborador->id }};
            var fecha = info.dateStr; // La fecha seleccionada

            // Realizar la solicitud AJAX para obtener las horas disponibles
            fetch(`/turnos/seleccion/${servicio_id}/${usuario_colaborador_id}/${fecha}`)
            .then(response => response.text())  // Cambiar a response.text() para ver la respuesta sin procesar
            .then(data => {
        console.log(data);  // Imprime la respuesta para ver qué está devolviendo el servidor
        try {
            var jsonData = JSON.parse(data);  // Intentamos analizar los datos manualmente
            var horaSelect = document.getElementById('hora');
            horaSelect.innerHTML = '';

            jsonData.forEach(function(hora) {
                var option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                horaSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al analizar el JSON:', error);
        }
    })
    .catch(error => console.error('Error al obtener las horas:', error));
        },
    });

    calendar.render(); // Renderizar el calendario
});

    </script>
@endsection
