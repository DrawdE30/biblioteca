<?php
include 'config.php';  // Conexión a la base de datos

$query = "SELECT idRol, nombre FROM roles";
$result = $conn->query($query);

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

echo json_encode($roles);
?>
