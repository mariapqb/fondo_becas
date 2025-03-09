<?php
require __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io'; // Servidor SMTP de Mailtrap
    $mail->SMTPAuth = true;
    $mail->Username = 'f390c28a3691c6'; // Usuario SMTP
    $mail->Password = 'da36a51cda950b'; // Contraseña SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad TLS
    $mail->Port = 587; // Puerto SMTP

    $mail->setFrom('mariaquintinblandon@gmail.com', 'Prueba PHPMailer');
    $mail->addAddress('mpquintin2003@gmail.com');

    $mail->Subject = 'Prueba de PHPMailer';
    $mail->Body = '¡PHPMailer está funcionando correctamente en tu proyecto!';

    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
