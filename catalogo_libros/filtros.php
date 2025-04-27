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
<html>
<head>
    <title>Filtrar y Ordenar Catálogo</title>
</head>
<body>
    <h1>Filtrar y Ordenar Libros</h1>

    <form method="GET">
        Categoría: <input type="text" name="categoria" placeholder="Ej: Novela"><br>
        Autor: <input type="text" name="autor" placeholder="Ej: Gabriel García Márquez"><br>
        Precio mínimo: <input type="number" name="precio_min" step="0.01"><br>
        Precio máximo: <input type="number" name="precio_max" step="0.01"><br>
        Ordenar por:
        <select name="orden">
            <option value="">---</option>
            <option value="precio_asc">Precio: Menor a Mayor</option>
            <option value="precio_desc">Precio: Mayor a Menor</option>
        </select><br><br>
        <button type="submit">Buscar</button>
    </form>

    <ul>
    <?php while($row = $resultado->fetch_assoc()): ?>
        <li>
            <a href="detalle.php?id=<?php echo $row['id']; ?>">
                <?php echo $row['titulo']; ?> - $<?php echo $row['precio']; ?>
            </a>
        </li>
    <?php endwhile; ?>
    </ul>

    <a href="index.php">Volver al Catálogo</a>
</body>
</html>