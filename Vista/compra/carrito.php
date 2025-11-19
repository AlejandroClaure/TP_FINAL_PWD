<?php
include_once '../../configuracion.php';
include_once $GLOBALS['CONTROL_PATH'] . 'Session.php';
include_once $GLOBALS['CONTROL_PATH'] . 'AbmProducto.php';

session_start();

// Validar sesión
$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito =& $_SESSION['carrito'];

// ==========================
// Modificar cantidad si se recibe id y accion
// ==========================
if (isset($_GET['id']) && isset($_GET['accion'])) {
    $idProducto = $_GET['id'];
    $accion = $_GET['accion'];

    // Obtener stock del producto
    $abmProducto = new AbmProducto();
    $producto = $abmProducto->buscarPorId($idProducto);
    $stock = (int)$producto->getProCantStock();

    if ($accion === 'sumar') {
        if (!isset($carrito[$idProducto])) {
            $carrito[$idProducto] = [
                'nombre' => $producto->getProNombre(),
                'precio' => (float)str_replace(['$', ','], '', $producto->getProDetalle()),
                'cantidad' => 0
            ];
        }
        if ($carrito[$idProducto]['cantidad'] < $stock) {
            $carrito[$idProducto]['cantidad']++;
        }
    } elseif ($accion === 'restar') {
        if (isset($carrito[$idProducto])) {
            $carrito[$idProducto]['cantidad']--;
            if ($carrito[$idProducto]['cantidad'] <= 0) {
                unset($carrito[$idProducto]);
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ==========================
// Eliminar producto si se recibe idEliminar
// ==========================
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    if (isset($carrito[$idEliminar])) {
        unset($carrito[$idEliminar]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ==========================
// Calcular totales
// ==========================
$subtotal = 0;
foreach ($carrito as $item) {
    $subtotal += floatval($item['precio']) * intval($item['cantidad']);
}

$envio = $subtotal > 0 ? 5000 : 0; // ejemplo fijo de envío
$total  = $subtotal + $envio;
?>

<?php include_once '../estructura/cabecera.php'; ?>

<main class="mt-5 pt-5">
    <div class="container mt-5">
        <h3>Mi carrito</h3>

        <?php if (empty($carrito)): ?>
            <p class="text-muted">No hay productos en el carrito.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $id => $item): ?>
                            <?php
                                // Obtener stock real
                                $abmProducto = new AbmProducto();
                                $productoObj = $abmProducto->buscarPorId($id);
                                $stock = (int)$productoObj->getProCantStock();
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nombre']); ?></td>
                                <td>$<?= number_format(floatval($item['precio']), 2, ',', '.'); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="?id=<?= $id; ?>&accion=restar" class="btn btn-sm btn-secondary me-1">-</a>
                                        <?= intval($item['cantidad']); ?>
                                        <a href="?id=<?= $id; ?>&accion=sumar" class="btn btn-sm btn-secondary ms-1 <?= $item['cantidad'] >= $stock ? 'disabled' : ''; ?>">+</a>
                                    </div>
                                    <small class="text-muted">Stock: <?= $stock; ?></small>
                                </td>
                                <td>$<?= number_format(floatval($item['precio']) * intval($item['cantidad']), 2, ',', '.'); ?></td>
                                <td>
                                    <a href="?eliminar=<?= $id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-end">
                <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2, ',', '.'); ?></p>
                <p><strong>Envío:</strong> $<?= number_format($envio, 2, ',', '.'); ?></p>
                <p class="fs-4"><strong>Total:</strong> $<?= number_format($total, 2, ',', '.'); ?></p>
                <a href="#" class="btn btn-success btn-lg">Proceder al Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once '../estructura/pie.php'; ?>
