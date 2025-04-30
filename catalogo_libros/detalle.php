<?php
include 'conexion.php';

$id = (int)$_GET['id'];
$sql = "SELECT * FROM libros WHERE id = $id";
$resultado = $conn->query($sql);
$libro = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <title>Detalles del Libro</title>
</head>

<body>
    <h1><?php echo $libro['titulo']; ?></h1>
    <p><strong>Autor:</strong> <?php echo $libro['autor']; ?></p>
    <p><strong>Categoría:</strong> <?php echo $libro['categoria']; ?></p>
    <p><strong>Precio:</strong> $<?php echo $libro['precio']; ?></p>
    <p><strong>ISBN:</strong> <?php echo $libro['isbn']; ?></p>
    <p><strong>Sinopsis:</strong> <?php echo $libro['sinopsis']; ?></p>

    <a href="./filtros.php">Volver al Catálogo</a>
</body>

</html>