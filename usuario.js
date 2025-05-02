const form = document.getElementById("form");
const tableBody = document.getElementById("tableBody");
const rootPhp = "./usuarioController.php";

// Insertar usuario
form.addEventListener('submit', e => {
    e.preventDefault();

    let formData = Object.fromEntries(new FormData(form));

    // Asignamos el rol "Cliente" automáticamente
    formData.roles = ["Cliente"];  // Asignar rol "Cliente" por defecto

    const data = JSON.stringify(formData);

    fetch(`${rootPhp}?action=insertar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: data
    })
        .then(res => {
            if (!res.ok) {
                console.error("Error en la respuesta del servidor:", res.status, res.statusText);
                return res.text().then(text => { throw new Error(`Error en la respuesta: ${text}`); });
            }
            return res.text(); // Obtén la respuesta como texto
        })
        .then(responseText => {
            console.log("Respuesta del servidor (texto):", responseText);
            return JSON.parse(responseText); // Intenta parsear el texto como JSON
        })
        .then(resp => {
            console.log("✅ Guardado:", resp);
            alert("Usuario guardado correctamente");
            form.reset();
            listarUsuarios();  // Actualizamos la lista de usuarios
        })
        .catch(err => {
            console.error("❌ Error al guardar usuario:", err);
            alert("Error al guardar usuario");
        });
});

// Función para listar clientes
function listarUsuarios() {
    fetch(`${rootPhp}?action=listar`)
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = '';  // Limpiar la tabla antes de añadir nuevos datos
            data.forEach(u => {
                const nombresRoles = u.roles.map(rol => rol.nombres).join(', ');
                tableBody.innerHTML += `
                    <tr>
                        <td>${u.idUsuario}</td>
                        <td>${u.nombres}</td>
                        <td>${u.correo}</td>
                        <td>${u.usuario}</td>
                        <td>${nombresRoles}</td>
                    </tr>`;
            });
        })
        .catch(error => {
            console.log("🚀 ~ listarUsuarios ~ error:", error)
            alert("Ocurrió un error al cargar la lista de usuarios.");
        });
}

document.addEventListener('DOMContentLoaded', listarUsuarios);

function mostrarTabla() {
    document.getElementById("form").reset();
    $("#roles").selectpicker('val', []);  // Limpia la selección
    document.getElementById("box-data")?.classList.add("d-none");
    document.getElementById("box-list")?.classList.remove("d-none");
}

function listarRoles() {
    fetch('get_roles.php')  // Asegúrate de que esta ruta sea correcta
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar roles');
            return res.json();
        })
        .then(data => {
            const rolesSelect = document.getElementById('roles');
            rolesSelect.innerHTML = '';  // Limpiar el select
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.idRol;
                option.textContent = rol.nombre;
                rolesSelect.appendChild(option);
            });
            $('.selectpicker').selectpicker('refresh');
        })
        .catch(error => {
            console.error("🚀 ~ listarRoles ~ error:", error);
            alert("Ocurrió un error al cargar la lista de roles.");
        });
}

document.addEventListener('DOMContentLoaded', listarRoles);


function listarRol() {
    fetch(`${rootPhp}?action=listar_roles`)
        .then(res => res.json())
        .then(data => {
            const rolesSelect = document.getElementById('roles');
            rolesSelect.innerHTML = '';
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.idRol;
                option.textContent = rol.nombre;
                rolesSelect.appendChild(option);
            });
            $('.selectpicker').selectpicker('refresh');
        })
        .catch(error => {
            console.error("Error al listar roles:", error);
            alert("Error al cargar la lista de roles.");
        });
}


function editar(u) {
    document.getElementById("box-data")?.classList.remove("d-none");
    document.getElementById("box-list")?.classList.add("d-none");
    form.idUsuario.value = u.idUsuario;
    form.nombres.value = u.nombres;
    form.usuario.value = u.usuario;
    form.password.value = u.password;
    form.correo.value = u.correo;
    $("#roles").selectpicker('val', u.roles.map(r => r.idRol));
    editando = true;
}

function eliminar(idUsuario) {
    if (!confirm("¿Eliminar usuario?")) return;

    fetch(`${rootPhp}?action=eliminar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ idUsuario })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) listarUsuarios();
        else alert("Error al eliminar usuario");
    });
}

document.addEventListener('DOMContentLoaded', () => {
    listarUsuarios();
    listarRol();
});
