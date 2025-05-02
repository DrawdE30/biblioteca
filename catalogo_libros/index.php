<?php
include 'conexion.php';

$sql = "SELECT * FROM libros";
$resultado = $conn->query($sql);

// Verificamos si la consulta fue exitosa
if (!$resultado) {
    die('Error en la consulta: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Libros</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Estilo para la cuadrícula */
        .book-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columnas */
            gap: 20px; /* Espacio entre los libros */
            width: 80%; /* Ancho de la cuadrícula */
            margin: 0 auto; /* Centrado */
        }
        .book-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            height: 300px; /* Altura fija para todos los libros */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .book-item img {
            width: 120px;
            height: 180px;
            object-fit: cover; /* Para asegurar que la imagen mantenga su proporción */
            margin-bottom: 15px;
            border-radius: 5px;
            align-self: center; /* Centra la imagen */
        }
        .book-item .book-details a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        /* Estilo para el contenedor de filtros */
        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            width: 50%;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <h1>Catálogo de Libros</h1>
    <a href="filtros.php" class="btn btn-primary mb-3">Filtrar y Ordenar</a>

    <?php if ($resultado->num_rows > 0): ?>
        <!-- Contenedor para la cuadrícula de libros -->
        <div class="book-list">
            <?php while($row = $resultado->fetch_assoc()): ?>
                <div class="book-item">
                    <!-- Imagen de portada (si existe) -->
                    <?php if (!empty($row['imagen_portada'])): ?>
                        <img src="imagenes/<?php echo htmlspecialchars($row['imagen_portada']); ?>" alt="Portada de <?php echo htmlspecialchars($row['titulo']); ?>">
                    <?php else: ?>
                        <img src="imagenes/default.jpg" alt="Imagen por defecto">
                    <?php endif; ?>

                    <!-- Información del libro -->
                    <div class="book-details">
                        <a href="detalle.php?id=<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['titulo']); ?>
                        </a>
                        <p>$<?php echo number_format($row['precio'], 2); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No hay libros disponibles en el catálogo.</p>
    <?php endif; ?>

</body>
</html>