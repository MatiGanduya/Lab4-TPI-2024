document.addEventListener('DOMContentLoaded', function () {
    console.log("Archivo empresa.js cargado");
    console.log("Entro a js de colaboradores");

    const empresaId = document.getElementById('empresaId').value;
    console.log("ID de la empresa desde el campo oculto:", empresaId);

    // if (empresaId) {
    //     fetch(`/colaboradores/${empresaId}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             const collaboratorsList = document.getElementById('collaboratorsList');
    //             collaboratorsList.innerHTML = '';

    //             if (data.length > 0) {
    //                 data.forEach(user => {
    //                     const listItem = document.createElement('li');
    //                     listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
    //                     listItem.innerHTML = `
    //                         ${user.name}

    //                     `;
    //                     collaboratorsList.appendChild(listItem);
    //                 });
    //             } else {
    //                 collaboratorsList.innerHTML = '<p>No hay colaboradores registrados.</p>';
    //             }
    //         })
    //         .catch(error => console.error('Error al cargar colaboradores:', error));
    // }

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


    const addCollaboratorButton = document.getElementById('addCollaboratorButton');
    const addCollaboratorModal = new bootstrap.Modal(document.getElementById('addCollaboratorModal'));

    // Abrir el modal al hacer clic en el botón de agregar colaborador
    addCollaboratorButton.addEventListener('click', function () {
        addCollaboratorModal.show();
        cargarUsuarios(); // Cargar usuarios cuando se abre el modal
    });



});

// Función para cargar los usuarios que no están asignados a ninguna empresa
function cargarUsuarios() {
    var usuariosNoAsignadosUrl = document.getElementById('dataContainer').getAttribute('data-url');

    fetch(usuariosNoAsignadosUrl)
        .then(response => response.json())
        .then(data => {
            let userList = document.getElementById('userList');
            userList.innerHTML = ''; // Limpiar la lista antes de agregar nuevos elementos
            data.forEach(user => {
                let listItem = document.createElement('li');
                listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                listItem.innerHTML = `
                    ${user.name} <button class="btn btn-sm btn-primary" onclick="agregarColaborador(${user.id})">Agregar</button>
                `;
                userList.appendChild(listItem);
            });
        })
        .catch(error => {
            console.error('Error al cargar usuarios:', error);
            alert('Hubo un error al cargar los usuarios.');
        });
}



// Función para agregar un colaborador
function agregarColaborador(userId) {
    var usuariosSeleccionadosUrl = document.getElementById('dataContainer1').getAttribute('data-url');
    const empresaId = document.getElementById('empresaId').value; // Obtén el ID de la empresa

    // Datos que se enviarán al servidor
    const data = {
        user_id: userId,
        enterprise_id: empresaId
    };

    // Realizar la solicitud POST
    fetch(usuariosSeleccionadosUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // Asegúrate de incluir el token CSRF
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            cargarUsuarios(); // Recargar la lista de usuarios
        } else {
            alert('Error desconocido.');
        }
    })
    .catch(error => {
        console.error('Error al agregar colaborador:', error);
        alert('Hubo un error al agregar al colaborador.');
    });
}


// Función para buscar usuarios
function buscarUsuarios() {
    let searchQuery = document.getElementById('searchUser').value.toLowerCase();
    let userListItems = document.getElementById('userList').getElementsByTagName('li');

    Array.from(userListItems).forEach(item => {
        let userName = item.textContent.toLowerCase();
        if (userName.indexOf(searchQuery) === -1) {
            item.style.display = 'none';
        } else {
            item.style.display = '';
        }
    });
}

// Función para eliminar un colaborador
function eliminarColaborador(userId) {
    const empresaId = document.getElementById('empresaId').value; // Asegúrate de tener este valor en un input oculto
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/empresa/${empresaId}/colaborador/${userId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || 'Hubo un error al eliminar al colaborador.');
            });
        }
        return response.json();
    })
    .then(() => {
        const collaboratorItem = document.getElementById(`collaborator-${userId}`);
        if (collaboratorItem) {
            collaboratorItem.remove();
        }
    })
    .catch(error => {
        console.error('Error al eliminar colaborador:', error);
        alert(error.message || 'Hubo un error al eliminar al colaborador.');
    });
}



