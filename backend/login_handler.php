<?php
// Iniciar la sesión para manejar autenticación de usuarios
global $conn;
session_start();

// Incluir los archivos necesarios para la conexión y autenticación
require_once "db.php";
require_once "login.php";

// Verificar si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear una instancia de la clase Login y autenticar al usuario
    $login = new Login($conn);
    $message = $login->authenticateUser($_POST["document-number"], $_POST["password"]);

    // Si la autenticación es exitosa, almacenar el usuario en la sesión y redirigir
    if ($message === "Inicio de sesión exitoso.") {
        $_SESSION['user'] = $_POST["document-number"];
        header("Location: ../public/beneficiarios.html");// Redirigir a la página de beneficiarios
    } else {
        // Si hay un error, redirigir al login con el mensaje de error
        header("Location: ../public/login.html?error=" . urlencode($message));
    }
    exit();// Finalizar la ejecución del script
}


