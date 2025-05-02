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
        // Consultamos y mostramos todos los roles disponibles
        echo json_encode(Rol::getAll($conn));
        break;

    case 'listar':
        // Mostrar los usuarios con el rol "Cliente"
        echo json_encode(Usuario::getAll($conn, "WHERE r.nombre = 'Cliente'"));
        break;

    case 'insertar':
        // Aquí asignamos el rol de "Cliente" automáticamente
        // Aseguramos que los roles sean "Cliente" por defecto
        if (!isset($data['roles']) || empty($data['roles'])) {
            $data['roles'] = ["Cliente"];  // Asignar rol "Cliente" de forma predeterminada
        }
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
        // Verificamos el login
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