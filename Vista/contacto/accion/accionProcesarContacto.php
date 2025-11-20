<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Si usas Composer

// Validar reCAPTCHA
$secretKey = "TU_SECRET_KEY_AQUÍ";
$captcha = $_POST['g-recaptcha-response'];

if (!$captcha) {
    die("Error: Debe verificar el reCAPTCHA.");
}

$validarCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
$captchaResponse = json_decode($validarCaptcha);

if (!$captchaResponse->success) {
    die("Error: reCAPTCHA inválido.");
}

// Si el captcha es correcto → continuar
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$mensaje = $_POST['mensaje'];

$mail = new PHPMailer(true);

try {
    // CONFIG SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'TU_EMAIL@gmail.com';
    $mail->Password = 'TU_CONTRASEÑA_APP';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Destinos
    $mail->setFrom('TU_EMAIL@gmail.com', 'Contacto Web');
    $mail->addAddress('TU_EMAIL@gmail.com'); 

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Nuevo mensaje desde el formulario de contacto";
    $mail->Body = "
        <h3>Nuevo mensaje recibido</h3>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mensaje:</strong><br>$mensaje</p>
    ";

    $mail->send();
    echo "<script>alert('Mensaje enviado correctamente'); window.location.href='contacto.php';</script>";

} catch (Exception $e) {
    echo "<script>alert('Error al enviar el mensaje'); history.back();</script>";
}
