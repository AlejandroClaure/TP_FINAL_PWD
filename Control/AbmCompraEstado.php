<?php
class AbmCompraEstado
{

    // Alta: crea un nuevo estado y cierra el anterior
    public function alta($datos)
    {
        // Cerrar estado anterior usando el ABM (correcto)
        $estadoActual = $this->obtenerEstadoActual($datos['idcompra']);
        if ($estadoActual) {
            $estadoActual->setCeFechaFin(date('Y-m-d H:i:s'));
            $estadoActual->modificar();
        }

        $objCompra = new Compra();
        $objCompra->setIdCompra($datos['idcompra']);
        if (!$objCompra->cargar()) return false;

        $objTipo = new CompraEstadoTipo();
        $objTipo->setIdCompraEstadoTipo($datos['idcompraestadotipo']);
        if (!$objTipo->cargar()) return false;

        $nuevo = new CompraEstado();
        $nuevo->setear(
            0,
            $objCompra,
            $objTipo,
            $datos['cefechaini'] ?? date('Y-m-d H:i:s'),
            null
        );

        return $nuevo->insertar();
    }

    // Baja
    public function baja($datos)
    {
        $obj = new CompraEstado();
        $obj->setIdCompraEstado($datos['idcompraestado']);
        return $obj->eliminar();
    }

    // Modificación
    public function modificacion($datos)
    {
        $obj = new CompraEstado();
        $obj->setIdCompraEstado($datos['idcompraestado']);
        $obj->cargar();

        if (isset($datos['cefechafin'])) {
            $obj->setCeFechaFin($datos['cefechafin']);
        }

        return $obj->modificar();
    }

    // Buscar
    public function buscar($param = array())
    {
        $where = "true";

        if (isset($param['idcompra'])) {
            $where .= " AND idcompra = " . $param['idcompra'];
        }

        if (isset($param['activo']) && $param['activo'] === true) {
            $where .= " AND cefechafin IS NULL";
        }

        return (new CompraEstado())->listar($where);
    }

    // Buscar el último estado activo de una compra
    public function buscarUltimoPorCompra($idcompra)
    {
        $estados = $this->buscar(['idcompra' => $idcompra, 'activo' => true]);
        if (!empty($estados)) {
            return $estados[0]; // El último activo
        }
        return null;
    }

    // Control/AbmCompraEstado.php

    public function cambiarEstadoCompra($idCompra, $nuevoEstadoTipo)
    {
        $nuevoEstadoTipo = (int)$nuevoEstadoTipo;
        if (!in_array($nuevoEstadoTipo, [2, 3, 4, 5])) return false;

        // Cerrar estado actual
        $estadoActual = $this->buscar([
            'idcompra' => $idCompra,
            'cefechafin' => null
        ]);

        if (!empty($estadoActual)) {
            $estado = $estadoActual[0];
            $estado->setCeFechaFin(date('Y-m-d H:i:s'));
            $estado->modificar();
        }

        // CREAR NUEVO ESTADO
        $datos = [
            'idcompra' => $idCompra,
            'idcompraestadotipo' => $nuevoEstadoTipo,
            'cefechaini' => date('Y-m-d H:i:s'),
            'cefechafin' => null
        ];

        $this->alta($datos);

        // SI EL ESTADO NUEVO ES 2 (ACEPTADA) → RESTAR STOCK
        if ($nuevoEstadoTipo == 2) {
            $this->procesarStockPorAceptacion($idCompra);
        }

         if ($nuevoEstadoTipo == 4) { 
        // Cancelada → devolver stock
        $this->revertirStockPorCancelacion($idCompra);
    }

        return true;
    }




    public function obtenerEstadoActual($idCompra)
    {
        $estados = $this->buscar([
            'idcompra' => $idCompra,
            'cefechafin' => null
        ]);
        return !empty($estados) ? $estados[0] : null;
    }

    /**
     * Procesa la reducción de stock cuando una compra pasa a estado "Aceptada".
     */
    public function procesarStockPorAceptacion($idCompra)
    {
        include_once $GLOBALS['CONTROL_PATH'] . 'AbmCompraItem.php';
        include_once $GLOBALS['CONTROL_PATH'] . 'AbmProducto.php';

        $abmItem = new AbmCompraItem();
        $abmProducto = new AbmProducto();

        // Obtener todos los productos de la compra
        $items = $abmItem->buscar(['idcompra' => $idCompra]);

        foreach ($items as $item) {

            $producto = $item->getObjProducto();
            $producto->cargar(); // aseguramos datos actualizados

            $cantidad = $item->getCiCantidad();
            $stockActual = $producto->getProCantStock();

            $nuevoStock = max(0, $stockActual - $cantidad);

            // Actualizar el stock usando el ABM de producto
            $abmProducto->modificar([
                'idproducto'   => $producto->getIdProducto(),
                'pronombre'    => $producto->getProNombre(),
                'prodetalle'   => $producto->getProDetalle(),
                'proprecio'    => $producto->getProPrecio(),
                'procantstock' => $nuevoStock,
                'prooferta'    => $producto->getProOferta(),
                'profinoffer'  => $producto->getProFinOffer(),
                'idusuario'    => $producto->getIdUsuario(),
                'proimagen'    => $producto->getProImagen()
            ]);
        }

        return true;
    }

    /**
 * Revierte el stock cuando una compra pasa a estado Cancelada.
 */
public function revertirStockPorCancelacion($idCompra)
{
    // SOLO devolver stock si la compra fue aceptada antes
    if (!$this->compraFueAceptada($idCompra)) {
        return false;
    }

    include_once $GLOBALS['CONTROL_PATH'] . 'AbmCompraItem.php';
    include_once $GLOBALS['CONTROL_PATH'] . 'AbmProducto.php';

    $abmItem = new AbmCompraItem();
    $abmProducto = new AbmProducto();

    $items = $abmItem->buscar(['idcompra' => $idCompra]);

    foreach ($items as $item) {

        $producto = $item->getObjProducto();
        $producto->cargar();

        $cantidad = $item->getCiCantidad();
        $stockActual = $producto->getProCantStock();

        $nuevoStock = $stockActual + $cantidad;

        $abmProducto->modificar([
            'idproducto'   => $producto->getIdProducto(),
            'pronombre'    => $producto->getProNombre(),
            'prodetalle'   => $producto->getProDetalle(),
            'proprecio'    => $producto->getProPrecio(),
            'procantstock' => $nuevoStock,
            'prooferta'    => $producto->getProOferta(),
            'profinoffer'  => $producto->getProFinOffer(),
            'idusuario'    => $producto->getIdUsuario(),
            'proimagen'    => $producto->getProImagen()
        ]);
    }

    return true;
}


/**
 * Retorna true si la compra tuvo alguna vez estado ACEPTADA (2)
 */
public function compraFueAceptada($idCompra)
{
    $lista = $this->buscar(['idcompra' => $idCompra]);

    foreach ($lista as $estado) {
        if ($estado->getObjCompraEstadoTipo()->getIdCompraEstadoTipo() == 2) {
            return true;
        }
    }

    return false;
}


}
