<?php
require_once "db.php"; // Archivo con la conexión a la base de datos

class Login {
    private $conn;// Variable privada para la conexión a la base de datos

    /**
     * Constructor de la clase Login.
     * @param $conn objeto de conexión a la base de datos.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Método para autenticar a un usuario.
     * @param string $numero_documento Número de documento del usuario.
     * @param string $password Contraseña ingresada por el usuario.
     * @return string Mensaje con el resultado de la autenticación.
     */
    public function authenticateUser($numero_documento, $password): string {
        // Verificar si los campos están vacíos
        if (empty($numero_documento) || empty($password)) {
            return "Todos los campos son obligatorios.";
        }
        // Consulta para obtener la contraseña del usuario
        $query = "SELECT contraseña FROM usuarios WHERE numero_documento = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return "Error en la consulta.";
        }

        // Asignar el parámetro y ejecutar la consulta
        $stmt->bind_param("s", $numero_documento);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verificar si el usuario existe
        if (!$user) {
            return "Usuario no encontrado.";
        }

        // Verificar la contraseña ingresada con la almacenada (hash SHA-256)
        $hashed_password = hash("sha256", $password);
        if ($hashed_password !== $user["contraseña"]) {
            return "Contraseña incorrecta.";
        }

        return "Inicio de sesión exitoso.";
    }
}
