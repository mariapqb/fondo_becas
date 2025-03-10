<?php
global $conn;
session_start();
require_once "db.php";
require_once "login.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = new Login($conn);
    $message = $login->authenticateUser($_POST["document-number"], $_POST["password"]);

    if ($message === "Inicio de sesión exitoso.") {
        $_SESSION['user'] = $_POST["document-number"];
        header("Location: ../public/beneficiarios.html");
    } else {
        header("Location: ../public/login.html?error=" . urlencode($message));
    }
    exit();
}


