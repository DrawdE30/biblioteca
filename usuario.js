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
                            <button class='btn btn-success' style="font-size:10px;" onclick='editar(${JSON.stringify(u)})'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg></button><br>
                            <button class='btn btn-danger' style="font-size:10px;" onclick='eliminar(${u.idUsuario})'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
</svg></button>
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