<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secretKey = "TU_CLAVE_SECRETA"; // Reemplaza con tu clave secreta de reCAPTCHA
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Verificar con Google
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $responseKey,
        'remoteip' => $userIP
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        // CAPTCHA válido, proceder con el inicio de sesión
        echo "CAPTCHA válido. Procesando login...";
        // Aquí colocas el código para verificar usuario y contraseña en la base de datos
    } else {
        echo "Error: reCAPTCHA no verificado.";
        exit;
    }
}
?>