document.addEventListener('DOMContentLoaded', function() {
    console.log("Archivo empresa.js cargado");

    // Inicializa el mapa
    var map = L.map('map').setView([-29.14431, -59.2648], 13); // Coordenadas iniciales

    // Cargar un mapa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var marker;
    var isMapEnabled = false; // Estado para habilitar o deshabilitar el mapa

    // Desactivar interacciones del mapa inicialmente
    map.dragging.disable(); // Desactiva el arrastre
    map.touchZoom.disable(); // Desactiva el zoom táctil
    map.doubleClickZoom.disable(); // Desactiva el zoom por doble clic
    map.scrollWheelZoom.disable(); // Desactiva el zoom por desplazamiento de la rueda del ratón

    // Deshabilitar los campos de nombre y dirección inicialmente
    document.getElementById('nombre').disabled = true;
    document.getElementById('direccion').disabled = true;

    // Añadir un marcador cuando el usuario haga clic en el mapa
    map.on('click', function(e) {
        if (isMapEnabled) { // Solo permite el clic si el mapa está habilitado
            if (marker) {
                map.removeLayer(marker); // Si ya hay un marcador, eliminarlo
            }

            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

            // Guardar las coordenadas en campos ocultos para enviarlas al servidor
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        }
    });

    document.getElementById('editEmpresa').addEventListener('click', function() {
        // Habilitar los campos de nombre y dirección
        document.getElementById('nombre').disabled = false;
        document.getElementById('direccion').disabled = false;

        // Mostrar el botón de guardar
        document.getElementById('saveEmpresa').style.display = 'block';

        // Habilitar interacciones del mapa
        isMapEnabled = true; // Cambiar el estado a habilitado

        map.dragging.enable(); // Habilitar el arrastre
        map.touchZoom.enable(); // Habilitar el zoom táctil
        map.doubleClickZoom.enable(); // Habilitar el zoom por doble clic
        map.scrollWheelZoom.enable(); // Habilitar el zoom por desplazamiento de la rueda del ratón
    });
});
