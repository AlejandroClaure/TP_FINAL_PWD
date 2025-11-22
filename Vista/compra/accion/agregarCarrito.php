<?php
include_once dirname(__DIR__, 3) . '/configuracion.php';
include_once dirname(__DIR__, 3) . '/Control/Session.php';
include_once dirname(__DIR__, 3) . '/Control/AbmProducto.php';

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

$idProducto = $_GET['id'] ?? null;
$cantidad   = max(1, intval($_POST['cantidad'] ?? 1));

if (!$idProducto || !is_numeric($idProducto)) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "producto/albumProductos.php?error=1");
    exit;
}

$abmProducto = new AbmProducto();
$producto = $abmProducto->buscarPorId($idProducto);

if (!$producto || $producto->getProDeshabilitado()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "producto/albumProductos.php?error=2");
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito =& $_SESSION['carrito'];

// Nombre real sin categorías
$nombreBD = $producto->getProNombre();
$partes = explode("_", $nombreBD);
$nombreReal = array_pop($partes);

// Agregar o incrementar
if (isset($carrito[$idProducto])) {
    $carrito[$idProducto]['cantidad'] += $cantidad;
} else {
    $carrito[$idProducto] = [
        'idproducto' => $producto->getIdProducto(),
        'nombre'     => $nombreReal,
        'precio'     => floatval($producto->getProPrecio()),
        'detalle'    => $producto->getProDetalle(),
        'imagen'     => $producto->getProImagen(),
        'stock'      => intval($producto->getProCantStock()),
        'cantidad'   => $cantidad
    ];
}

header("Location: " . $GLOBALS['VISTA_URL'] . "compra/carrito.php?ok=1");
exit;
