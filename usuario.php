<?php
ob_clean();
header('Content-Type: application/json');

class Usuario {
    public static function getAll($conn, $query = null)
    {
        if ($query === null) {
            $query = "
                SELECT u.idUsuario, u.usuario, u.password, u.nombres, u.correo, u.status, r.idRol, r.nombre AS nombreRol
                FROM usuario u
                LEFT JOIN usuario_rol ur ON ur.idUsuario = u.idUsuario
                LEFT JOIN roles r ON r.idRol = ur.idRol
                ORDER BY u.idUsuario DESC"; // Ordenamos por el más reciente
        }

        $result = $conn->query($query);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $idUsuario = $row['idUsuario'];
            if (!isset($usuarios[$idUsuario])) {
                $usuarios[$idUsuario] = [
                    'idUsuario' => $row['idUsuario'],
                    'usuario' => $row['usuario'],
                    'correo' => $row['correo'],
                    'password' => $row['password'],
                    'nombres' => $row['nombres'],
                    'status' => $row['status'],
                    'roles' => []
                ];
            }
            if ($row['idRol'] !== null) {
                $usuarios[$idUsuario]['roles'][] = [
                    'idRol' => $row['idRol'],
                    'nombres' => $row['nombreRol']
                ];
            }
        }
        return array_values($usuarios);  // Devuelve un array indexado numéricamente
    }

    public static function insert($conn, $data)
    {
        $conn->begin_transaction();  // Inicia la transacción

        // Insertar usuario
        $stmt = $conn->prepare("INSERT INTO usuario (nombres, usuario, correo, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['nombres'], $data['usuario'], $data['correo'], $data['password']);

        if ($stmt->execute()) {
            $idNuevoUsuario = $conn->insert_id;

            // Asignar rol "Cliente" por defecto si no hay roles
            $roles = isset($data['roles']) ? $data['roles'] : ['Cliente'];
            if (self::guardarRoles($conn, $idNuevoUsuario, $roles)) {
                $conn->commit();
                return $idNuevoUsuario;  // Devuelve el ID del nuevo usuario
            } else {
                $conn->rollback();
                return false;
            }
        } else {
            $conn->rollback();
            return false;
        }
    }

    private static function guardarRoles($conn, $idUsuario, $roles)
    {
        if (is_array($roles) && !empty($roles)) {
            $stmt = $conn->prepare("INSERT INTO usuario_rol (idUsuario, idRol) VALUES (?, ?)");
            foreach ($roles as $idRol) {
                $stmt->bind_param("ii", $idUsuario, $idRol);
                if (!$stmt->execute()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}

    public static function update($conn, $data)
    {
        $stmt = $conn->prepare("UPDATE usuario SET nombres=?, usuario=?, password=? WHERE idUsuario=?");
        $stmt->bind_param("ssi", $data['usuario'], $data['password'], $data['idUsuario']);
        return $stmt->execute();
    }

    public static function delete($conn, $id)
    {
        $stmt = $conn->prepare("DELETE FROM usuario WHERE idUsuario=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function getById($conn, $id)
    {
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE idUsuario=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function login($conn, $usuario, $password, $tipoUsuario)
    {
        if ($tipoUsuario == 'CLIENTE') {
            $stmt = $conn->prepare("SELECT * FROM cliente WHERE correo=? AND password=?");
        } else {
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo=? AND password=?");
        }
        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
