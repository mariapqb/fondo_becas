<?php
// Iniciar la sesión para manejar mensajes de éxito o error
global $conn;
session_start();

// Incluir los archivos necesarios para la conexión y el registro de usuarios
require_once "db.php";
require_once "register.php";

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Crear una instancia de la clase Register para procesar el registro
    $register = new Register($conn);
    $message = $register->registerUser($_POST["document-type"], $_POST["document-number"], $_POST["password"], $_POST["confirm-password"]);

    // Manejar el resultado del registro y redirigir a la página correspondiente
    if ($message === "Registro exitoso.") {
        $_SESSION['success'] = $message;
        header("Location: ../public/registro.html?success=" . urlencode($message));
    } else {
        $_SESSION['error'] = $message;
        header("Location: ../public/registro.html?error=" . urlencode($message));
    }
    exit();// Finalizar la ejecución del script
} else {
    // Mensaje de error si se intenta acceder directamente al script
    echo "Error: No se permitió el acceso directo.";
}

