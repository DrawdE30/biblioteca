const form = document.getElementById('clienteForm');
const tableBody = document.querySelector('#clientesTable tbody');
let editando = false;

const rootPhp = './clienteController.php';

function listarClientes() {
    fetch(`${rootPhp}?action=listar`)
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar clientes');
            return res.json();
        })
        .then(data => {
            tableBody.innerHTML = '';
            data.forEach(c => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${c.idCliente}</td>
                        <td>${c.nombres}</td>
                        <td>${c.apellidos}</td>
                        <td>${c.correo}</td>
                        <td>${c.direccion}</td>
                        <td>
                            <button onclick='editar(${c.idCliente})'>✏️</button>
                            <button onclick='eliminar(${c.idCliente})'>❌</button>
                        </td>
                    </tr>`;
            });
        })
        .catch(error => {
            console.log("🚀 ~ listarClientes ~ error:", error)
            alert("Ocurrió un error al cargar la lista de clientes.");
        });
}

function editar(id) {
    fetch(`${rootPhp}?action=obtener`, {
        method: 'POST',
        body: JSON.stringify({ idCliente: id })
    })
        .then(res => res.json())
        .then(data => {
            for (let field in data) {
                if (form[field]) form[field].value = data[field];
            }
            editando = true;
        });
}

function eliminar(id) {
    if (confirm("¿Eliminar cliente?")) {
        fetch(`${rootPhp}?action=eliminar`, {
            method: 'POST',
            body: JSON.stringify({ idCliente: id })
        }).then(() => listarClientes());
    }
}

form.addEventListener('submit', e => {
    e.preventDefault();

    const formData = Object.fromEntries(new FormData(form));
    const action = editando ? 'actualizar' : 'insertar';

    fetch(`${rootPhp}?action=${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(res => {
        if (res.status === 200) {
            form.reset();
            editando = false;
            listarClientes();
        } else {
            alert("Error al guardar cliente: " + res.message);
        }
    })
    .catch(error => {
        console.log("🚀 ~ error:", error)
        alert("Ocurrió un error al insertar el cliente");
    });
});

listarClientes();
