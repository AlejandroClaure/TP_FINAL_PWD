<?php

class Producto{
    
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;  
    private $mensajeoperacion;

    public function __construct(){
        $this->idproducto="";
        $this->pronombre="";
        $this->prodetalle="";
        $this->procantstock="";
       
        $this->mensajeoperacion ="";
    }

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock){
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        
    }

    public function getIdproducto(){
        return $this->idproducto;
    }
    public function setIdproducto($valor){
        $this->idproducto = $valor;
    }
    public function getPronombre(){
        return $this->pronombre;
    }
    public function setPronombre($valor){
        $this->pronombre = $valor;
    }
    public function getProdetalle(){
        return $this->prodetalle;
    }
    public function setProdetalle($valor){
        $this->prodetalle = $valor;
    }
    public function getProcantstock(){
        return $this->procantstock;
    }
    public function setProcantstock($valor){
        $this->procantstock = $valor;
    }
    
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;
    }

    public function cargar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto WHERE idproducto = " . $this->getIdproducto();

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                $row = $base->Registro();
                $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']);
                $resp = true;
            }
        } else {
            $this->setMensajeOperacion("Producto->cargar: ".$base->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();

        $sql = "INSERT INTO producto (idproducto, pronombre, prodetalle, procantstock)
                VALUES ('{$this->getIdproducto()}', '{$this->getPronombre()}',
                        '{$this->getProdetalle()}', '{$this->getProcantstock()}')";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->insertar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();

        $sql = "UPDATE producto SET 
                    pronombre='{$this->getPronombre()}',
                    prodetalle='{$this->getProdetalle()}',
                    procantstock='{$this->getProcantstock()}'
                WHERE idproducto='{$this->getIdproducto()}'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->modificar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM producto WHERE idproducto = " . $this->getIdproducto();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("Producto->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public function listar($condicion = "") {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto";
        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        $res = $base->Ejecutar($sql);
        if ($res > 0) {
            while ($row = $base->Registro()) {
                $obj = new Producto();
                $obj->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']);
                array_push($arreglo, $obj);
            }
        } else {
            $this->setMensajeOperacion("Producto->listar: ".$base->getError());
        }
        return $arreglo;
    }

}

?>