<?php

class Compra extends BaseDatos{

    private $idcompra;
    private $cofecha; //TIMESTAMP
    private $objusuario;
    private $mensajeoperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idcompra = "";
        $this->cofecha = date('Y-m-d H:i:s');
        $this->objusuario = "";
        $this->mensajeoperacion = "";
    }

    public function setear($idcompra, $cofecha, $objusuario)
    {
        $this->setID($idcompra);
        $this->setCoFecha($cofecha);
        $this->setObjUsuario($objusuario);
    }

    public function setearSinID($cofecha, $objusuario){
        $this->setCoFecha($cofecha);
        $this->setObjUsuario($objusuario);
    }

    public function getID(){
        return $this->idcompra;
    }

    public function setID($idcompra){
        $this->idcompra = $idcompra;
    }

    public function getCofecha(){
        return $this->cofecha;
    }
    
    public function setCofecha($cofecha){
        $this->cofecha = $cofecha;
    }

    public function getObjUsuario(){
        return $this->objusuario;
    }
    
    public function setObjUsuario($newObjetoUsuario){
        $this->objusuario = $newObjetoUsuario;
    }

    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }
   

    public function cargar(){
        $resp = false;
        $sql = "SELECT * FROM compra WHERE idcompra = " . $this->getID();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $this->Registro();

                    $objUsuario = new usuario();
                    $objUsuario->setID($row['idusuario']);
                    $objUsuario->cargar();

                    $this->setear($row['idcompra'], $row['cofecha'], $objUsuario);
                }
            }
        } else {
            $this->setMensajeOperacion("compra->listar: " . $this->getError());
        }
        return $resp;
    }

    public function insertar(){
        
        $resp = false;
        
        $sql = "INSERT INTO compra(cofecha, idusuario) 
            VALUES('"
            . $this->getCofecha() . "', '"
            . $this->getObjUsuario()->getID() . "'
        );";
        if ($this->Iniciar()) {
            if ($esteid = $this->Ejecutar($sql)) {
                
                $this->setID($esteid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("compra->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("compra->insertar: " . $this->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $sql = "UPDATE compra 
        SET cofecha='" . $this->getCofecha()
            . "', idusuario='" . $this->getObjUsuario()->getID()
            . "' WHERE idcompra='" . $this->getID() . "'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("compra->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("compra->modificar: " . $this->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $sql = "DELETE FROM compra WHERE idcompra=" . $this->getID();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("compra->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("compra->eliminar: " . $this->getError());
        }
        return $resp;
    }

    public function listar($parametro = ""){
        $arreglo = array();
        $sql = "SELECT * FROM compra ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $this->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $this->Registro()) {
                    $obj = new compra();

                    $objUsuario = new usuario();
                    $objUsuario->setID($row['idusuario']);
                    $objUsuario->cargar();

                    $obj->setear($row['idcompra'], $row['cofecha'], $objUsuario);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            $this->setMensajeOperacion("compra->listar: " . $this->getError());
        }

        return $arreglo;
    }

}
?>