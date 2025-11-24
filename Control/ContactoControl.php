<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactoControl {

    public function procesarFormulario($data) {

        $nombre  = trim($data['nombre'] ?? '');
        $email   = trim($data['email'] ?? '');
        $mensaje = trim($data['mensaje'] ?? '');
        $captcha = $data['g-recaptcha-response'] ?? '';

        if (!$this->validarCampos($nombre, $email, $mensaje)) {
            return ['success' => false, 'msg' => 'Formulario incompleto'];
        }

        if (!$this->validarCaptcha($captcha)) {
            return ['success' => false, 'msg' => 'Captcha incorrecto'];
        }

        return $this->enviarMail($nombre, $email, $mensaje);
    }

    private function validarCampos($n, $e, $m) {
        return !empty($n) && !empty($e) && !empty($m);
    }

    private function validarCaptcha($captcha) {
        $secretKey = "6LcWHhMsAAAAACI_3lxNzikxT4eKcwm7BGKA2kJh";
        $val = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
        $res = json_decode($val);
        return $res->success;
    }

    private function enviarMail($nombre, $email, $mensaje) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nasabunc@gmail.com'; 
            $mail->Password   = 'hnsg eebv wezw btku';                 
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('nasabunc@gmail.com', 'Formulario Web');
            $mail->addAddress('nasabunc@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = "Contacto Celulandia";
            $mail->Body = "
                <h3>Mensaje enviado por cliente desde la web</h3>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Mensaje:</strong><br>$mensaje</p>
            ";

            $mail->send();

            return ['success' => true];

        } catch (Exception $e) {
            return ['success' => false, 'msg' => $mail->ErrorInfo];
        }
    }
}
?>