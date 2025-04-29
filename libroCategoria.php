<?php
ob_clean();
header('Content-Type: application/json');
require 'config.php';

$result = $conn->query("SELECT * FROM categoria");
$categorias = [];

while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}

echo json_encode($categorias);
?>
