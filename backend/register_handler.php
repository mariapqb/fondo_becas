<?php
global $conn;
session_start();
require_once "db.php";
require_once "register.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $register = new Register($conn);
    $message = $register->registerUser($_POST["document-type"], $_POST["document-number"], $_POST["password"], $_POST["confirm-password"]);

    if ($message === "Registro exitoso.") {
        $_SESSION['success'] = $message;
        header("Location: ../public/registro.html?success=" . urlencode($message));
    } else {
        $_SESSION['error'] = $message;
        header("Location: ../public/registro.html?error=" . urlencode($message));
    }
    exit();
} else {
    echo "Error: No se permitió el acceso directo.";
}

