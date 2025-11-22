<?php
include '../../../configuracion.php';
$abm = new AbmProducto();

$id = $_GET['id'];

$prod = $abm->listar("idproducto = $id")[0];
$actual = $prod->getProDeshabilitado();
$nuevo = $actual ? null : date("Y-m-d H:i:s");

$abm->modificar([
    'idproducto' => $id,
    'prodeshabilitado' => $nuevo
]);

header("Location: ../gestion.php?toggle=1");
