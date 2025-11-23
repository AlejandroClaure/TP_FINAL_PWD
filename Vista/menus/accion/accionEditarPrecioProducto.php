<?php
include_once '../../../configuracion.php';

$abmProducto = new AbmProducto();

$id = $_POST['idproducto'] ?? null;
$precio = $_POST['proprecio'] ?? null;

if (!$id || !$precio) {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

$exito = $abmProducto->modificar([
    "idproducto" => $id,
    "proprecio"  => $precio
]);

header("Location: ../gestionMenus.php?ok=" . ($exito ? 1 : 0));
exit;
