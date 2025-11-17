<?php
include_once '../../../configuracion.php';

// Creo la sesión
$session = new Session();

// Obtengo datos enviados desde login.php
$usnombre = $_POST['usnombre'] ?? '';
$uspass   = $_POST['uspass'] ?? '';

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
