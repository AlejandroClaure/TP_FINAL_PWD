<?php
require_once '../../../configuracion.php';

$abm = new AbmUsuario();

$param["usnombre"] = $_POST["usnombre"];
$param["usmail"]   = $_POST["usmail"];
$param["uspass"]   = $_POST["uspass"];

$abm->registrar($param);
if ($idUsuario) {
    // asigno rol cliente = idrol 2
    $abmUR = new AbmUsuarioRol();
    $abmUR->asignarRol($idUsuario, 2);
}

// Redirección correcta
header("Location: " . $GLOBALS['BASE_URL'] . "Vista/login/login.php?ok=1");
exit;
