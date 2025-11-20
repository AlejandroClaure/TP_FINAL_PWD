<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../configuracion.php';

// Captura POST
$nombre  = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

if (empty($nombre) || empty($email) || empty($mensaje)) {
    die("Error: Formulario incompleto.");
}

// reCAPTCHA v2
$captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
if (!$captcha) die("Error: Debe verificar el reCAPTCHA.");

$secretKey = "6LfrKxMsAAAAAHcrhiI14qKIRAW62arFAQCse-1K"; 
$validarCaptcha = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha"
);
$captchaResponse = json_decode($validarCaptcha);
if (!$captchaResponse->success) die("Error: reCAPTCHA inválido.");

// PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'alejandro.claure@est.fi.uncoma.edu.ar'; 
    $mail->Password   = 'bblz pabp xvew gbjb';                     
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('alejandro.claure@est.fi.uncoma.edu.ar', 'Formulario Web');
    $mail->addAddress('alejandro.claure@est.fi.uncoma.edu.ar'); 

    $mail->isHTML(true);
    $mail->Subject = "Nuevo mensaje desde el formulario de contacto";
    $mail->Body = "
        <h3>Nuevo mensaje recibido</h3>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mensaje:</strong><br>$mensaje</p>
    ";

    $mail->send();

    echo "<script>alert('Mensaje enviado correctamente'); window.location.href='" . $GLOBALS['VISTA_URL'] . "contacto/contacto.php';</script>";

} catch (Exception $e) {
    echo "<script>alert('Error al enviar el mensaje: " . $mail->ErrorInfo . "'); history.back();</script>";
}
