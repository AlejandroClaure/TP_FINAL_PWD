<?php
require_once dirname(__DIR__, 4) . '/configuracion.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$session = new Session();

if (!$session->activa() || !in_array("admin", ($_SESSION['roles'] ?? []))) {
    echo json_encode(['success' => false]);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = (int)$_POST['id'];
$abm = new AbmProducto();
$prod = $abm->buscarPorId($id);

if (!$prod) {
    echo json_encode(['success' => false]);
    exit;
}

$nuevoStock = $prod->getProCantStock();

// ¿Viene cambio +1/-1 o valor directo?
if (isset($_POST['cambio'])) {
    $nuevoStock += (int)$_POST['cambio'];
} elseif (isset($_POST['stock'])) {
    $nuevoStock = max(0, (int)$_POST['stock']);
}

$nuevoStock = max(0, $nuevoStock); // nunca negativo

$param = [
    'idproducto'   => $id,
    'pronombre'     => $prod->getProNombre(),
    'prodetalle'   => $prod->getProDetalle(),
    'procantstock' => $nuevoStock,
    'idusuario'    => $prod->getIdUsuario()
];

$exito = $abm->modificar($param);

echo json_encode([
    'success' => $exito,
    'nuevoStock' => $nuevoStock
]);
exit;