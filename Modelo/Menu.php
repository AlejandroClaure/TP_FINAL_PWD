<?php

class Menu extends BaseDatos{

    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $objmenupadre;
    private $medeshabilitado;
    private $mensajeoperacion;


    public function __construct(){
        parent :: __construct();
        $this->idmenu="";
        $this->menombre="";
        $this->medescripcion="";
        $this->objmenupadre= null;
        $this->medeshabilitado=null;
        $this->mensajeoperacion ="";
    }

    public function setear($idmenu, $menombre, $medescripcion, $newobjpadre, $medeshabilitado) {
        $this->setIdmenu($idmenu);
        $this->setMeNombre($menombre);
        $this->setMeDescripcion($medescripcion);
        $this->setObjMenuPadre($newobjpadre);
        $this->setMeDeshabilitado($medeshabilitado);
    }

    public function setearSinID( $menombre, $medescripcion, $newobjpadre, $medeshabilitado) {
        $this->setMeNombre($menombre);
        $this->setMeDescripcion($medescripcion);
        $this->setObjMenuPadre($newobjpadre);
        $this->setMeDeshabilitado($medeshabilitado);
    }

    public function setearSinPadre($menombre, $medescripcion){
        $objPadre = new menu();
        $this->setMeNombre($menombre);
        $this->setMeDescripcion($medescripcion);
        $this->setObjMenuPadre($objPadre);
    }

    public function getIdMenu(){
        return $this->idmenu;
    }
    public function setIdMenu($idmenu){
        $this->idmenu = $idmenu;
    }
    public function getMeNombre(){
        return $this->menombre;
    }
    public function setMeNombre($menombre){
        $this->menombre = $menombre;
    }
    public function getMeDescripcion(){
        return $this->medescripcion;
    }
    public function setMeDescripcion($medescripcion){
        $this->medescripcion = $medescripcion;
    }
    public function getObjMenuPadre(){
        return $this->objmenupadre;
    }
    public function setObjMenuPadre($newObjMenuPadre){
        $this->objmenupadre = $newObjMenuPadre;
    }
    public function getMeDeshabilitado(){
        return $this->medeshabilitado;
    }
    public function setMeDeshabilitado($medeshabilitado){
        $this->medeshabilitado = $medeshabilitado;
    }
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }
    
    
    public function cargar(){
    $resp = false;   
    $sql="SELECT * FROM menu WHERE idmenu = '".$this->getIdMenu()."'";
    if ($this->Iniciar()) {
        $res = $this->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                $row = $this->Registro();
                $padre = new Menu();
                $padre->setIdMenu($row['idpadre']);//ver
                $padre->cargar();

                $this->setear($row['idmenu'], $row['menombre'], $row['medescripcion'], $padre, $row['medeshabilitado']);
            }
        }
    } else {
        $this->setMensajeOperacion("menu->listar: ".$this->getError());
    }
    return $resp;
}

public function insertar(){
    $resp = false;

    $idpadre = $this->getObjMenuPadre() != "" ? "'".$this->getObjMenuPadre()->getIdMenu()."'" : 'NULL';
    $deshabilitado= $this->getMeDeshabilitado() != '' ? $this->getMeDeshabilitado() : 'NULL';
      
    // Si lleva ID Autoincrement, la consulta SQL no lleva id. Y viceversa:
    $sql="INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado)
        VALUES('"
        .$this->getMeNombre()."', '"
        .$this->getMeDescripcion()."', "
        .$idpadre.", "
        .$deshabilitado.");";
        
    if ($this->Iniciar()) {
        if ($this->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeOperacion("menu->insertar: ".$this->getError());
        }
    } else {
        $this->setMensajeOperacion("menu->insertar: ".$this->getError());
    }
    return $resp;
}


public function modificarDescripcion(){
    $resp = false;
   
    $sql="UPDATE menu SET medescripcion='".$this->getMeDescripcion()."' WHERE idmenu='".$this->getIdMenu()."'";
    
    if ($this->Iniciar()) {
        if ($this->Ejecutar($sql)) {
            $resp = true;
         
        } else {
            $this->setMensajeOperacion("menu->modificar: ".$this->getError());
        }
    } else {
        $this->setMensajeOperacion("menu->modificar: ".$this->getError());
    }
    return $resp;
}

public function modificar(){
    $idPadre = $this->getObjMenuPadre() != null ? "'".$this->getObjMenuPadre()->getIdMenu()."'" : 'NULL';
    
    $deshabilitado= $this->getMeDeshabilitado() != '' ? $this->getMeDeshabilitado() : 'NULL';
      
    $resp = false;
   
    $sql="UPDATE menu SET menombre='"
    .$this->getMeNombre()."', medescripcion='"
    .$this->getMeDescripcion()."', medeshabilitado='"
    .$deshabilitado."', idpadre="
    .$idPadre 
    ." WHERE idmenu='".$this->getIdMenu()."'";

    
    if ($this->Iniciar()) {
        if ($this->Ejecutar($sql)) {
            $resp = true;
         
        } else {
            $this->setMensajeOperacion("menu->modificar: ".$this->getError());
        }
    } else {
        $this->setMensajeOperacion("menu->modificar: ".$this->getError());
    }
    return $resp;
}

public function eliminar(){
    $resp = false;
   
    $sql="DELETE FROM menu WHERE idmenu=".$this->getIdMenu()."";
    if ($this->Iniciar()) {
        if ($this->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeOperacion("menu->eliminar: ".$this->getError());
        }
    } else {
        $this->setMensajeOperacion("menu->eliminar: ".$this->getError());
    }
    return $resp;
}

public function listar($parametro=""){
    $arreglo = array();
    $sql="SELECT * FROM menu ";
    if ($parametro!="") {
        $sql.= "WHERE ".$parametro;
    }
    $res = $this->Ejecutar($sql);
    if($res>-1){
        if($res>0){
            while ($row = $this->Registro()){
                $obj= new menu();
                $padre= new menu();
                
                $padre->setIdMenu($row['idpadre']);
                $padre->cargar();

                $obj->setear($row['idmenu'], $row['menombre'], $row['medescripcion'], $padre, $row['medeshabilitado']);
                array_push($arreglo, $obj);
            }
        }
    } else {
        $this->setMensajeOperacion("menu->listar: ".$this->getError());
    }
    

    return $arreglo;
}

}

?>