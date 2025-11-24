<?php
include_once '../../../configuracion.php';

$datos = data_submitted();
$session = new Session();

if ($session->iniciar($datos['usnombre'], $datos['uspass'])) {
    header("Location: " . $GLOBALS['BASE_URL'] . "../paginaSegura.php?ok=1");
    exit;
} 

// Si falla → vuelve al login
header("Location: " . $GLOBALS['BASE_URL'] . "Vista/login/login.php?error=1");
exit;
    
