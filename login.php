<?php
// Simular una "base de datos" de usuarios para este ejemplo
$usuarios = [
    [
        'correo' => 'cliente@example.com',
        'password' => '123456', // En producción deberían estar hasheadas
        'tipo' => 'Cliente'
    ],
    [
        'correo' => 'colaborador@example.com',
        'password' => 'abcdef',
        'tipo' => 'Colaborador'
    ]
];

// Leer el JSON enviado
$input = json_decode(file_get_contents('php://input'), true);

$correo = $input['correo'] ?? '';
$password = $input['password'] ?? '';
$tipoUsuario = $input['tipoUsuario'] ?? '';

$response = ['success' => false, 'message' => 'Datos inválidos'];

// Buscar usuario
foreach ($usuarios as $usuario) {
    if (
        $usuario['correo'] === $correo &&
        $usuario['password'] === $password &&
        $usuario['tipo'] === $tipoUsuario
    ) {
        $response = ['success' => true];
        break;
    }
}

// Devolver respuesta
header('Content-Type: application/json');
echo json_encode($response);
