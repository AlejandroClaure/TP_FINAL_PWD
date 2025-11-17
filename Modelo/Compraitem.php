<?php

class CompraItem extends BaseDatos {

    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idcompraitem = 0;
        $this->objProducto = new Producto();
        $this->objCompra = new Compra();
        $this->cicantidad = 0;
        $this->mensajeoperacion = "";
    }

    /* =============================
       SETEAR COMPLETO
       ============================= */
    public function setear($idcompraitem, $objProducto, $objCompra, $cicantidad) {
        $this->idcompraitem = $idcompraitem;
        $this->objProducto = $objProducto;
        $this->objCompra = $objCompra;
        $this->cicantidad = $cicantidad;
    }

    /* =============================
       GETTERS & SETTERS
       ============================= */

    public function getIdCompraItem() {
        return $this->idcompraitem;
    }
    public function setIdCompraItem($id) {
        $this->idcompraitem = $id;
    }

    public function getObjProducto() {
        return $this->objProducto;
    }
    public function setObjProducto($obj) {
        $this->objProducto = $obj;
    }

    public function getObjCompra() {
        return $this->objCompra;
    }
    public function setObjCompra($obj) {
        $this->objCompra = $obj;
    }

    public function getCiCantidad() {
        return $this->cicantidad;
    }
    public function setCiCantidad($cant) {
        $this->cicantidad = $cant;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($msg) {
        $this->mensajeoperacion = $msg;
    }

    /* =============================
       CARGAR
       ============================= */
    public function cargar() {

        $sql = "SELECT * FROM compraitem WHERE idcompraitem = " . $this->idcompraitem;
        $res = $this->Ejecutar($sql);

        if ($res > 0) {

            $row = $this->Registro();

            $producto = new Producto();
            $producto->setIdProducto($row['idproducto']);
            $producto->cargar();

            $compra = new Compra();
            $compra->setIdCompra($row['idcompra']);
            $compra->cargar();

            $this->setear(
                $row['idcompraitem'],
                $producto,
                $compra,
                $row['cicantidad']
            );

            return true;
        }

        return false;
    }

    /* =============================
       INSERTAR
       ============================= */
    public function insertar() {

        $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad)
                VALUES (
                    {$this->objProducto->getIdProducto()},
                    {$this->objCompra->getIdCompra()},
                    {$this->cicantidad}
                );";

        $id = $this->Ejecutar($sql);

        if ($id > 0) {
            $this->idcompraitem = $id;
            return true;
        }

        return false;
    }

    /* =============================
       MODIFICAR (FALTABA)
       ============================= */
    public function modificar() {

        $sql = "UPDATE compraitem SET
                    idproducto = {$this->objProducto->getIdProducto()},
                    idcompra   = {$this->objCompra->getIdCompra()},
                    cicantidad = {$this->cicantidad}
                WHERE idcompraitem = {$this->idcompraitem}";

        return $this->Ejecutar($sql) >= 0;
    }

    /* =============================
       ELIMINAR
       ============================= */
    public function eliminar() {
        $sql = "DELETE FROM compraitem WHERE idcompraitem = {$this->idcompraitem}";
        return $this->Ejecutar($sql) >= 0;
    }

    /* =============================
       LISTAR
       ============================= */
    public function listar($condicion = "") {

        $arreglo = [];
        $sql = "SELECT * FROM compraitem";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        $res = $this->Ejecutar($sql);

        if ($res > 0) {
            while ($row = $this->Registro()) {

                $obj = new CompraItem();

                $producto = new Producto();
                $producto->setIdProducto($row['idproducto']);
                $producto->cargar();

                $compra = new Compra();
                $compra->setIdCompra($row['idcompra']);
                $compra->cargar();

                $obj->setear(
                    $row['idcompraitem'],
                    $producto,
                    $compra,
                    $row['cicantidad']
                );

                $arreglo[] = $obj;
            }
        }

        return $arreglo;
    }
}
?>
