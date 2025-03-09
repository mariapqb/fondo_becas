<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../public/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
<h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?></h1>
<a href="logout.php">Cerrar Sesión</a>
</body>
</html>
