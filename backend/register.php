<?php
// Incluir el archivo de conexión a la base de datos
require_once "db.php"; // Archivo con la conexión a la base de datos

class Register {
    private $conn;// Variable para manejar la conexión a la base de datos

    /**
     * Constructor de la clase Register.
     * @param $conn : objeto de conexión a la base de datos.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Método para registrar un nuevo usuario en la base de datos.
     * @param string $tipo_documento Tipo de documento de identificación.
     * @param string $numero_documento Número de documento de identificación.
     * @param string $password Contraseña ingresada por el usuario.
     * @param string $confirm_password Confirmación de la contraseña.
     * @return string Mensaje con el resultado del registro.
     */
    public function registerUser($tipo_documento, $numero_documento, $password, $confirm_password): string
    {
        // Validar que todos los campos estén completos
        if (empty($tipo_documento) || empty($numero_documento) || empty($password) || empty($confirm_password)) {
            return "Todos los campos son obligatorios.";
        }

        // Verificar si las contraseñas coinciden
        if ($password !== $confirm_password) {
            return "Las contraseñas no coinciden.";
        }

        // Comprobar si el usuario ya está registrado
        if ($this->userExists($numero_documento)) {
            return "El usuario ya está registrado.";
        }

        // Insertar el nuevo usuario en la base de datos
        return $this->insertUser($tipo_documento, $numero_documento, $password) ? "Registro exitoso." : "Error en el registro.";
    }

    /**
     * Método para verificar si un usuario ya está registrado.
     * @param string $numero_documento Número de documento del usuario.
     * @return bool True si el usuario ya existe, False en caso contrario.
     */
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

    /**
     * Método para insertar un nuevo usuario en la base de datos.
     * @param string $tipo_documento Tipo de documento de identificación.
     * @param string $numero_documento Número de documento de identificación.
     * @param string $password Contraseña del usuario (se almacena encriptada).
     * @return bool True si el registro es exitoso, False en caso contrario.
     */
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
