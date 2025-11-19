<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../configuracion.php';
require_once '../../Modelo/Venta.php';

// Cargar Autoload de Composer (DOMPDF + PHPMailer)
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

// ==============================
// 1 - DATOS DE USUARIO
// ==============================
$usuario = $session->getUsuario();
$idusuario = $usuario->getIdUsuario();

// ==============================
// 2 - CREAR LA COMPRA
// ==============================
$venta = new Venta();
$idcompra = $venta->nuevaCompra($idusuario);

// ==============================
// 3 - AGREGAR ITEMS DEL CARRITO
// ==============================
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    echo "No hay productos en el carrito.";
    exit;
}

foreach ($_SESSION['carrito'] as $item) {
    $venta->agregarItem($idcompra, $item['idproducto'], $item['cantidad']);
}

// Cambiar estado a iniciada (1)
$venta->setEstado($idcompra, 1);

// ==============================
// 4 - TRAER DATOS PARA EL PDF
// ==============================
$compra = $venta->getCompra($idcompra);
$items  = $venta->getItems($idcompra);

$fecha = date("d/m/Y H:i");

// ==============================
// 5 - GENERAR PDF DOMPDF
// ==============================
$html = "
<h2 style='text-align:center;'>Ticket de Compra Nº $idcompra</h2>
<p><b>Fecha:</b> $fecha</p>
<p><b>Cliente:</b> {$compra['usnombre']} ({$compra['usmail']})</p>

<h3>Productos adquiridos</h3>
<table width='100%' border='1' cellspacing='0' cellpadding='5'>
    <tr>
        <th>Producto</th>
        <th>Detalle</th>
        <th>Cantidad</th>
    </tr>
";

foreach ($items as $i) {
    $html .= "
    <tr>
        <td>{$i['pronombre']}</td>
        <td>{$i['prodetalle']}</td>
        <td style='text-align:center;'>{$i['cicantidad']}</td>
    </tr>
    ";
}

$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();

// Crear carpeta si no existe
$rutaCarpeta = __DIR__ . '/../../Archivos/ventas';
if (!file_exists($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
}

$rutaPDF = "$rutaCarpeta/ticket_$idcompra.pdf";

// Guardar PDF
file_put_contents($rutaPDF, $dompdf->output());

// ==============================
// 6 - ENVIAR POR MAIL
// ==============================
$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "alejandro.claure@est.fi.uncoma.edu.ar";
    $mail->Password   = "bblz pabp xvew gbjb"; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom("tu_correo@gmail.com", "Tienda Online");
    $mail->addAddress($compra['usmail'], $compra['usnombre']);
    $mail->addAttachment($rutaPDF);

    $mail->isHTML(true);
    $mail->Subject = "Confirmación de compra Nº $idcompra";
    $mail->Body = "
    Hola {$compra['usnombre']}<br><br>
    Tu compra fue registrada correctamente.<br>
    Adjuntamos tu ticket en PDF.<br><br>
    <b>Tienda Online</b>";

    $mail->send();

} catch (Exception $e) {
    file_put_contents("mail_error_log.txt", "Error: " . $mail->ErrorInfo);
}

// ==============================
// 7 - LIMPIAR CARRITO
// ==============================
unset($_SESSION['carrito']);

// ==============================
// 8 - REDIRECCIÓN
// ==============================
header("Location: compra_exitosa.php?id=$idcompra");
exit;
