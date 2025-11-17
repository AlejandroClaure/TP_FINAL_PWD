<?php

class CompraEstado extends BaseDatos {

    private $idcompraestado;
    private $objcompra;
    private $objtipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idcompraestado = 0;
        $this->objcompra = new Compra();
        $this->objtipo = new CompraEstadoTipo();
        $this->cefechaini = null;
        $this->cefechafin = null;
        $this->mensajeoperacion = "";
    }

    public function setear($idcompraestado, $objcompra, $objtipo, $ini, $fin) {
        $this->idcompraestado = $idcompraestado;
        $this->objcompra = $objcompra;
        $this->objtipo = $objtipo;
        $this->cefechaini = $ini;
        $this->cefechafin = $fin;
    }

    // GETTERS / SETTERS

    public function getIdCompraEstado() { return $this->idcompraestado; }
    public function getObjCompra() { return $this->objcompra; }
    public function getObjTipo() { return $this->objtipo; }
    public function getFechaIni() { return $this->cefechaini; }
    public function getFechaFin() { return $this->cefechafin; }

    public function setIdCompraEstado($v) { $this->idcompraestado = $v; }
    public function setObjCompra($v) { $this->objcompra = $v; }
    public function setObjTipo($v) { $this->objtipo = $v; }
    public function setFechaIni($v) { $this->cefechaini = $v; }
    public function setFechaFin($v) { $this->cefechafin = $v; }

    // ===============================
    // CARGAR
    // ===============================
    public function cargar() {

        $sql = "SELECT * FROM compraestado WHERE idcompraestado = " . $this->getIdCompraEstado();

        if ($this->Iniciar()) {

            $res = $this->Ejecutar($sql);

            if ($res > 0) {
                $row = $this->Registro();

                $compra = new Compra();
                $compra->setIdCompra($row['idcompra']);
                $compra->cargar();

                $tipo = new CompraEstadoTipo();
                $tipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                $tipo->cargar();

                $this->setear(
                    $row['idcompraestado'],
                    $compra,
                    $tipo,
                    $row['cefechaini'],
                    $row['cefechafin']
                );

                return true;
            }
        }

        return false;
    }

    // ===============================
    // INSERTAR
    // ===============================
    public function insertar() {
        $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo)
                VALUES (" .
                $this->getObjCompra()->getIdCompra() . ", " .
                $this->getObjTipo()->getIdCompraEstadoTipo() . "
                );";

        if ($this->Iniciar()) {
            $id = $this->Ejecutar($sql);

            if ($id > 0) {
                $this->setIdCompraEstado($id);
                return true;
            }
        }
        return false;
    }

    // ===============================
    // LISTAR
    // ===============================
    public function listar($cond = "") {

        $array = [];
        $sql = "SELECT * FROM compraestado ";

        if ($cond != "") $sql .= " WHERE " . $cond;

        $sql .= " ORDER BY cefechaini DESC";

        $res = $this->Ejecutar($sql);

        if ($res > 0) {
            while ($row = $this->Registro()) {

                $compra = new Compra();
                $compra->setIdCompra($row['idcompra']);
                $compra->cargar();

                $tipo = new CompraEstadoTipo();
                $tipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                $tipo->cargar();

                $obj = new CompraEstado();
                $obj->setear(
                    $row['idcompraestado'],
                    $compra,
                    $tipo,
                    $row['cefechaini'],
                    $row['cefechafin']
                );

                $array[] = $obj;
            }
        }

        return $array;
    }

}

?>
