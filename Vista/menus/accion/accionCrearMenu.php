<?php
// Vista/menus/accion/accionCrearMenu.php
require_once dirname(__DIR__, 3) . "/configuracion.php";
require_once $GLOBALS['CONTROL_PATH'] . "AbmMenu.php";
require_once $GLOBALS['CONTROL_PATH'] . "AbmMenuRol.php"; // si lo usás aquí

$abmMenu = new AbmMenu();

$idNuevo = $abmMenu->crearMenu($_POST); // o ->alta($_POST) si agregaste el método alta()

header("Location: ../gestionMenus.php?ok=" . ($idNuevo ? '1' : '0'));
exit;
