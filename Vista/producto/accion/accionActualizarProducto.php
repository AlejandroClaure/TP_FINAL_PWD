<?php
require_once "../../../configuracion.php";

$session = new Session();
if (!$session->activa() || !$session->esAdmin()) 
    exit(header("Location: " . $GLOBALS['VISTA_URL'] . "error/noAutorizado.php"));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
    exit(header("Location: ../../producto/listarProductos.php"));

$id = (int)($_POST['idproducto'] ?? 0);
if ($id <= 0) 
    exit(header("Location: ../editarProducto.php?id=$id&error=ID+inválido"));

$datos = [
    'idproducto'       => $id,
    'pronombre'        => trim($_POST['pronombre'] ?? ''),
    'prodetalle'       => trim($_POST['prodetalle'] ?? ''),
    'proprecio'        => (float)($_POST['proprecio'] ?? 0),
    'procantstock'     => (int)($_POST['procantstock'] ?? 0),
    'prooferta'        => (int)($_POST['prooferta'] ?? 0),
    'proimagen'        => $_POST['proimagen'] ?? null,
    'idusuario'        => $session->getUsuario()->getIdUsuario(),
    'prodeshabilitado' => !empty($_POST['deshabilitar']) ? date('Y-m-d H:i:s') : null
];

// Validaciones (100% igual que antes)
if (empty($datos['pronombre']) || $datos['proprecio'] <= 0 || $datos['procantstock'] < 0) {
    exit(header("Location: ../editarProducto.php?id=$id&error=Datos+inválidos"));
}

$exito = (new AbmProducto())->modificar($datos);
exit(header("Location: ../editarProducto.php?id=$id" . ($exito ? "&msg=¡Actualizado!" : "&error=Error+al+guardar")));