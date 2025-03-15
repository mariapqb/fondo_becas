<?php
// Incluir el archivo de conexión a la base de datos
require_once "db.php"; // Conexión a la base de datos
require __DIR__ . '/../vendor/autoload.php'; // Cargar las dependencias de Composer

// Importar las clases de PHPMailer para el envío de correos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PQRS {

    private $conn;// Variable para manejar la conexión a la base de datos

    /**
     * Constructor de la clase PQRS.
     * @param $conn: objeto de conexión a la base de datos.
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }
    /**
     * Método para registrar una PQRS (Petición, Queja, Reclamo o Sugerencia).
     * @param string $nombre_completo Nombre completo del usuario.
     * @param string $documento_identidad Documento de identidad del usuario.
     * @param string $correo_electronico Correo electrónico del usuario (opcional).
     * @param string $direccion Dirección de residencia del usuario (opcional).
     * @param string $asunto Asunto de la solicitud.
     * @param string $descripcion Descripción detallada de la solicitud.
     * @param string $solicitud_especifica Tipo específico de solicitud.
     * @return string Mensaje con el resultado del registro.
     */
    public function registrarPQRS($nombre_completo, $documento_identidad, $correo_electronico, $direccion, $asunto, $descripcion, $solicitud_especifica): string {
        // Validar que los campos obligatorios no estén vacíos
        if (empty($nombre_completo) || empty($documento_identidad) || empty($asunto) || empty($descripcion)) {
            return "Los campos obligatorios no pueden estar vacíos.";
        }
        // Validar el formato del correo electrónico si se proporciona
        if (!empty($correo_electronico) && !filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
            return "Correo electrónico no válido.";
        }

        $fecha = date("Y-m-d"); // Obtener la fecha actual

        // Consulta SQL para insertar la PQRS en la base de datos
        $query = "INSERT INTO pqrs (fecha, nombre_completo, documento_identidad, correo_electronico, direccion, asunto, descripcion, solicitud_especifica) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return "Error en la base de datos: " . $this->conn->error;
        }


        // Asignar los parámetros y ejecutar la consulta

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

    /**Función patra enviar correo de confirmación
     * @param $correo
     * @param $nombre
     * @param $asunto
     * @param $descripcion
     * @return void
     */
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
