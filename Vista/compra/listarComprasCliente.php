<?php
include_once '../../configuracion.php';
include_once $GLOBALS['CONTROL_PATH'] . 'Session.php';
include_once $GLOBALS['CONTROL_PATH'] . 'AbmCompra.php';
include_once $GLOBALS['CONTROL_PATH'] . 'AbmCompraEstado.php';

$session = new Session();
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php");
    exit;
}

$usuario = $session->getUsuario();
$abmCompra = new AbmCompra();

// Buscar todas las compras del usuario
$compras = $abmCompra->buscar(['idusuario' => $usuario->getIdUsuario()]);

include_once '../estructura/cabecera.php';
?>

<div class="container mt-5">
    <h2 class="mb-4"><i class="fa fa-shopping-bag"></i> Mis Compras</h2>

    <?php if (empty($compras)) : ?>
        <div class="alert alert-info">Todavía no realizaste ninguna compra.</div>
    <?php else : ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra) : 
                    $estadoActual = (new AbmCompraEstado())->obtenerEstadoActual($compra->getIdCompra());
                    $estado = $estadoActual ? $estadoActual->getObjCompraEstadoTipo()->getCeTDescripcion() : 'desconocido';

                    // Badge según estado
                    $badgeClass = [
                        'iniciada'  => 'secondary',
                        'aceptada'  => 'primary',
                        'enviada'   => 'info',
                        'cancelada' => 'danger'
                    ][$estado] ?? 'dark';
                ?>
                    <tr>
                        <td><?= $compra->getIdCompra() ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($compra->getCoFecha())) ?></td>
                        <td><span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($estado) ?></span></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary"
                               href="verCompraCliente.php?id=<?= $compra->getIdCompra(); ?>">
                                Ver detalle
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>

<?php include_once '../estructura/pie.php'; ?>
