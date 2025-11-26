<?php
require_once "../../../configuracion.php";

$session = new Session();
if (!$session->activa() || !$session->esAdmin()) exit;

$id = $_POST['idproducto'] ?? 0;
if ($id > 0) {
    $abm = new AbmProducto();
    if ($abm->eliminar($id)) {
        header("Location: ../../producto/listarProductos.php?msg=Producto deshabilitado");
    } else {
        header("Location: ../../producto/listarProductos.php?error=No se pudo deshabilitar");
    }
} else {
    header("Location: ../../producto/listarProductos.php");
}
exit;