document.addEventListener('DOMContentLoaded', function() {
    console.log("Archivo empresa.js cargado");

    var map = L.map('map').setView([-29.14431, -59.2648], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var marker;
    var isMapEnabled = false;

    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();

    document.getElementById('nombre').disabled = true;
    document.getElementById('direccion').disabled = true;

    map.on('click', function(e) {
        if (isMapEnabled) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${e.latlng.lat}&lon=${e.latlng.lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    const country = data.address.country;
                    const state = data.address.state;
                    const city = data.address.city || data.address.town || data.address.village;
                    const postalCode = data.address.postcode;

                    document.getElementById('country').value = country;
                    document.getElementById('state').value = state;
                    document.getElementById('city').value = city;
                    document.getElementById('postalCode').value = postalCode;
                })
                .catch(error => console.error('Error al obtener datos geográficos:', error));
        }
    });

    document.getElementById('editEmpresa').addEventListener('click', function() {

        document.getElementById('nombre').disabled = false;
        document.getElementById('direccion').disabled = false;

        document.getElementById('saveEmpresa').style.display = 'block';

        isMapEnabled = true;

        map.dragging.enable();
        map.touchZoom.enable();
        map.doubleClickZoom.enable();
        map.scrollWheelZoom.enable();
    });
});
