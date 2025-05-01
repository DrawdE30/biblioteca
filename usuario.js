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
    const data = JSON.stringify(formData);
    fetch(`${rootPhp}?action=insertar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
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
            listarUsuarios();
            mostrarTabla();
        })
        .catch(err => {
            console.error("❌ Error al guardar usuario:", err);
            alert("Error al guardar usuario");
        });
});
function mostrarTabla() {
    document.getElementById("form").reset();
    $("#roles").selectpicker('val', []);
    document.getElementById("box-data").classList.add("d-none");
    document.getElementById("box-list").classList.remove("d-none");
}

function listarRol() {
    fetch(`${rootPhp}?action=listar_roles`)
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar roles');
            return res.json();
        })
        .then(data => {
            roles = data;
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
                const nombresRoles = u.roles.map(rol => rol.nombres).join(', ')
                tableBody.innerHTML += `
                    <tr>
                        <td>${u.idUsuario}</td>
                        <td>${u.nombres}</td>
                        <td>${u.correo}</td>
                        <td>${u.usuario}</td>
                        <td>${nombresRoles}</td>
                        <td>
                            <button class='btn btn-outline-success btn-sm' style="font-size:10px;" onclick='editar(${JSON.stringify(u)})'>✏️</button>
                            <button class='btn btn-outline-danger btn-sm' style="font-size:10px;" onclick='eliminar(${u.idUsuario})'>❌</button>
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
    document.getElementById("box-data").classList.remove("d-none");
    document.getElementById("box-list").classList.add("d-none");
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
            if (res.status === "ok") listarUsuarios();
            else alert("Error al eliminar");
        });
}

listarUsuarios();
listarRol();