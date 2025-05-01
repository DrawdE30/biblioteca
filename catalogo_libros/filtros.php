<?php
include 'conexion.php';

$where = [];
$orden = "";

// Filtros de búsqueda
if (!empty($_GET['categoria'])) {
    $where[] = "categoria = '" . $conn->real_escape_string($_GET['categoria']) . "'";
}
if (!empty($_GET['autor'])) {
    $where[] = "autor = '" . $conn->real_escape_string($_GET['autor']) . "'";
}
if (!empty($_GET['precio_min'])) {
    $where[] = "precio >= " . (float)$_GET['precio_min'];
}
if (!empty($_GET['precio_max'])) {
    $where[] = "precio <= " . (float)$_GET['precio_max'];
}

// Ordenamiento
if (!empty($_GET['orden'])) {
    if ($_GET['orden'] == "precio_asc") {
        $orden = " ORDER BY precio ASC";
    } elseif ($_GET['orden'] == "precio_desc") {
        $orden = " ORDER BY precio DESC";
    }
}

// Construcción final del SQL
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
    <link href="https://getbootstrap.com/docs/5.1/assets/css/docs.css" rel="stylesheet">
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
        }
        input.form-control, select.form-select {
            margin-bottom: 15px;
            border-radius: 10px;
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
            </div>
            <div class="mb-3">
                <label class="form-label">Ordenar por:</label>
                <select name="orden" class="form-select">
                    <option value="">---</option>
                    <option value="precio_asc">Precio: Menor a Mayor</option>
                    <option value="precio_desc">Precio: Mayor a Menor</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="index.php" class="btn btn-secondary">Volver al Catálogo</a>
            </div>
        </form>
    </div>

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