const formLibro = document.getElementById('libroForm');
const tableBodyLibro = document.querySelector('#librosTable tbody');
let editandoLibro = false;
let idLibroActual = null;
const rootLibroPhp = './libro.php';
const rootCategoriaPhp = './libroCategoria.php';

function listarLibros() {
    fetch(`${rootLibroPhp}?action=listar`)
        .then(res => res.json())
        .then(data => {
            tableBodyLibro.innerHTML = '';
            data.forEach(libro => {
                tableBodyLibro.innerHTML += `
                    <tr>
                        <td>${libro.idLibro}</td>
                        <td>${libro.titulo}</td>
                        <td>${libro.autor}</td>
                        <td>${libro.anio_publicacion}</td>
                        <td>${libro.isbn}</td>
                        <td>${libro.categorias || ''}</td>
                        <td>
                            <button onclick='editarLibro(${JSON.stringify(libro)})'>✏️</button>
                            <button onclick='eliminarLibro(${libro.idLibro})'>❌</button>
                        </td>
                    </tr>`;
            });
        });
}

function listarCategorias() {
    fetch(rootCategoriaPhp)
        .then(res => res.json())
        .then(data => {
            const selectCategorias = document.getElementById('categorias');
            selectCategorias.innerHTML = '';
            data.forEach(categoria => {
                selectCategorias.innerHTML += `<option value="${categoria.idCategoria}">${categoria.nombre}</option>`;
            });
        });
}

function editarLibro(libro) {
    document.getElementById('titulo').value = libro.titulo;
    document.getElementById('autor').value = libro.autor;
    document.getElementById('anio_publicacion').value = libro.anio_publicacion;
    document.getElementById('isbn').value = libro.isbn;
    idLibroActual = libro.idLibro;
    editandoLibro = true;
}

function eliminarLibro(idLibro) {
    if (confirm("¿Eliminar libro?")) {
        fetch(`${rootLibroPhp}?action=eliminar`, {
            method: 'POST',
            body: JSON.stringify({ idLibro })
        }).then(() => listarLibros());
    }
}

formLibro.addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(formLibro);
    const categorias = Array.from(document.getElementById('categorias').selectedOptions).map(opt => opt.value);
    const data = Object.fromEntries(formData.entries());
    data.categorias = categorias.map(id => parseInt(id));
    
    if (editandoLibro) {
        data.idLibro = idLibroActual;
    }

    const action = editandoLibro ? 'actualizar' : 'insertar';

    fetch(`${rootLibroPhp}?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(res => res.json())
    .then(respuesta => {
        if (respuesta.success) {
            formLibro.reset();
            editandoLibro = false;
            listarLibros();
        } else {
            alert('Error al guardar el libro.');
        }
    });
});

listarLibros();
listarCategorias();
