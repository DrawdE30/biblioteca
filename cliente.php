<?php
ob_clean();
header('Content-Type: application/json');

class Cliente {
    public static function getAll($conn) {
        $result = $conn->query("SELECT * FROM cliente");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // public static function insert($conn, $data) {
    //     $stmt = $conn->prepare("INSERT INTO cliente (nombres, apellidos, correo, password, direccion) VALUES (?, ?, ?, ?, ?)");
    //     $stmt->bind_param("sssss", $data['nombres'], $data['apellidos'], $data['correo'], $data['password'], $data['direccion']);
    //     return $stmt->execute();
    // }
    public static function insert($conn, $data) {
        $stmt = $conn->prepare("INSERT INTO cliente (nombres, apellidos, correo, password, direccion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['nombres'], $data['apellidos'], $data['correo'], $data['password'], $data['direccion']);
        
        if ($stmt->execute()) {
            // Respuesta JSON si se insertó correctamente
            echo json_encode(['status' => 'ok']);
        } else {
            // Respuesta JSON si ocurrió un error
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar el cliente']);
        }
    }
    

    public static function update($conn, $data) {
        $stmt = $conn->prepare("UPDATE cliente SET nombres=?, apellidos=?, correo=?, password=?, direccion=? WHERE idCliente=?");
        $stmt->bind_param("sssssi", $data['nombres'], $data['apellidos'], $data['correo'], $data['password'], $data['direccion'], $data['idCliente']);
        return $stmt->execute();
    }

    public static function delete($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM cliente WHERE idCliente=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function getById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM cliente WHERE idCliente=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
