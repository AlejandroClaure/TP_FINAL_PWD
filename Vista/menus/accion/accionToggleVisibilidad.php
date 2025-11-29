<?php
include_once dirname(__DIR__, 3) . "/configuracion.php";
include_once dirname(__DIR__, 3) . "/Control/AbmMenu.php";

$id = $_GET["idmenu"] ?? null;
//Exito
if (!$id) {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

$abm = new AbmMenu();
$resultado = $abm->accionToggleVisibilidad($abm,$id);

// Fallos
if (!$resultado['estado']) {

    if ($resultado['error'] === "no_encontrado") {
        header("Location: ../gestionMenus.php?error=notfound");
        exit;
    }

    header("Location: ../gestionMenus.php?error=1");
    exit;
}