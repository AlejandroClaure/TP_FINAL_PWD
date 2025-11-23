<?php
include_once '../../configuracion.php';

$session = new Session();

if (!$session->activa() || !$session->tieneRol('cliente')) {
    header("Location: ../login/login.php");
    exit;
}

$idUsuario = $session->getUsuario()->getIdusuario();

$abmCompra = new AbmCompra();
$compras = $abmCompra->buscar(['idusuario' => $idUsuario]);

include_once "../estructura/cabecera.php";
?>

<div class="container mt-5">

    <h2 class="mb-4">
        🛒 Mis Compras
    </h2>

    <?php if (empty($compras)): ?>
        <div class="alert alert-info">
            Todavía no realizaste ninguna compra.
        </div>
    <?php else: ?>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Compra</th>
                        <th>Fecha</th>
                        <th>Estado Actual</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($compras as $compra): ?>
                        <tr>
                            <td><?= $compra->getIdcompra(); ?></td>
                            <td><?= $compra->getCoFecha(); ?></td>
                            <td>
                                <span class="badge bg-primary">
                                    <?= $compra->getEstadoActualDescripcion(); ?>
                                </span>
                            </td>
                            <td>
                                $<?= number_format($compra->getTotal(), 2, ',', '.'); ?>
                            </td>
                            <td>
                                <a href="detalleCompra.php?id=<?= $compra->getIdcompra(); ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

    <a href="../login/panelCliente.php" class="btn btn-secondary mt-3">
        ← Volver al panel
    </a>

</div>

<?php include_once "../estructura/pie.php"; ?>
