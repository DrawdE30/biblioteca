const form = document.getElementById("form");
const tableBody = document.getElementById("tableBody");
const rootPhp = "./usuarioController.php";
let editando = false;

form?.addEventListener('submit', e => {
    e.preventDefault();
    let formData = Object.fromEntries(new FormData(form));
    const selectObject = document.getElementById("roles");
    let roles = [];
    for (let i = 0; i < selectObject.options.length; i++) {
        if (selectObject.options[i].selected) {
            roles.push(selectObject.options[i].value);
        }
    }
    formData.roles = roles;

    fetch(`${rootPhp}?action=insertar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.ok ? res.text() : res.text().then(text => { throw new Error(text) }))
    .then(text => JSON.parse(text))
    .then(resp => {
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
    form?.reset();
    $("#roles").selectpicker('val', []);
    document.getElementById("box-data")?.classList.add("d-none");
    document.getElementById("box-list")?.classList.remove("d-none");
}

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

function listarUsuarios() {
    fetch(`${rootPhp}?action=listar`)
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = '';
            data.forEach(u => {
                const nombresRoles = u.roles.map(rol => rol.nombres).join(', ');
                tableBody.innerHTML += `
                    <tr>
                        <td>${u.idUsuario}</td>
                        <td>${u.nombres}</td>
                        <td>${u.correo}</td>
                        <td>${u.usuario}</td>
                        <td>${nombresRoles}</td>
                        <td>
                            <button class='btn btn-success btn-sm me-1' onclick='editar(${JSON.stringify(u)})'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class='btn btn-danger btn-sm' onclick='eliminar(${u.idUsuario})'>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;
            });
        })
        .catch(error => {
            console.error("Error al listar usuarios:", error);
            alert("Error al cargar la lista de usuarios.");
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
