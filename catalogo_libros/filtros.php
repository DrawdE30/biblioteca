<?php
include 'conexion.php';

// Obtener categorías y autores para las listas desplegables
$sql_categoria = "SELECT DISTINCT categoria FROM libros";
$result_categoria = $conn->query($sql_categoria);

$sql_autor = "SELECT DISTINCT autor FROM libros";
$result_autor = $conn->query($sql_autor);

// Inicializamos variables para filtros y ordenamiento
$where = [];
$orden = "";

// Filtros de búsqueda con validación
if (!empty($_GET['categoria'])) {
    $categoria = $conn->real_escape_string($_GET['categoria']);
    $where[] = "categoria = '$categoria'";
}
if (!empty($_GET['autor'])) {
    $autor = $conn->real_escape_string($_GET['autor']);
    $where[] = "autor = '$autor'";
}
if (!empty($_GET['precio_min']) && is_numeric($_GET['precio_min'])) {
    $precio_min = (float)$_GET['precio_min'];
    $where[] = "precio >= $precio_min";
}
if (!empty($_GET['precio_max']) && is_numeric($_GET['precio_max'])) {
    $precio_max = (float)$_GET['precio_max'];
    $where[] = "precio <= $precio_max";
}

// Ordenamiento
if (!empty($_GET['orden'])) {
    if ($_GET['orden'] == "precio_asc") {
        $orden = " ORDER BY precio ASC";
    } elseif ($_GET['orden'] == "precio_desc") {
        $orden = " ORDER BY precio DESC";
    }
}

// Construcción de la consulta final con filtros y ordenamiento
$sql = "SELECT * FROM libros";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= $orden;

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Filtrar y Ordenar Catálogo</title>
    <link rel="stylesheet" href="bootstrap.min.css" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<<<<<<< HEAD
    <link href="https://getbootstrap.com/docs/5.1/assets/css/docs.css" rel="stylesheet">
=======
>>>>>>> ec1b365d480230c59e69edb7afdf49da4f22e14b
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            width: 50%; /* Reducido a la mitad */
            margin: 0 auto; /* Centrado */
        }
        input.form-control, select.form-select {
            margin-bottom: 15px;
            border-radius: 10px;
        }
        .book-list {
            width: 50%; /* Reducido a la mitad */
            margin: 0 auto; /* Centrado */
        }
        .book-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: white;
        }
        .book-item img {
            width: 80px;
            height: auto;
            margin-right: 15px;
            border-radius: 5px;
        }
        .book-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4 text-primary">Filtrar y Ordenar Libros</h1>

    <div class="form-container">
        <form method="GET">
            <div class="mb-3">
                <label class="form-label">Categoría:</label>
<<<<<<< HEAD
                <input type="text" name="categoria" class="form-control" placeholder="Ej: Novela">
            </div>
            <div class="mb-3">
                <label class="form-label">Autor:</label>
                <input type="text" name="autor" class="form-control" placeholder="Ej: Gabriel García Márquez">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio mínimo:</label>
                <input type="number" name="precio_min" class="form-control" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio máximo:</label>
                <input type="number" name="precio_max" class="form-control" step="0.01">
=======
                <select name="categoria" class="form-select">
                    <option value="">Selecciona una categoría</option>
                    <?php while ($row_categoria = $result_categoria->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row_categoria['categoria']); ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $row_categoria['categoria']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_categoria['categoria']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Autor:</label>
                <select name="autor" class="form-select">
                    <option value="">Selecciona un autor</option>
                    <?php while ($row_autor = $result_autor->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row_autor['autor']); ?>" <?php echo (isset($_GET['autor']) && $_GET['autor'] == $row_autor['autor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row_autor['autor']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio mínimo:</label>
                <input type="number" name="precio_min" class="form-control" step="0.01" value="<?php echo isset($_GET['precio_min']) ? htmlspecialchars($_GET['precio_min']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio máximo:</label>
                <input type="number" name="precio_max" class="form-control" step="0.01" value="<?php echo isset($_GET['precio_max']) ? htmlspecialchars($_GET['precio_max']) : ''; ?>">
>>>>>>> ec1b365d480230c59e69edb7afdf49da4f22e14b
            </div>
            <div class="mb-3">
                <label class="form-label">Ordenar por:</label>
                <select name="orden" class="form-select">
                    <option value="">---</option>
<<<<<<< HEAD
                    <option value="precio_asc">Precio: Menor a Mayor</option>
                    <option value="precio_desc">Precio: Mayor a Menor</option>
=======
                    <option value="precio_asc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_asc' ? 'selected' : ''; ?>>Precio: Menor a Mayor</option>
                    <option value="precio_desc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_desc' ? 'selected' : ''; ?>>Precio: Mayor a Menor</option>
>>>>>>> ec1b365d480230c59e69edb7afdf49da4f22e14b
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="index.php" class="btn btn-secondary">Volver al Catálogo</a>
            </div>
        </form>
    </div>
<<<<<<< HEAD

    <ul class="list-group">
<?php while($row = $resultado->fetch_assoc()): ?>
    <li class="list-group-item d-flex align-items-center">
        <!-- Imagen de portada -->
        <?php if (!empty($row['imagen_portada'])): ?>
            <img src="imagenes/<?php echo htmlspecialchars($row['imagen_portada']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>" width="80" class="me-3 rounded">
        <?php endif; ?>

        <!-- Información del libro -->
        <a href="detalle.php?id=<?php echo $row['id']; ?>" class="text-decoration-none flex-grow-1">
            <?php echo htmlspecialchars($row['titulo']); ?> - $<?php echo number_format($row['precio'], 2); ?>
        </a>
    </li>
<?php endwhile; ?>
</ul>
=======

    <?php if ($resultado->num_rows > 0): ?>
        <div class="book-list">
            <ul class="list-group">
                <?php while($row = $resultado->fetch_assoc()): ?>
                    <li class="book-item">
                        <!-- Imagen de portada -->
                        <?php if (!empty($row['imagen_portada'])): ?>
                            <img src="imagenes/<?php echo htmlspecialchars($row['imagen_portada']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>">
                        <?php else: ?>
                            <img src="imagenes/default.jpg" alt="Imagen por defecto">
                        <?php endif; ?>

                        <!-- Información del libro -->
                        <a href="detalle.php?id=<?php echo $row['id']; ?>" class="flex-grow-1">
                            <?php echo htmlspecialchars($row['titulo']); ?> - $<?php echo number_format($row['precio'], 2); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>No se encontraron resultados para los filtros seleccionados.</p>
    <?php endif; ?>
</div>

</body>
</html>
>>>>>>> ec1b365d480230c59e69edb7afdf49da4f22e14b
