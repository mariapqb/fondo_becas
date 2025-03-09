<?php
global $conn;
require_once "db.php"; // Archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $tipo_documento = $_POST["document-type"];
    $numero_documento = $_POST["document-number"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];
    $rol = "Beneficiario"; // Rol predeterminado

    // Validar que los campos no estén vacíos
    if (empty($tipo_documento) || empty($numero_documento) || empty($password) || empty($confirm_password)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        die("Error: Las contraseñas no coinciden.");
    }

    // Verificar si el usuario ya existe
    $query_check = "SELECT * FROM usuarios WHERE numero_documento = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $numero_documento);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        die("Error: El usuario ya está registrado.");
    }

    // Cifrar la contraseña con SHA-256
    $password_hash = hash("sha256", $password);

    // Insertar en la base de datos
    $query_insert = "INSERT INTO usuarios (tipo_documento, numero_documento, contraseña, rol) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("ssss", $tipo_documento, $numero_documento, $password_hash, $rol);

    if ($stmt_insert->execute()) {
        echo "Registro exitoso. <a href='../public/login.html'>Iniciar sesión</a>";
    } else {
        echo "Error en el registro: " . $stmt_insert->error;
    }

    // Cerrar conexiones
    $stmt_check->close();
    $stmt_insert->close();
    $conn->close();
} else {
    die("Acceso no permitido.");
}

