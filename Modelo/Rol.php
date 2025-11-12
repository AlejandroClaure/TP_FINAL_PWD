<?php

class Rol extends BaseDatos{

    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;

    public function __construct(){
        parent::__construct();
        $this->idrol = "";
        $this->rodescripcion = "";
    }

    public function setear($idrol, $rodescripcion){
        $this->setIdRol($idrol);
        $this->setRoDescripcion($rodescripcion);
    }

    public function getIdRol(){  
        return $this->idrol;
    }
    public function setIdRol($idrol){     
        $this->idrol = $idrol;    
    }
    public function getRoDescripcion(){      
        return $this->rodescripcion;     
    }
    public function setRoDescripcion($rodescripcion){  
        $this->rodescripcion = $rodescripcion;    
    }
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor){
        $this->mensajeoperacion = $valor;
    }
   
    public function cargar(){
        $resp = false;
        $sql="SELECT * FROM rol WHERE idrol = ".$this->getIdRol();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $this->setear($row['idrol'], $row['rodescripcion']);
                    
                }
            }
        } else {
            $this->setMensajeOperacion("Especies->listar: ".$this->getError());
        }
        return $resp;
    
        
    }
    
    public function insertar(){
        $resp = false;
        $sql="INSERT INTO rol(idrol,rodescripcion)  VALUES('".$this->getIdRol()."','".$this->getRoDescripcion()."');";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdRol($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Especie->insertar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Especie->insertar: ".$this->getError());
        }
        return $resp;
    }
    
    
    public function modificar(){
        $resp = false;
        $sql="UPDATE rol SET rodescripcion='".$this->getRoDescripcion()."' ".
            " WHERE idrol=".$this->getIdRol();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Especie->modificar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Especie->modificar: ".$this->getError());
        }
        return $resp;
    }



    public function eliminar(){
        $resp = false;
        $sql="DELETE FROM rol WHERE idrol=".$this->getIdRol();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Especie->eliminar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Especie->eliminar: ".$this->getError());
        }
        return $resp;
    }
    
    public function listar($parametro=""){
        $arreglo = array();
        $sql="SELECT * FROM rol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            //echo $sql;
        $res = $this->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $this->Registro()){
                    $obj= new Rol();
                    $obj->setIdRol($row['idrol']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        }
        else {
           $this->setMensajeOperacion("Especie->listar: ".$this->getError());
        }
        }
        return $arreglo;
    }
}
?>