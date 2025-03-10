<?php
global $conn;
session_start();
require_once "db.php";
require_once "pqrs.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pqrs = new PQRS($conn);
    $message = $pqrs->registrarPQRS(
        $_POST["nombre_completo"],
        $_POST["documento_identidad"],
        $_POST["correo_electronico"] ?? null,  // Puede estar vacío
        $_POST["direccion"] ?? null,  // Puede estar vacío
        $_POST["asunto"],
        $_POST["descripcion"],
        $_POST["solicitud_especifica"] ?? null // Puede estar vacío
    );

    if ($message === "PQRS registrado exitosamente.") {
        header("Location: ../public/pqrs.html?success=" . urlencode($message));
    } else {
        header("Location: ../public/pqrs.html?error=" . urlencode($message));
    }
    exit();
}
