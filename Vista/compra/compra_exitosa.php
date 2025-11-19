<?php
require_once '../../configuracion.php';

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

// ================================
// OBTENER EL ID DE LA COMPRA
// ================================
$idventa = $_GET['id'] ?? $_GET['idventa'] ?? null;

if (!$idventa) {
    echo "Error: No se encontró el número de operación.";
    exit;
}

// ================================
// OBTENER DATOS DEL USUARIO
// ================================
$usuario = $session->getUsuario();
$nombreUsuario = $usuario ? $usuario->getUsNombre() : "Cliente";

// ================================
// CABECERA
// ================================
include_once '../estructura/cabecera.php';
?>

<div class="container mt-5 mb-5">

    <div class="card p-4 shadow-lg">

        <h1 class="text-success text-center mb-4">
            ✔ ¡Compra realizada con éxito!
        </h1>

        <p class="text-center fs-5">
            Gracias por tu compra, <b><?= htmlspecialchars($nombreUsuario) ?></b>.
        </p>

        <p class="text-center fs-4">
            Tu número de operación es:
        </p>

        <h2 class="text-center display-5">
            <?= htmlspecialchars($idventa) ?>
        </h2>

        <div class="text-center mt-4">
            <a
                class="btn btn-primary btn-lg"
                target="_blank"
                href="<?= $GLOBALS['VISTA_URL'] ?>compra/comprobante.php?idventa=<?= urlencode($idventa) ?>">
                📄 Ver Comprobante en PDF
            </a>

            <a
                class="btn btn-secondary btn-lg ms-2"
                href="<?= $GLOBALS['BASE_URL'] ?>index.php">
                Volver al inicio
            </a>
        </div>

    </div>

</div>

<?php
// ================================
// PIE
// ================================
include_once '../estructura/pie.php';
?>