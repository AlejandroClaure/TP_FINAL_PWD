<?php
// Archivo: Vista/admin/roles/accion/rolesDeUsuario.php

ob_clean();
header('Content-Type: application/json; charset=utf-8');

// Ruta correcta: subir 5 niveles desde esta carpeta hasta la raíz del proyecto
$rootPath = dirname(__DIR__, 4); // ← 5 niveles arriba: accion → roles → admin → Vista → PWD_TPFinal
require_once $rootPath . '/configuracion.php';

$idusuario = $_GET['idusuario'] ?? null;

if (!$idusuario || !is_numeric($idusuario)) {
    echo json_encode([]);
    exit;
}

try {
    $abmUR = new AbmUsuarioRol();
    $roles = $abmUR->rolesDeUsuarioConID($idusuario);
    echo json_encode($roles, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor']);
}

exit;