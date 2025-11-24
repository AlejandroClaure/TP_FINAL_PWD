<?php
require_once '../../../configuracion.php';

$auth = new AbmAuth();

if ($auth->registrarYLogin($_POST)) {
    header("Location: " . $GLOBALS['BASE_URL'] . "Vista/login/login.php?ok=1");
    exit;
}

header("Location: " . $GLOBALS['BASE_URL'] . "Vista/login/registro.php?error=1");
exit;