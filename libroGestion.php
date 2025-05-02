<?php
header('Content-Type: application/json');
$conexion = new mysqli('localhost', 'root', '', 'biblioteca');

$accion = $_GET['action'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

switch ($accion) {
  case 'listar':
    $res = $conexion->query("SELECT * FROM libros");
    $libros = [];
    while ($fila = $res->fetch_assoc()) {
        $fila['categorias'] = $fila['categorias'] ?? '';
        $libros[] = $fila;
    }
    echo json_encode($libros);
    break;

  case 'insertar':
    $categorias = implode(',', $data['categorias']);
    $stmt = $conexion->prepare("INSERT INTO libros (isbn, titulo, autor, editorial, anio, categorias) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $data['isbn'], $data['titulo'], $data['autor'], $data['editorial'], $data['anio'], $categorias);
    $stmt->execute();
    echo json_encode(['success' => true]);
    break;

  case 'actualizar':
    $categorias = implode(',', $data['categorias']);
    $stmt = $conexion->prepare("UPDATE libros SET isbn=?, titulo=?, autor=?, editorial=?, anio=?, categorias=? WHERE idLibro=?");
    $stmt->bind_param("ssssisi", $data['isbn'], $data['titulo'], $data['autor'], $data['editorial'], $data['anio'], $categorias, $data['idLibro']);
    $stmt->execute();
    echo json_encode(['success' => true]);
    break;

  case 'eliminar':
    $stmt = $conexion->prepare("DELETE FROM libros WHERE idLibro=?");
    $stmt->bind_param("i", $data['idLibro']);
    $stmt->execute();
    echo json_encode(['success' => true]);
    break;

  default:
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    break;
}
?>
