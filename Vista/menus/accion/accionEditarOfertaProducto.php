<?php
include '../../../configuracion.php';
$abm = new AbmProducto();

$id = $_POST['idproducto'];
$desc = $_POST['prooferta'];
$fin  = $_POST['profinoffer'];

$abm->modificar([
    'idproducto' => $id,
    'prooferta' => $desc,
    'profinoffer' => $fin
]);

header("Location: ../gestionMenus.php?ok=1");
