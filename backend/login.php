<?php
require_once "db.php"; // Archivo con la conexión a la base de datos

class Login {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticateUser($numero_documento, $password): string {
        if (empty($numero_documento) || empty($password)) {
            return "Todos los campos son obligatorios.";
        }

        $query = "SELECT contraseña FROM usuarios WHERE numero_documento = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return "Error en la consulta.";
        }

        $stmt->bind_param("s", $numero_documento);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return "Usuario no encontrado.";
        }

        $hashed_password = hash("sha256", $password);
        if ($hashed_password !== $user["contraseña"]) {
            return "Contraseña incorrecta.";
        }

        return "Inicio de sesión exitoso.";
    }
}
