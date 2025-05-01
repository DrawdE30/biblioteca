<?php
ob_clean();
header('Content-Type: application/json');
require 'config.php';

class Libro {
    public static function listar($conn) {
        $result = $conn->query("SELECT l.*, GROUP_CONCAT(c.nombre) as categorias
                                FROM libro l
                                LEFT JOIN libro_categoria lc ON l.idLibro = lc.idLibro
                                LEFT JOIN categoria c ON c.idCategoria = lc.idCategoria
                                GROUP BY l.idLibro");
        $libros = [];
        while ($row = $result->fetch_assoc()) {
            $libros[] = $row;
        }
        echo json_encode($libros);
    }

    public static function insertar($conn, $data) {
        // Validación de campos
        if (!isset($data['titulo'], $data['autor'], $data['anio_publicacion'], $data['isbn'], $data['categorias'])) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos']);
            return;
        }

        $stmt = $conn->prepare("INSERT INTO libro (titulo, autor, anio_publicacion, isbn) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $data['titulo'], $data['autor'], $data['anio_publicacion'], $data['isbn']);
        if ($stmt->execute()) {
            $idLibro = $conn->insert_id;
            self::guardarCategorias($conn, $idLibro, $data['categorias']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }

    public static function actualizar($conn, $data) {
        if (!isset($data['idLibro'])) {
            echo json_encode(['success' => false, 'error' => 'ID no encontrado']);
            return;
        }

        $stmt = $conn->prepare("UPDATE libro SET titulo=?, autor=?, anio_publicacion=?, isbn=? WHERE idLibro=?");
        $stmt->bind_param("ssisi", $data['titulo'], $data['autor'], $data['anio_publicacion'], $data['isbn'], $data['idLibro']);
        if ($stmt->execute()) {
            self::guardarCategorias($conn, $data['idLibro'], $data['categorias'], true);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }

    public static function eliminar($conn, $idLibro) {
        $conn->query("DELETE FROM libro_categoria WHERE idLibro=$idLibro");
        $conn->query("DELETE FROM libro WHERE idLibro=$idLibro");
        echo json_encode(['success' => true]);
    }

    private static function guardarCategorias($conn, $idLibro, $categorias, $borrarPrevias = false) {
        if (!is_array($categorias)) return;

        if ($borrarPrevias) {
            $conn->query("DELETE FROM libro_categoria WHERE idLibro=$idLibro");
        }
        $stmt = $conn->prepare("INSERT INTO libro_categoria (idLibro, idCategoria) VALUES (?, ?)");
        foreach ($categorias as $idCategoria) {
            $stmt->bind_param("ii", $idLibro, $idCategoria);
            $stmt->execute();
        }
    }
}

// ✅ Este bloque lee los datos JSON ENVIADOS por fetch()
$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

// ✅ Manejo de acciones
switch ($action) {
    case 'listar':
        Libro::listar($conn);
        break;
    case 'insertar':
        Libro::insertar($conn, $data);
        break;
    case 'actualizar':
        Libro::actualizar($conn, $data);
        break;
    case 'eliminar':
        Libro::eliminar($conn, $data['idLibro']);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        break;
}
?>
