<?php
class Compraitem extends BaseDatos{

    //ver los diferentes estados de la compra y sus posibles contextos de cambio 
    //hacer la extensión con la BD

    private $idcompraitem;
    private $objproducto;
    private $objcompra;
    private $cicantidad;
    private $mensajeoperacion;

    public function __construct(){
        parent:: __construct();
        $this->idcompraitem="";
        $this->objproducto="";
        $this->objcompra="";
        $this->cicantidad="";
        $this->mensajeoperacion="";
    }

    public function setear($idcompraitem,$newObjProducto,$newObjCompra,$cicantidad){
        $this->setID($idcompraitem);
        $this->setObjproducto($newObjProducto);
        $this->setObjcompra($newObjCompra);
        $this->setCicantidad($cicantidad);
    }

    public function setearSinID($newObjProducto,$newObjCompra,$cicantidad){
        $this->setObjproducto($newObjProducto);
        $this->setObjcompra($newObjCompra);
        $this->setCicantidad($cicantidad);
    }

    
    public function getID(){
        return $this->idcompraitem;
    }

    public function setID($idcompraitem){
        $this->idcompraitem = $idcompraitem;
    }
 
    public function getObjProducto(){
        return $this->objproducto;
    }

    public function setObjProducto($newObjProducto){
        $this->objproducto = $newObjProducto;
    }

    public function getObjCompra(){
        return $this->objcompra;
    }

    public function setObjCompra($newObjCompra){
        $this->objcompra = $newObjCompra;
    }

    public function getCiCantidad(){
        return $this->cicantidad;
    }
 
    public function setCiCantidad($cicantidad){
        $this->cicantidad = $cicantidad;
    }

    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    
    public function setMensajeOperacion($newMensajeOperacion){
        $this->mensajeoperacion=$newMensajeOperacion;
        return $this;
    }

    public function cargar(){
        $resp = false;
       
        $sql="SELECT * FROM compraitem WHERE idcompraitem = ".$this->getID();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $objproducto= new producto();
                    $objcompra= new compra();

                    $objproducto->setID($row['idproducto']);
                    $objcompra->setID($row['idcompra']);

                    $objproducto->cargar();
                    $objcompra->cargar();

                    $this->setear($row['idcompraitem'], $objproducto, $objcompra,$row['cicantidad']);
                }
            }
        } else {
            $this->setMensajeOperacion("compraitem->listar: ".$this->getError());
        }
        return $resp;
    }
    
    public function insertar(){
        //Fecha ini poner fecha actual
        //Setear fecha fin cuando el admin apruebe la compra (fecha)
        $resp = false;
       
        // Si lleva ID Autoincrement, la consulta SQL no lleva dicho ID
        $sql="INSERT INTO compraitem(idproducto, idcompra, cicantidad) 
            VALUES('"
            .$this->getObjProducto()->getID()."', '"
            .$this->getObjCompra()->getID()."', '"
            .$this->getCicantidad()."'
        );";
        if ($this->Iniciar()) {
            if ($esteid = $this->Ejecutar($sql)) {
                // Si se usa ID autoincrement, descomentar lo siguiente:
                $this->setId($esteid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("compraitem->insertar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraitem->insertar: ".$this->getError());
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
    
        $sql="UPDATE compraitem 
        SET idproducto='".$this->getObjProducto()->getID()
        ."', idcompra='".$this->getObjCompra()->getID()
        ."', cicantidad='".$this->getCiCantidad()
        ."' WHERE idcompraitem='".$this->getID()."'";
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("compraitem->modificar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraitem->modificar: ".$this->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
       
        $sql="DELETE FROM compraitem WHERE idcompraitem=".$this->getID();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("compraitem->eliminar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("compraitem->eliminar: ".$this->getError());
        }
        return $resp;
    }
    
    public function listar($parametro=""){
        $arreglo = array();
       
        $sql="SELECT * FROM compraitem ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $this->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $this->Registro()){
                    $obj= new compraItem();
                    $objCompra = new compra();
                    $objProducto= new producto();

                    $objCompra->setID($row['idcompra']);
                    $objProducto->setID($row['idproducto']);

                    $objCompra->cargar();
                    $objProducto->cargar();

                    $obj->setear($row['idcompraitem'], $objProducto, 
                    $objCompra, $row['cicantidad']);

                    array_push($arreglo, $obj);
                }
            }
        } else {
            $this->setMensajeOperacion("compraitem->listar: ".$this->getError());
        }
    
        return $arreglo;
    }

}


?>