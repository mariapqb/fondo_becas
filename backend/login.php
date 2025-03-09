<?php
global $conn;
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Preparar la consulta para evitar inyección SQL
    $sql = "SELECT id_admin, nombre, contraseña FROM administradores WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña con SHA2
        if ($row["contraseña"] === hash("sha256", $password)) {
            $_SESSION["usuario"] = $usuario;
            $_SESSION["id_admin"] = $row["id_admin"];
            $_SESSION["nombre"] = $row["nombre"];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        echo "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>
