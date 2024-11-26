document.addEventListener('DOMContentLoaded', function () {
    console.log("Archivo empresa.js cargado");
    console.log("Entro a js de colaboradores");

    const empresaId = document.getElementById('empresaId').value;
    console.log("ID de la empresa desde el campo oculto:", empresaId);

    if (empresaId) {
        fetch(`/colaboradores/${empresaId}`)
            .then(response => response.json())
            .then(data => {
                const collaboratorsList = document.getElementById('collaboratorsList');
                collaboratorsList.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(user => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        listItem.innerHTML = `
                            ${user.name}
                            <span class="badge bg-primary rounded-pill">${user.user_type}</span>
                        `;
                        collaboratorsList.appendChild(listItem);
                    });
                } else {
                    collaboratorsList.innerHTML = '<p>No hay colaboradores registrados.</p>';
                }
            })
            .catch(error => console.error('Error al cargar colaboradores:', error));
    }

    // El resto del código aquí
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

    map.on('click', function (e) {
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

    document.getElementById('editEmpresa').addEventListener('click', function () {
        document.getElementById('nombre').disabled = false;
        document.getElementById('direccion').disabled = false;

        document.getElementById('saveEmpresa').style.display = 'block';

        isMapEnabled = true;

        map.dragging.enable();
        map.touchZoom.enable();
        map.doubleClickZoom.enable();
        map.scrollWheelZoom.enable();
    });

    // Mostrar el modal para editar un servicio
    document.querySelectorAll('.service-card').forEach(function(card) {
        card.addEventListener('click', function() {
            console.log("Checkpoint 1: JS funcionando");
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
        console.log("Checkpoint 2: JS funcionando");
        document.getElementById('nombre').disabled = false;
        document.getElementById('descripcion').disabled = false;
        document.getElementById('direccion').disabled = false;
        document.getElementById('saveEmpresa').style.display = 'block';
    });

    document.getElementById('addServiceButton').addEventListener('click', function() {
        console.log("Checkpoint 3: JS funcionando");
    var myModal = new bootstrap.Modal(document.getElementById('addServiceModal'));
    myModal.show();
    });




});
