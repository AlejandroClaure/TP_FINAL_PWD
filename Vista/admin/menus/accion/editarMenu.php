<?php
include_once dirname(__DIR__, 4) . '/configuracion.php';
include_once dirname(__DIR__, 4) . '/Control/AbmMenu.php';

$abmMenu = new AbmMenu();

$idmenu = $_POST['idmenu'] ?? null;
$menombre = trim($_POST['menombre'] ?? "");
$tipo = $_POST['tipo'] ?? "raiz";
$idPadre = $_POST['idpadre'] ?? null;

if (!$idmenu || $menombre === "") {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

// Normalizar nombre
$menombre = ucfirst($menombre);

// Slug seguro
$slug = strtolower(trim($menombre));
$slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
$slug = trim($slug, "-");

// Generar ruta PHP
$ruta = $slug . ".php";
if ($tipo === "sub" && $idPadre) {
    $padre = $abmMenu->buscar(['idmenu' => $idPadre])[0];
    $padreSlug = strtolower(str_replace(".php", "", $padre->getMeDescripcion()));
    $ruta = $padreSlug . "/" . $slug . ".php";
}

// Evitar que un menú sea su propio padre
if ($idPadre == $idmenu) {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}

// Datos para modificar
$datos = [
    "idmenu" => $idmenu,
    "menombre" => $menombre,
    "medescripcion" => $ruta,
    "idpadre" => ($tipo === "sub") ? $idPadre : null
];

if ($abmMenu->modificacion($datos)) {
    header("Location: ../gestionMenus.php?ok=1");
    exit;
} else {
    header("Location: ../gestionMenus.php?ok=0");
    exit;
}
