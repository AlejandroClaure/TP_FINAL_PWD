<?php

class Compra extends BaseDatos {

    private $idcompra;
    private $cofecha;        // TIMESTAMP
    private $objusuario;     // objeto Usuario
    private $mensajeoperacion;

    public function __construct() {
        parent::__construct();
        $this->idcompra = 0;
        $this->cofecha = date('Y-m-d H:i:s');
        $this->objusuario = new Usuario();   // SIEMPRE UN OBJETO
        $this->mensajeoperacion = "";
    }

    // =============================
    // SETEAR OBJETO COMPLETO
    // =============================
    public function setear($idcompra, $cofecha, $objusuario) {
        $this->idcompra = $idcompra;
        $this->cofecha = $cofecha;
        $this->objusuario = $objusuario;
    }

    public function setearSinID($cofecha, $objusuario) {
        $this->cofecha = $cofecha;
        $this->objusuario = $objusuario;
    }

    // =============================
    // GETTERS / SETTERS
    // =============================
    public function getIdCompra() {
        return $this->idcompra;
    }

    public function setIdCompra($idcompra) {
        $this->idcompra = $idcompra;
    }

    public function getCoFecha() {
        return $this->cofecha;
    }

    public function setCoFecha($cofecha) {
        $this->cofecha = $cofecha;
    }

    public function getObjUsuario() {
        return $this->objusuario;
    }

    public function setObjUsuario($objusuario) {
        $this->objusuario = $objusuario;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    // =============================
    // CARGAR
    // =============================
    public function cargar() {
        $resp = false;
        $sql = "SELECT * FROM compra WHERE idcompra = " . $this->getIdCompra();

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);

            if ($res > 0) {
                $row = $this->Registro();

                $objUsuario = new Usuario();
                $objUsuario->setIdUsuario($row['idusuario']);
                $objUsuario->cargar();

                $this->setear($row['idcompra'], $row['cofecha'], $objUsuario);

                $resp = true; // ESTO FALTABA
            }
        } else {
            $this->setMensajeOperacion("Compra->cargar: " . $this->getError());
        }

        return $resp;
    }

    // =============================
    // INSERTAR
    // =============================
    public function insertar() {

        $resp = false;

        $sql = "INSERT INTO compra (cofecha, idusuario) VALUES (
                    '" . $this->getCoFecha() . "',
                    " . $this->getObjUsuario()->getIdUsuario() . "
                );";

        if ($this->Iniciar()) {
            if ($id = $this->Ejecutar($sql)) {

                $this->setIdCompra($id);
                $resp = true;

            } else {
                $this->setMensajeOperacion("Compra->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->insertar: " . $this->getError());
        }

        return $resp;
    }

    // =============================
    // MODIFICAR
    // =============================
    public function modificar() {

        $resp = false;

        $sql = "UPDATE compra SET 
                    cofecha = '" . $this->getCoFecha() . "',
                    idusuario = " . $this->getObjUsuario()->getIdUsuario() . "
                WHERE idcompra = " . $this->getIdCompra();

        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Compra->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("Compra->modificar: " . $this->getError());
        }

        return $resp;
    }

    // =============================
    // ELIMINAR
    // =============================
    public function eliminar() {

        $resp = false;

        $sql = "DELETE FROM compra WHERE idcompra = " . $this->getIdCompra();

        if ($this->Iniciar()) {

            if ($this->Ejecutar($sql)) return true;

            $this->setMensajeOperacion("Compra->eliminar: " . $this->getError());

        } else {
            $this->setMensajeOperacion("Compra->eliminar: " . $this->getError());
        }

        return $resp;
    }

    // =============================
    // LISTAR
    // =============================
    public function listar($parametro = "") {

        $arreglo = [];
        $sql = "SELECT * FROM compra";

        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }

        $res = $this->Ejecutar($sql);

        if ($res > 0) {
            while ($row = $this->Registro()) {

                $obj = new Compra();

                $objUsuario = new Usuario();
                $objUsuario->setIdUsuario($row['idusuario']);
                $objUsuario->cargar();

                $obj->setear(
                    $row['idcompra'],
                    $row['cofecha'],
                    $objUsuario
                );

                $arreglo[] = $obj;
            }
        }

        return $arreglo;
    }

}
?>
