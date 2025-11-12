<?php

class Compraestadotipo extends BaseDatos
{
    //ver los diferentes estados de la compra y sus posibles contextos de cambio
    //hacer la extensión con la BD

    private $idcompraestadotipo;
    private $cetdescripcion; 
    private $cetdetalle;
    private $mensajeoperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idcompraestadotipo="";
        $this->cetdescripcion="";
        $this->cetdetalle="";
        $this->mensajeoperacion="";
    }

    public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle)
    {
        $this->setID($idcompraestadotipo);
        $this->setCetDescripcion($cetdescripcion);
        $this->setCetDetalle($cetdetalle);
    }

    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;

    }

    public function getCetDetalle(){
        return $this->cetdetalle;
    }

    public function setCetDetalle($cetdetalle){
        $this->cetdetalle = $cetdetalle;

    }

    public function getCetDescripcion(){
        return $this->cetdescripcion;
    }

   
    public function setCetDescripcion($cetdescripcion){
        $this->cetdescripcion = $cetdescripcion;

    }

    public function getID(){
        return $this->idcompraestadotipo;
    }

    public function setID($idcompraestadotipo){
        $this->idcompraestadotipo = $idcompraestadotipo;

    }
    
    public function cargar()
    {
        $resp = false;
        
        $sql="SELECT * FROM compraestadotipo WHERE idcompraestadotipo = ".$this->getID();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if ($res>-1) {
                if ($res>0) {
                    $row = $this->Registro();
                    
                    $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                }
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->listar: ".$this->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        //Fecha ini poner fecha actual
        //Setear fecha fin cuando el admin apruebe la compra (fecha)
        $resp = false;
        
        // Si lleva ID Autoincrement, la consulta SQL no lleva dicho ID
        $sql="INSERT INTO compraestadotipo(idcompraestadotipo, cetdescripcion, cetdetalle) 
            VALUES('"
            .$this->getID()."', '"
            .$this->getCetdescripcion()."', '"
            .$this->getCetdetalle()."'
        );";
        if ($this->Iniciar()) {
            if ($esteid = $this->Ejecutar($sql)) {
                // Si se usa ID autoincrement, descomentar lo siguiente:
                //$this->setID($esteid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("compraestadotipo->insertar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->insertar: ".$this->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        
        $sql="UPDATE compraestadotipo 
        SET cetdescripcion='".$this->getCetDescripcion()
        ."', cetdetalle='".$this->getCetDetalle()
        ."' WHERE idcompraestadotipo='".$this->getID()."'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("compraestadotipo->modificar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->modificar: ".$this->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        
        $sql="DELETE FROM compraestadotipo WHERE idcompraestadotipo=".$this->getID();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("compraestadotipo->eliminar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->eliminar: ".$this->getError());
        }
        return $resp;
    }

    public function listar($parametro="")
    {
        $arreglo = array();
        
        $sql="SELECT * FROM compraestadotipo ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $this->Ejecutar($sql);
        if ($res>-1) {
            if ($res>0) {
                while ($row = $this->Registro()) {
                    $obj= new compraEstadoTipo();
                    
                    $obj->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->listar: ".$this->getError());
        }

        return $arreglo;
    }

    
}
?>