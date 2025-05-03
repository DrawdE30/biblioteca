<?php
ob_clean();
header('Content-Type: application/json');

class Usuario
{
    public static function getAll($conn)
    {
        $result = $conn->query("SELECT u.idUsuario, u.usuario, u.password, u.nombres, u.correo, u.status, r.idRol, r.nombre AS nombreRol
                                FROM usuario u
                                LEFT JOIN usuario_rol ur ON ur.idUsuario = u.idUsuario
                                LEFT JOIN roles r ON r.idRol = ur.idRol
                                ORDER BY u.idUsuario");

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
        return array_values($usuarios); // Devuelve un array indexado numéricamente
    }

    public static function insert($conn, $data)
    {
        $conn->begin_transaction(); // Inicia una transacción

        $stmt = $conn->prepare("INSERT INTO usuario (nombres, usuario, correo, password, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['nombres'],  $data['usuario'], $data['correo'], $data['password'], 1);

        if ($stmt->execute()) {
            $idNuevoUsuario = $conn->insert_id;
            if (self::guardarRoles($conn, $idNuevoUsuario, $data['roles'])) {
                $conn->commit(); // Si todo va bien, confirma la transacción
                return $idNuevoUsuario;
            } else {
                $conn->rollback(); // Si falla guardarRoles, revierte la inserción del usuario
                return false;
            }
        } else {
            $conn->rollback(); // Si falla la inserción del usuario, revierte (aunque no haya nada que revertir)
            return false;
        }
    }
    private static function guardarRoles($conn, $idUsuario, $roles)
    {
        if (is_array($roles) && !empty($roles)) {
            $stmt = $conn->prepare("DELETE FROM usuario_rol WHERE idUsuario = ?");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO usuario_rol (idUsuario, idRol) VALUES (?, ?)");
            foreach ($roles as $idRol) {
                $stmt->bind_param("ii", $idUsuario, $idRol);
                if (!$stmt->execute()) {
                    return false;
                }
            }
            return true;
        }
        return true;
    }

    public static function update($conn, $data)
    {
        $stmt = $conn->prepare("UPDATE usuario SET nombres=?, correo=?, usuario=?, password=?, status=? WHERE idUsuario=?");
        $stmt->bind_param("ssssii", $data['nombres'], $data['correo'], $data['usuario'], $data['password'], $data['status'], $data['idUsuario']);
        return $stmt->execute();
    }

    public static function delete($conn, $id)
    {
        $stmt = $conn->prepare("UPDATE usuario SET status=1 WHERE idUsuario=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
        // $stmt = $conn->prepare("DELETE FROM usuario WHERE idUsuario=?");
        // $stmt->bind_param("i", $id);
        // return $stmt->execute();
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