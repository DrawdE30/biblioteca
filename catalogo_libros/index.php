<?php
include 'conexion.php';

$sql = "SELECT * FROM libros";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Catálogo de Libros</title>
</head>
<body>
    <h1>Catálogo de Libros</h1>
    <a href="filtros.php">Filtrar y Ordenar</a>
    <ul>
    <?php while($row = $resultado->fetch_assoc()): ?>
        <li>
            <a href="detalle.php?id=<?php echo $row['id']; ?>">
                <?php echo $row['titulo']; ?> - $<?php echo $row['precio']; ?>
            </a>
        </li>
    <?php endwhile; ?>
    </ul>
</body>
</html>