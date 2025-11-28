<?php
require_once dirname(__DIR__, 3) . '/util/funciones.php';
require_once dirname(__DIR__, 3) . '/configuracion.php';
// Creo la sesión
$session = new Session();
$datos = data_submitted();

$usnombre = $datos['usnombre'] ?? '';
$uspass   = $datos['uspass'] ?? '';
// Intento iniciar sesión
if ($session->iniciar($usnombre, $uspass)) {

    // Login correcto → redirige al menú seguro
    header("Location: ../paginaSegura.php");
    exit;

} else {

    // Login incorrecto
    header("Location: ../login.php?error=1");
    exit;
}
