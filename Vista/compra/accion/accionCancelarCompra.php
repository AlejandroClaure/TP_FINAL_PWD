<?php
include_once '../../../configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->tieneRol('cliente')) {
    header("Location: ../../login/login.php");
    exit;
}

$idCompra = $_GET['id'] ?? null;

if (!$idCompra) {
    header("Location: ../verCompraCliente.php?msg=error_id");
    exit;
}

$abmEstado = new AbmCompraEstado();

// 4 = cancelada
$ok = $abmEstado->cambiarEstadoCompra($idCompra, 4);

if ($ok) {
    header("Location: ../detalleCompra.php?id=$idCompra&msg=cancel_ok");
} else {
    header("Location: ../detalleCompra.php?id=$idCompra&msg=cancel_fail");
}

exit;
