<?php
global $conn;
require_once "db.php"; // Archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $fecha = $_POST["fecha"];
    $nombre = trim($_POST["nombre"]);
    $documento = trim($_POST["documento"]);
    $correo = trim($_POST["correo"]);
    $direccion = trim($_POST["direccion"]);
    $asunto = trim($_POST["asunto"]);
    $descripcion = trim($_POST["descripcion"]);
    $solicitud = trim($_POST["solicitud"]);

    // Validar que los campos no estén vacíos
    if (empty($fecha) || empty($nombre) || empty($documento) || empty($correo) || empty($direccion) || empty($asunto) || empty($descripcion) || empty($solicitud)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: Correo electrónico no válido.");
    }

    // Insertar en la base de datos
    $query_insert = "INSERT INTO pqrs (fecha, nombre_completo, documento_identidad, correo_electronico, direccion, asunto, descripcion, solicitud_especifica) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("ssssssss", $fecha, $nombre, $documento, $correo, $direccion, $asunto, $descripcion, $solicitud);

    if ($stmt_insert->execute()) {
        echo "PQRS enviada correctamente. Nos pondremos en contacto contigo.";
    } else {
        echo "Error al enviar la PQRS: " . $stmt_insert->error;
    }

    // Cerrar conexiones
    $stmt_insert->close();
    $conn->close();
} else {
    die("Acceso no permitido.");
}

