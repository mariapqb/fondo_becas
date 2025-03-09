<?php
require __DIR__ . '/../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mariaquintinblandon@gmail.com';
    $mail->Password = 'Maria220303';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('mariap.quintinb@uqvirtual.edu.co', 'Prueba PHPMailer');
    $mail->addAddress('mpquintin2003@gmail.com');

    $mail->Subject = 'Prueba de PHPMailer';
    $mail->Body = '¡PHPMailer está funcionando correctamente en tu proyecto!';

    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
