<?php
ob_clean();
header('Content-Type: application/json');

session_start();
include './config.php';
include './usuario.php';
include './rol.php';

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'listar_roles':
        echo json_encode(Rol::getAll($conn));
        break;

    case 'listar':
        echo json_encode(Usuario::getAll($conn));
        break;

    case 'insertar':
        echo json_encode(['success' => Usuario::insert($conn, $data)]);
        break;

    case 'actualizar':
        echo json_encode(['success' => Usuario::update($conn, $data)]);
        break;

    case 'eliminar':
        echo json_encode(['success' => Usuario::delete($conn, $data['idUsuario'])]);
        break;

    case 'obtener':
        echo json_encode(Usuario::getById($conn, $data['idUsuario']));
        break;

    case 'login':
        $data['tipoUsuario'] = strtoupper($data['tipoUsuario']);
        $usuario = $data['correo'];
        $usuario = Usuario::login($conn, $usuario, $data['password'], $data['tipoUsuario']);
        if ($usuario) {
            $_SESSION['usuario'] = $usuario['nombres'];
            echo json_encode(['success' => true, "usuario" => $usuario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    case 'check':
        echo json_encode(['logged_in' => isset($_SESSION['usuario'])]);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
?>