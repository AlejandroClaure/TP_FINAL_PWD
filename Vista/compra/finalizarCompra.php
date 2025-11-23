<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../configuracion.php';
require_once '../../Modelo/Venta.php';
require_once '../../vendor/autoload.php'; // DOMPDF + PHPMailer

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
$usuario   = $session->getUsuario();
$idusuario = $usuario->getIdUsuario();

// ==============================
// 2 - VALIDAR CARRITO
// ==============================
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    echo "No hay productos en el carrito.";
    exit;
}

// ==============================
// 3 - CREAR LA COMPRA
// ==============================
$venta = new Venta();
$idcompra = $venta->nuevaCompra($idusuario);

// ==============================
// 4 - AGREGAR ITEMS Y DESCONTAR STOCK
// ==============================
$bd = new BaseDatos();

foreach ($_SESSION['carrito'] as $item) {
    $idProducto = $item['idproducto'];
    $cantidad   = $item['cantidad'];

    // Insertar línea de compra
    $venta->agregarItem($idcompra, $idProducto, $cantidad);

    // Descontar stock
    $sqlUpdate = "UPDATE producto SET proCantStock = proCantStock - $cantidad WHERE idproducto = $idProducto";
    $bd->Ejecutar($sqlUpdate);
}

// Cambiar estado compra a iniciada (1)
$venta->setEstado($idcompra, 1);

// ==============================
// 5 - TRAER DATOS PARA EL PDF
// ==============================
$compra = $venta->getCompra($idcompra);
$items  = $venta->getItems($idcompra);
$fecha  = date("d/m/Y H:i");

// ==============================
// 6 - GENERAR PDF
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
file_put_contents($rutaPDF, $dompdf->output());

// ==============================
// 7 - ENVIAR CORREO CON PDF
// ==============================
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "alejandro.claure@est.fi.uncoma.edu.ar"; // tu mail
    $mail->Password   = "bblz pabp xvew gbjb";                     // contraseña de app
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom("alejandro.claure@est.fi.uncoma.edu.ar", "Tienda Online");
    $mail->addAddress($compra['usmail'], $compra['usnombre']);
    $mail->addAttachment($rutaPDF);

    $mail->isHTML(true);
    $mail->Subject = "Confirmación de compra Nº $idcompra";
    $mail->Body = "
        Hola {$compra['usnombre']}<br><br>
        Tu compra fue registrada correctamente.<br>
        Adjuntamos tu ticket en PDF.<br><br>
        <b>Gracias por tu compra.</b>
    ";

    $mail->send();
} catch (Exception $e) {
    file_put_contents("mail_error_log.txt", "Error al enviar mail: " . $mail->ErrorInfo);
}

// ==============================
// 8 - LIMPIAR CARRITO
// ==============================
unset($_SESSION['carrito']);

// ==============================
// 9 - REDIRECCIÓN
// ==============================
header("Location: compra_exitosa.php?id=$idcompra");
exit;
