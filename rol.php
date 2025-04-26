<?php
ob_clean();
header('Content-Type: application/json');

class Rol {
    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM roles");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
