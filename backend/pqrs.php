<?php
global $conn;
require_once "db.php"; // Archivo con la conexión a la base de datos
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Servidor SMTP de Mailtrap
            $mail->SMTPAuth = true;
            $mail->Username = 'f390c28a3691c6'; // Usuario SMTP
            $mail->Password = 'da36a51cda950b'; // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad TLS
            $mail->Port = 587; // Puerto SMTP

            // Configuración del correo
            $mail->setFrom('no-reply@fondobecas.gov.co', 'Fondo Municipal de Becas');
            $mail->addAddress($correo, $nombre);
            $mail->Subject = "Confirmación de recepción de PQRS";
            $mail->Body = "Hola $nombre,\n\nHemos recibido tu PQRS con el asunto: '$asunto'.\nNuestro equipo la revisará y te contactaremos pronto.\n\nGracias por comunicarte con el Fondo Municipal de Becas y Subsidios.\n\nAtentamente,\nEquipo de Atención PQRS";

            // Enviar correo
            $mail->send();
            echo "PQRS enviada correctamente. Se ha enviado un correo de confirmación.";
        } catch (Exception $e) {
            echo "PQRS enviada, pero no se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error al enviar la PQRS: " . $stmt_insert->error;
    }


    // Cerrar conexiones
    $stmt_insert->close();
    $conn->close();
} else {
    die("Acceso no permitido.");
}

