<?php
header('Content-Type: application/json');
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "error" => $conexion->connect_error]));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $resultado = $conexion->query("SELECT * FROM libros");
        $libros = [];

        while ($fila = $resultado->fetch_assoc()) {
            $fila['categorias'] = explode(',', $fila['categorias']);
            $libros[] = $fila;
        }

        echo json_encode($libros);
        break;

    case 'insertar':
        $datos = json_decode(file_get_contents('php://input'), true);
        $stmt = $conexion->prepare("INSERT INTO libros (isbn, titulo, autor, editorial, anio, categorias) VALUES (?, ?, ?, ?, ?, ?)");
        $categorias = implode(',', $datos['categorias']);
        $stmt->bind_param("ssssis", $datos['isbn'], $datos['titulo'], $datos['autor'], $datos['editorial'], $datos['anio'], $categorias);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    case 'actualizar':
        $datos = json_decode(file_get_contents('php://input'), true);
        $stmt = $conexion->prepare("UPDATE libros SET isbn=?, titulo=?, autor=?, editorial=?, anio=?, categorias=? WHERE idLibro=?");
        $categorias = implode(',', $datos['categorias']);
        $stmt->bind_param("ssssisi", $datos['isbn'], $datos['titulo'], $datos['autor'], $datos['editorial'], $datos['anio'], $categorias, $datos['idLibro']);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    case 'eliminar':
        $datos = json_decode(file_get_contents('php://input'), true);
        $stmt = $conexion->prepare("DELETE FROM libros WHERE idLibro=?");
        $stmt->bind_param("i", $datos['idLibro']);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Acción no válida"]);
}
?>
