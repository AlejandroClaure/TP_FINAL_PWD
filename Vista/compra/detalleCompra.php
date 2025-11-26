<?php
include_once '../../configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->tieneRol('cliente')) {
    header("Location: ../login/login.php");
    exit;
}

$idCompra = $_GET['id'] ?? null;

if (!$idCompra) {
    header("Location: verCompraCliente.php?msg=error_id");
    exit;
}

$abmCompra = new AbmCompra();
$abmCompraEstado = new AbmCompraEstado();
$abmCompraItem = new AbmCompraItem();

// CARGO LA COMPRA
$compra = $abmCompra->buscar(['idcompra' => $idCompra])[0] ?? null;

if (!$compra) {
    header("Location: verCompraCliente.php?msg=compra_no_existente");
    exit;
}

// ITEMS
$items = $abmCompraItem->buscar(['idcompra' => $idCompra]);

// HISTORIAL DE ESTADOS
$historial = $abmCompraEstado->buscar(['idcompra' => $idCompra]);

// ESTADO ACTUAL Y POSIBILIDAD DE CANCELAR
$estadoActual = $compra->getEstadoActual();
if ($estadoActual) {
    $descripcionActual = $estadoActual->getObjCompraEstadoTipo()->getCetDescripcion();
    $sePuedeCancelar = in_array($descripcionActual, ['Iniciada', 'Aceptada']);
} else {
    $descripcionActual = '—';
    $sePuedeCancelar = false;
}

include_once "../estructura/cabecera.php";
?>

<div class="container mt-5">

    <h2 class="mb-4">
        📄 Detalle de la Compra #<?= $compra->getIdcompra(); ?>
    </h2>

    <!-- DATOS PRINCIPALES -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Fecha:</strong> <?= $compra->getCoFecha(); ?></p>

            <p>
                <strong>Estado Actual:</strong>
                <span class="badge bg-info">
                    <?= $descripcionActual; ?>
                </span>
            </p>

            <p><strong>Total:</strong> $<?= number_format($compra->getTotal(), 2, ',', '.'); ?></p>

            <?php if ($sePuedeCancelar): ?>
                <a href="accion/accionCancelarCompra.php?id=<?= $compra->getIdcompra(); ?>"
                   class="btn btn-danger"
                   onclick="return confirm('¿Seguro que querés cancelar esta compra?');">
                    Cancelar compra
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- LISTA DE PRODUCTOS -->
    <h4 class="mb-3">🧾 Ítems de la compra</h4>

    <div class="table-responsive mb-4 shadow-sm">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $producto = $item->getObjProducto();
                    // Calcula subtotal de forma segura
                    $subtotal = ($item->getCiCantidad() ?? 0) * ($producto->getProprecio() ?? 0);
                ?>
                    <tr>
                        <td><?= $producto->getPronombre(); ?></td>
                        <td><?= $item->getCiCantidad(); ?></td>
                        <td>$<?= number_format($producto->getProprecio(), 2, ',', '.'); ?></td>
                        <td>$<?= number_format($subtotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- HISTORIAL DE ESTADOS -->
    <h4 class="mb-3">📜 Historial de Estados</h4>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $estado): 
                    $tipo = $estado->getObjCompraEstadoTipo();
                ?>
                    <tr>
                        <td><strong><?= $tipo->getCetDescripcion(); ?></strong></td>
                        <td><?= $estado->getCeFechaIni(); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="verCompraCliente.php" class="btn btn-secondary mt-3">
        ← Volver
    </a>

</div>

<?php include_once "../estructura/pie.php"; ?>
