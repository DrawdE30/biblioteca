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
    <title>Detalles del Libro</title>
    <style>
    .contenedor {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 40px;
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
    }
    .texto {
        flex: 2;
    }
    .imagen {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .imagen img {
        width: 100%;
        max-width: 400px;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>
</head>
<body>
    <div class="contenedor">
        <div class="texto">
            <h1><?php echo $libro['titulo']; ?></h1>
            <p><strong>Autor:</strong> <?php echo $libro['autor']; ?></p>
            <p><strong>Categoría:</strong> <?php echo $libro['categoria']; ?></p>
            <p><strong>Precio:</strong> $<?php echo $libro['precio']; ?></p>
            <p><strong>ISBN:</strong> <?php echo $libro['isbn']; ?></p>
            <p><strong>Sinopsis:</strong> <?php echo $libro['sinopsis']; ?></p>
            <a href="index.php">Volver al Catálogo</a>
        </div>
        <div class="imagen">
            <?php if (!empty($libro['imagen_portada'])): ?>
                <img src="imagenes/<?php echo htmlspecialchars($libro['imagen_portada']); ?>" alt="Portada del libro">
            <?php else: ?>
                <p><em>No hay imagen disponible.</em></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>