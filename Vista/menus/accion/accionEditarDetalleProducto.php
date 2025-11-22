<?php
include '../../../configuracion.php';
$abm = new AbmProducto();

$id = $_POST['idproducto'];
$detalle = $_POST['prodetalle'];

$abm->modificar([
    'idproducto' => $id,
    'prodetalle' => $detalle
]);

header("Location: ../gestionMenus.php?ok=1");
