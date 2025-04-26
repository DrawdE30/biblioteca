const form = document.getElementById("form");
const tableBody = document.getElementById("tableBody");
const rootPhp = "./usuarioController.php";

let editando = false;

form.addEventListener('submit', e => {
    e.preventDefault();

    let formData = Object.fromEntries(new FormData(form));
    const selectObject = document.getElementById("roles");
    let roles = [];
    for (var i = 0; i < selectObject.options.length; i++) {
        if (selectObject.options[i].selected == true) {
            roles.push(selectObject.options[i].value)
        }
    }
    formData.roles = roles
    fetch(`${rootPhp}?action=insertar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
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
        listarUsuarios();
    })
    .catch(err => {
        console.error("❌ Error al guardar usuario:", err);
        alert("Error al guardar usuario");
    });
});

function listarRol() {
    fetch(`${rootPhp}?action=listar_roles`)
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar roles');
            return res.json();
        })
        .then(data => {
            const rolesSelect = document.getElementById('roles');
            rolesSelect.innerHTML = '';
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.idRol;
                option.textContent = rol.nombre;
                rolesSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("🚀 ~ listarRol ~ error:", error);
            alert("Ocurrió un error al cargar la lista de roles.");
        });
}

function listarUsuarios() {
    fetch(`${rootPhp}?action=listar`)
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar usuario');
            return res.json();
        })
        .then(data => {
            tableBody.innerHTML = '';
            data.forEach(u => {
                const nombresRoles = u.roles.map(rol => rol.nombre).join(', ')
                tableBody.innerHTML += `
                    <tr>
                        <td>${u.idUsuario}</td>
                        <td>${u.nombre}</td>
                        <td>${u.usuario}</td>
                        <td>${nombresRoles}</td>
                        <td>
                            <button onclick='editar(${JSON.stringify(u)})'>✏️</button>
                            <button onclick='eliminar(${u.idUsuario})'>❌</button>
                        </td>
                    </tr>`;
            });
        })
        .catch(error => {
            console.log("🚀 ~ listarUsuarios ~ error:", error)
            alert("Ocurrió un error al cargar la lista de usuarios.");
        });
}

function editar(u) {
    form.idUsuario.value = u.idUsuario;
    form.nombre.value = u.nombre;
    form.usuario.value = u.usuario;
    form.password.value = u.password;
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
            if (res.status === "ok") listarUsuarios();
            else alert("Error al eliminar");
        });
}

listarUsuarios();
listarRol();