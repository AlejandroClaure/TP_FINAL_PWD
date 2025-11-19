<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../configuracion.php';
require_once '../../Modelo/Venta.php';

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

// Validar ID de compra recibido por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Error: No se encontró el número de operación.";
    exit;
}

$idventa = intval($_GET['id']);

// Incluir cabecera
include_once '../estructura/cabecera.php';

// Ruta al PDF generado
$rutaPDF = "../../Archivos/ventas/ticket_$idventa.pdf";

// Traer datos de compra
$venta = new Venta();
$usuario = $session->getUsuario();
?>

<div class="container mt-5 mb-5">

    <h2 class="text-success">✔ ¡Compra realizada con éxito!</h2>

    <p class="lead">
        Gracias por tu compra, <b><?= htmlspecialchars($usuario->getUsNombre()); ?></b>.
    </p>

    <p>
        Tu número de operación es: <b><?= $idventa ?></b>
    </p>

    <hr>

    <?php if (file_exists($rutaPDF)) : ?>
        <a 
            class="btn btn-primary btn-lg mt-3"
            href="<?= $GLOBALS['BASE_URL'] ?>Archivos/ventas/ticket_<?= $idventa ?>.pdf"
            target="_blank"
        >
            📄 Descargar Comprobante PDF
        </a>
    <?php else : ?>
        <div class="alert alert-danger">
            ❌ El comprobante no se encontró en el servidor.
        </div>
    <?php endif; ?>

    <br><br>

    <a 
        class="btn btn-secondary btn-lg"
        href="<?= $GLOBALS['BASE_URL'] ?>index.php"
    >
        Volver al inicio
    </a>

</div>

<?php include_once '../estructura/pie.php'; ?>
