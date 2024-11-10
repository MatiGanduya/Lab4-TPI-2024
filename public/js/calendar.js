document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        locale: 'es',
        dayCellClassNames: function(arg) {
            const today = new Date().toISOString().split('T')[0];
            if (arg.dateStr < today) {
                return ['fc-disabled-day'];
            }
        },
        selectAllow: function(selectInfo) {
            const today = new Date().toISOString().split('T')[0];
            return selectInfo.startStr >= today;
        },
        select: function(info) {
            document.getElementById('fecha').value = info.startStr;

            // Filtrar las horas según la fecha seleccionada
            const selectedDate = new Date(info.startStr);  // Asegura que sea un objeto Date
            const dayOfWeekNumber = selectedDate.getUTCDay(); // Obtiene el día de la semana (0 = domingo, 1 = lunes, etc.)

            const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const dayOfWeek = daysOfWeek[dayOfWeekNumber]; // Mapea el número al nombre del día

            console.log('Día seleccionado:', dayOfWeek);  // Verifica el día de la semana

            // Obtener los datos de disponibilidad desde la variable global
            const disponibilidad = window.disponibilidad; // Accede a los datos pasados desde Blade
            console.log(window.disponibilidad); // Verifica cómo están estructurados los datos

            // Filtrar las horas según el día seleccionado
            const availableHours = disponibilidad.filter(function(d) {
                // Aseguramos que la comparación de días sea insensible a mayúsculas/minúsculas
                return d.day_of_week.toLowerCase() === dayOfWeek.toLowerCase();
            });

            console.log('Horas disponibles:', availableHours); // Verifica las horas disponibles filtradas
            console.log('Día seleccionado:', dayOfWeek);
            console.log('Disponibilidad:', disponibilidad.map(d => d.day_of_week)); // Muestra todos los valores de day_of_week

            // Mostrar los bloques de hora disponibles
            const horaContainer = document.getElementById('hora-container');
            horaContainer.innerHTML = ''; // Limpia los bloques anteriores

            if (availableHours.length > 0) {
                availableHours.forEach(function(hour) {
                    const block = document.createElement('div');
                    block.classList.add('hora-bloque');
                    block.textContent = hour.start_time + ' - ' + hour.end_time;

                    block.onclick = function() {
                        document.getElementById('hora').value = hour.start_time; // Asigna la hora seleccionada
                    };

                    horaContainer.appendChild(block);
                });
            } else {
                horaContainer.innerHTML = '<p>No hay horarios disponibles para este día.</p>';
            }
        }
    });
    calendar.render();
});
