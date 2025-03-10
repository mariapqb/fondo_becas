<?php
require_once "db.php"; // Archivo con la conexión a la base de datos

class Register {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($tipo_documento, $numero_documento, $password, $confirm_password): string
    {
        if (empty($tipo_documento) || empty($numero_documento) || empty($password) || empty($confirm_password)) {
            return "Todos los campos son obligatorios.";
        }

        if ($password !== $confirm_password) {
            return "Las contraseñas no coinciden.";
        }

        if ($this->userExists($numero_documento)) {
            return "El usuario ya está registrado.";
        }

        return $this->insertUser($tipo_documento, $numero_documento, $password) ? "Registro exitoso." : "Error en el registro.";
    }

    private function userExists($numero_documento): bool {
        $query_check = "SELECT 1 FROM usuarios WHERE numero_documento = ?";
        $stmt_check = $this->conn->prepare($query_check);

        if (!$stmt_check) {
            return false;
        }

        $stmt_check->bind_param("s", $numero_documento);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        return (bool) $result->fetch_assoc(); // Si encuentra un usuario, devuelve true
    }

    private function insertUser($tipo_documento, $numero_documento, $password): bool {
        $password_hash = hash("sha256", $password);
        $rol = "Beneficiario";

        $query_insert = "INSERT INTO usuarios (tipo_documento, numero_documento, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conn->prepare($query_insert);

        if (!$stmt_insert) {
            return false;
        }

        $stmt_insert->bind_param("ssss", $tipo_documento, $numero_documento, $password_hash, $rol);
        return $stmt_insert->execute();
    }
}
