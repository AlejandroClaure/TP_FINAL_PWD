<?php
include_once '../../../configuracion.php';

$session = new Session();
$session->cerrar();

// Volver al login
header("Location: ../login.php?logout=1");
exit;
