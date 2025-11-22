<?php
// ====== MODAL: DETALLE ======
?>
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="accion/accionEDetalleProducto.php" method="POST">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Modificar Detalle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="detalleId" name="idproducto">

                    <label>Detalle del producto</label>
                    <textarea id="detalleTexto" name="prodetalle" class="form-control" rows="4" required></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
// ====== MODAL: PRECIO ======
?>
<div class="modal fade" id="modalPrecio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="accion/accionEditarPrecioProducto.php" method="POST">

                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Modificar Precio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="precioId" name="idproducto">

                    <label>Nuevo Precio</label>
                    <input type="number" step="0.01" id="precioValor" name="proprecio" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-info">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
// ====== MODAL: OFERTA ======
?>
<div class="modal fade" id="modalOferta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="accion/accionEditarOfertaProducto.php" method="POST">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Administrar Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="ofertaId" name="idproducto">

                    <div class="mb-3">
                        <label>Descuento (%)</label>
                        <input type="number" id="ofertaValor" name="prooferta" class="form-control" min="0" max="90" required>
                    </div>

                    <div class="mb-3">
                        <label>Fecha de fin de oferta</label>
                        <input type="date" id="ofertaFecha" name="profinoffer" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>
