<?php
require_once "db.php"; // Conexión a la base de datos
require __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PQRS {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrarPQRS($nombre_completo, $documento_identidad, $correo_electronico, $direccion, $asunto, $descripcion, $solicitud_especifica): string {
        if (empty($nombre_completo) || empty($documento_identidad) || empty($asunto) || empty($descripcion)) {
            return "Los campos obligatorios no pueden estar vacíos.";
        }

        if (!empty($correo_electronico) && !filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
            return "Correo electrónico no válido.";
        }

        $fecha = date("Y-m-d"); // Obtener la fecha actual

        $query = "INSERT INTO pqrs (fecha, nombre_completo, documento_identidad, correo_electronico, direccion, asunto, descripcion, solicitud_especifica) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return "Error en la base de datos: " . $this->conn->error;
        }

        $stmt->bind_param("ssssssss", $fecha, $nombre_completo, $documento_identidad, $correo_electronico, $direccion, $asunto, $descripcion, $solicitud_especifica);

        if ($stmt->execute()) {
            // Enviar correo de confirmación
            if (!empty($correo_electronico)) {
                $this->enviarCorreo($correo_electronico, $nombre_completo, $asunto, $descripcion);
            }
            return "PQRS registrado exitosamente.";
        } else {
            return "Error al registrar PQRS: " . $stmt->error;
        }
    }

    private function enviarCorreo($correo, $nombre, $asunto, $descripcion) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Servidor SMTP de Mailtrap
            $mail->SMTPAuth = true;
            $mail->Username = 'f390c28a3691c6'; // Usuario SMTP
            $mail->Password = 'da36a51cda950b'; // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad TLS
            $mail->Port = 587; // Puerto SMTP

            // Configuración del remitente y destinatario
            $mail->setFrom('fondoBecas@gmail.com.com', 'Soporte Fondo de Becas');
            $mail->addAddress($correo, $nombre);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "Confirmación de PQRS: $asunto";
            $mail->Body = "
                <h2>Estimado/a $nombre,</h2>
                <p>Hemos recibido tu solicitud de PQRS con el siguiente detalle:</p>
                <p><strong>Asunto:</strong> $asunto</p>
                <p><strong>Descripción:</strong> $descripcion</p>
                <p>Pronto te daremos una respuesta.</p>
                <p>Gracias por contactarnos.</p>
            ";

            // Enviar correo
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}
