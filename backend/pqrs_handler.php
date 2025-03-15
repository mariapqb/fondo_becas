<?php
// Iniciar la sesión para manejar autenticación de usuarios
global $conn;
session_start();

// Incluir los archivos necesarios para la conexión y gestión de PQRS
require_once "db.php";
require_once "pqrs.php";

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear una instancia de la clase PQRS y registrar la solicitud
    $pqrs = new PQRS($conn);
    $message = $pqrs->registrarPQRS(
        $_POST["nombre_completo"], // Nombre completo del usuario
        $_POST["documento_identidad"],// Documento de identidad
        $_POST["correo_electronico"] ?? null,  // Correo electrónico (opcional)
        $_POST["direccion"] ?? null,  // Dirección (opcional)
        $_POST["asunto"],// Asunto de la solicitud
        $_POST["descripcion"],// Descripción de la solicitud
        $_POST["solicitud_especifica"] ?? null // Tipo específico de solicitud (opcional)
    );

    // Redirigir a la página de PQRS con el mensaje correspondiente
    if ($message === "PQRS registrado exitosamente.") {
        header("Location: ../public/pqrs.html?success=" . urlencode($message));
    } else {
        header("Location: ../public/pqrs.html?error=" . urlencode($message));
    }
    exit();// Finalizar la ejecución del script
}
