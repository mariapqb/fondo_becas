<?php
global $conn;
session_start();
include "db.php"; // Asegúrate de que este archivo contiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_documento = trim($_POST["usuario"]); // Número de documento como usuario
    $password = trim($_POST["password"]);

    // Hash de la contraseña ingresada
    $password_hashed = hash("sha256", $password);

    // Consulta segura para evitar inyección SQL
    $sql = "SELECT numero_documento, tipo_documento, contraseña, rol FROM usuarios WHERE numero_documento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $numero_documento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña encriptada con SHA-256
        if ($row["contraseña"] === $password_hashed) {
            // Almacenar datos en la sesión
            $_SESSION["numero_documento"] = $row["numero_documento"];
            $_SESSION["tipo_documento"] = $row["tipo_documento"];
            $_SESSION["rol"] = $row["rol"];

            // Redirección según el rol
            if ($row["rol"] === "Administrador") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: beneficiario_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Número de documento o contraseña incorrectos.'); window.location.href='../frontend/login.html';</script>";
        }
    } else {
        echo "<script>alert('Número de documento o contraseña incorrectos.'); window.location.href='../frontend/login.html';</script>";
    }

    $stmt->close();
}

$conn->close();

