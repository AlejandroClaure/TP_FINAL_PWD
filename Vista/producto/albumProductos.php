<?php
include_once '../../configuracion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar sesión
$session = new Session();

// Incluir AbmProducto
include_once $GLOBALS['CONTROL_PATH'] . 'AbmProducto.php';

$abmProducto = new AbmProducto();

// 🔥 Ahora solo carga los productos habilitados
if (method_exists($abmProducto, 'listarHabilitados')) {
    $productos = $abmProducto->listarHabilitados();
} else {
    // fallback si todavía no creó la función
    $productos = $abmProducto->listar("prodeshabilitado IS NULL");
}

// RUTA A LA CARPETA DE IMÁGENES
$imgBaseUrl = $GLOBALS['VISTA_URL'] . "imagenes/productos/";
$imgDir     = dirname(__DIR__, 1) . "/imagenes/productos/";
?>

<div class="row g-4">

    <?php if (empty($productos)): ?>
        <p class="text-center text-muted">No hay productos cargados.</p>

    <?php else: ?>

        <?php foreach ($productos as $prod): ?>

            <?php
            // Nombre completo y visible
            $nombreCompleto  = $prod->getProNombre();
            $partes          = explode('_', $nombreCompleto);
            $nombreReal      = end($partes);
            $nombreVisible   = str_replace('_', ' ', $nombreReal);

            // Imagen desde BD
            $imagenBD = $prod->getProimagen();

            // Verificar imagen física
            if ($imagenBD && file_exists($imgDir . $imagenBD)) {
                $imagenURL = $imgBaseUrl . $imagenBD;
            } else {
                $imagenURL = $imgBaseUrl . "no-image.jpeg";
            }

            // Precio
            $precio = (float) $prod->getProPrecio();

            // Stock
            $stock = (int) $prod->getProCantStock();
            ?>

            <div class="col-md-4 col-lg-3">
                <div class="card shadow-sm h-100 product-card">

                    <img src="<?= htmlspecialchars($imagenURL, ENT_QUOTES); ?>"
                         class="card-img-top producto-img"
                         alt="<?= htmlspecialchars($nombreVisible); ?>"
                         onerror="this.src='<?= $imgBaseUrl; ?>no-image.jpeg';">

                    <div class="card-body">

                        <h5 class="card-title">
                            <?= htmlspecialchars($nombreVisible); ?>
                        </h5>

                        <p class="text-success fw-bold fs-5">
                            $<?= number_format($precio, 2, ',', '.'); ?>
                        </p>

                        <p class="card-text small text-muted">
                            <?= $prod->getProdetalle(); ?>
                        </p>

                        <p class="text-muted">
                            Stock: <?= $stock; ?>
                        </p>

                        <a href="<?= $GLOBALS['VISTA_URL']; ?>compra/accion/agregarCarrito.php?id=<?= $prod->getIdProducto(); ?>"
                           class="btn btn-warning w-100 <?= $stock <= 0 ? 'disabled' : ''; ?>">
                            <i class="fa fa-shopping-cart"></i>
                            <?= $stock > 0 ? 'Agregar al carrito' : 'Sin stock'; ?>
                        </a>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>
