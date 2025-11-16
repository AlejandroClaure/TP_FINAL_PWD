<?php
// Nombre del proyecto
$PROYECTO = 'PWD_TPFinal';

// Ruta física
$ROOT = $_SERVER['DOCUMENT_ROOT'] . "/$PROYECTO/";

// URL pública
$BASE_URL = "/" . $PROYECTO . "/";

// Rutas globales
$GLOBALS['ROOT_PROYECTO'] = $ROOT;
$GLOBALS['BASE_URL'] = $BASE_URL;
$GLOBALS['VISTA_URL'] = $BASE_URL . "Vista/";
$GLOBALS['CSS_URL'] = $BASE_URL . "Vista/css/";
$GLOBALS['IMG_URL'] = $BASE_URL . "Vista/imagenes/";
?>
