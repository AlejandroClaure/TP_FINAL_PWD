<?php
include_once '../../configuracion.php';
include_once $GLOBALS['CONTROL_PATH'] . 'Session.php';
include_once $GLOBALS['CONTROL_PATH'] . 'AbmProducto.php';

session_start();

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php?error=2");
    exit;
}

// ===============================
// CARRITO EN SESIÓN
// ===============================
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$carrito =& $_SESSION['carrito'];


// ===============================
// DETECCIÓN DE IMÁGENES
// ===============================
$imgDir = $GLOBALS['VISTA_PATH'] . "imagenes/productos/";
$imgBaseUrl = $GLOBALS['VISTA_URL'] . "imagenes/productos/";

if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}


// ===============================
// FUNCIONES
// ===============================
function normalizar_nombre_img($nombre) {
    $tmp = trim($nombre);
    $tmp = mb_strtolower($tmp, 'UTF-8');
    $tmp = iconv('UTF-8', 'ASCII//TRANSLIT', $tmp) ?: $tmp;
    $tmp = preg_replace('/[^a-z0-9 ]+/', '', $tmp);
    $tmp = preg_replace('/\s+/', '_', trim($tmp));
    return $tmp;
}


// ===============================
// MODIFICAR CANTIDAD
// ===============================
if (isset($_GET['id']) && isset($_GET['accion'])) {

    $idProducto = intval($_GET['id']);
    $accion = $_GET['accion'];

    $abmProducto = new AbmProducto();
    $producto = $abmProducto->buscarPorId($idProducto);

    if (!$producto) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $precio = floatval($producto->getProPrecio());
    $stock  = intval($producto->getProCantStock());

    // Nombre real sin las categorías
    $nombreBD = $producto->getProNombre();
    $partes = explode("_", $nombreBD);
    $nombreReal = array_pop($partes);

    // Guardar detalle e imagen
    $detalle = $producto->getProDetalle();
    $imagenNombre = $producto->getProImagen();

    if ($accion === 'sumar') {

        if (!isset($carrito[$idProducto])) {
            $carrito[$idProducto] = [
                'nombre'   => $nombreReal,
                'precio'   => $precio,
                'cantidad' => 0,
                'detalle'  => $detalle,
                'imagen'   => $imagenNombre
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


// ===============================
// ELIMINAR PRODUCTO
// ===============================
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);

    if (isset($carrito[$idEliminar])) {
        unset($carrito[$idEliminar]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// ===============================
// TOTALES
// ===============================
$subtotal = 0;

foreach ($carrito as $item) {
    $subtotal += floatval($item['precio']) * intval($item['cantidad']);
}

$envio = $subtotal > 0 ? 5000 : 0;
$total = $subtotal + $envio;

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

                <?php foreach ($carrito as $id => $item):

                    $nombre = htmlspecialchars(str_replace("_", " ", $item['nombre']));
                    $precio = floatval($item['precio']);
                    $cantidad = intval($item['cantidad']);

                    // Imagen
                    if ($item['imagen'] && file_exists($imgDir . $item['imagen'])) {
                        $imagenURL = $imgBaseUrl . $item['imagen'];
                    } else {
                        $imagenURL = $imgBaseUrl . "no-image.jpeg";
                    }

                    // Stock actual
                    $abmProducto = new AbmProducto();
                    $productoObj = $abmProducto->buscarPorId($id);
                    $stock = intval($productoObj->getProCantStock());

                ?>
                    <tr>
                        <td class="d-flex align-items-center">
                            <img src="<?= $imagenURL ?>" 
                                 alt="<?= $nombre ?>" 
                                 style="width:50px; height:auto; margin-right:10px;">
                            <?= $nombre ?>
                        </td>

                        <td>$<?= number_format($precio, 2, ',', '.') ?></td>

                        <td>
                            <div class="d-flex align-items-center">
                                <a href="?id=<?= $id ?>&accion=restar" class="btn btn-sm btn-secondary me-1">-</a>

                                <?= $cantidad ?>

                                <a href="?id=<?= $id ?>&accion=sumar"
                                   class="btn btn-sm btn-secondary ms-1 <?= $cantidad >= $stock ? 'disabled' : '' ?>">
                                   +
                                </a>
                            </div>
                            <small class="text-muted">Stock: <?= $stock ?></small>
                        </td>

                        <td>$<?= number_format($precio * $cantidad, 2, ',', '.') ?></td>

                        <td>
                            <a href="?eliminar=<?= $id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>


        <div class="mt-4 text-end">
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2, ',', '.') ?></p>
            <p><strong>Envío:</strong> $<?= number_format($envio, 2, ',', '.') ?></p>
            <p class="fs-4"><strong>Total:</strong> $<?= number_format($total, 2, ',', '.') ?></p>

            <a href="../producto/producto.php" class="btn btn-dark btn-lg me-2">
                Seguir Comprando
            </a>
            <a href="../compra/finalizarCompra.php" class="btn btn-success btn-lg">
                Finalizar Compra
            </a>
        </div>

    <?php endif; ?>
</div>
</main>

<?php include_once '../estructura/pie.php'; ?>
