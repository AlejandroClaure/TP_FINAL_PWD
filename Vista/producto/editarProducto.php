<?php
require_once "../../configuracion.php";

$session = new Session();

// Seguridad: solo admin
if (!$session->activa()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "login/login.php");
    exit;
}
if (!$session->esAdmin()) {
    header("Location: " . $GLOBALS['VISTA_URL'] . "error/noAutorizado.php");
    exit;
}

$usuario = $session->getUsuario();

// Obtener ID del producto
$idproducto = $_GET['id'] ?? null;
if (!$idproducto || !is_numeric($idproducto)) {
    die("ID de producto inválido.");
}

$abmProducto = new AbmProducto();
$producto = $abmProducto->buscarPorId($idproducto);  // ← Usa tu método existente

if (!$producto || !$producto->getIdProducto()) {
    die("Producto no encontrado.");
}

include_once "../estructura/cabecera.php";
?>

<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">
                        Editar Producto #<?= $producto->getIdProducto() ?>
                    </h2>
                </div>
                <div class="card-body p-4">

                    <!-- Mensajes -->
                    <?php if (isset($_GET['msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($_GET['msg']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_GET['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="accion/accionActualizarProducto.php" method="post" class="row g-4">
                        <input type="hidden" name="idproducto" value="<?= $producto->getIdProducto() ?>">
                        <input type="hidden" name="proimagen" value="<?= htmlspecialchars($producto->getProimagen() ?? '') ?>">
                        <input type="hidden" name="idusuario" value="<?= $usuario->getIdUsuario() ?>">

                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nombre del producto</label>
                            <input type="text" name="pronombre" class="form-control form-control-lg"
                                value="<?= htmlspecialchars($producto->getProNombre()) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Precio ($)</label>
                            <input type="number" step="0.01" name="proprecio" class="form-control form-control-lg"
                                value="<?= $producto->getProPrecio() ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Detalle / Descripción</label>
                            <textarea name="prodetalle" class="form-control" rows="5" required>
                            <?= htmlspecialchars(trim($producto->getProDetalle())) ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Stock actual</label>
                            <input type="number" name="procantstock" class="form-control form-control-lg"
                                value="<?= $producto->getProCantStock() ?>" min="0" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Oferta (%)</label>
                            <input type="number" name="prooferta" class="form-control form-control-lg"
                                value="<?= $producto->getProOferta() ?>" min="0" max="99">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Visibilidad</label>
                            <div class="pt-2">
                                <?php $deshabilitado = $producto->getProDeshabilitado() !== null && $producto->getProDeshabilitado() !== '0000-00-00 00:00:00'; ?>
                                <a href="accion/accionToggleVisibilidadProducto.php?id=<?= $producto->getIdProducto() ?>"
                                    class="btn <?= $deshabilitado ? 'btn-outline-success' : 'btn-outline-danger' ?> btn-sm px-4"
                                    title="<?= $deshabilitado ? 'Habilitar producto' : 'Deshabilitar producto' ?>">
                                    <i class="fa <?= $deshabilitado ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                                    <?= $deshabilitado ? 'Mostrar' : 'Ocultar' ?>
                                </a>
                                <div class="mt-2">
                                    <?php if ($deshabilitado): ?>
                                        <span class="badge bg-danger">Oculto del catálogo</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Visible</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                Guardar todos los cambios
                            </button>
                        </div>
                    </form>
                    <div class="col-12 text-center mt-5">
                        <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                            <i class="fa fa-trash"></i> Eliminar Producto
                        </button>
                    </div>

                    <!-- Modal Confirmación -->
                    <div class="modal fade" id="modalEliminar" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">¿Eliminar producto?</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que querés <strong>deshabilitar</strong> este producto?</p>
                                    <p class="text-muted small">Se ocultará del catálogo pero no se borrará permanentemente.</p>
                                </div>
                                <div class="modal-footer">
                                    <form action="accion/accionEliminarProducto.php" method="post">
                                        <input type="hidden" name="idproducto" value="<?= $producto->getIdProducto() ?>">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="../menus/gestionMenus.php" class="btn btn-secondary btn-lg">
                            Volver al menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "../estructura/pie.php"; ?>