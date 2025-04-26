<?php
ob_clean();
header('Content-Type: application/json');

include './config.php';
include './cliente.php';

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'listar':
        echo json_encode(Cliente::getAll($conn));
        break;

    case 'insertar':
        echo json_encode(['success' => Cliente::insert($conn, $data)]);
        break;

    case 'actualizar':
        echo json_encode(['success' => Cliente::update($conn, $data)]);
        break;

    case 'eliminar':
        echo json_encode(['success' => Cliente::delete($conn, $data['idCliente'])]);
        break;

    case 'obtener':
        echo json_encode(Cliente::getById($conn, $data['idCliente']));
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
?>
