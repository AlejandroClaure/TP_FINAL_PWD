<?php

class Usuario extends BaseDatos{

    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $mensajeoperacion;

    public function __construct(){
        parent::__construct();
        $this->idusuario="";
        $this->usnombre="";
        $this->uspass="";
        $this->usmail="";
        $this->usdeshabilitado=0;
        $this->mensajeoperacion="";

    }

    public function setear($idusuario,$usnombre,$uspass,$usmail,$usdeshabilitado){

        $this->setIdUsuario($idusuario);
        $this->setUsNombre($usnombre);
        $this->setUsPass($uspass);
        $this->setUsMail($usmail);
        $this->setUsDeshabilitado($usdeshabilitado);
    }

    //setter y getter

    public function getIdUsuario(){  
        return $this->idusuario;
    }
    public function setIdUsuario($idusuario){ 
            $this->idusuario = $idusuario;    
    }
    public function getUsNombre(){      
        return $this->usnombre;     
    }
    public function setUsNombre($usnombre){  
        $this->usnombre = $usnombre;    
    }
    public function getUsPass(){  
        return $this->uspass;
    }
    public function setUsPass($uspass){   
          $this->uspass = $uspass;    
    }
    public function getUsMail(){     
        return $this->usmail;     
    }
    public function setUsMail($usmail){  
        $this->usmail = $usmail;    
    }
    public function getUsDeshabilitado(){  
        return $this->usdeshabilitado;  
    }
    public function setUsDeshabilitado($usdeshabilitado){
          $this->usdeshabilitado = $usdeshabilitado;    
    }
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor){
        $this->mensajeoperacion = $valor;
    }
    
    public function cargar(){
        $resp = false;
        $sql = "SELECT * FROM usuario WHERE idusuario = ".$this->getIdUsuario();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $this->setear($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
                    
                }
            }
        } else {
            $this->setMensajeOperacion("Usuarios->listar: ".$this->getError());
        }
        return $resp;
    
        
    }

    public function insertar(){
        $resp = false;
        $sql = "INSERT INTO usuario(usnombre,uspass,usmail,usdeshabilitado)  VALUES('".$this->getUsNombre()."','".$this->getUsPass()."','".$this->getUsMail()."',".$this->getUsDeshabilitado().");";
        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdUsuario($elid);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Usuario->insertar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Usuario->insertar: ".$this->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $sql = "UPDATE usuario SET usnombre='".$this->getUsNombre()."' ,uspass='".$this->getUsPass()."',usmail='".$this->getUsMail()."' ,usdeshabilitado='".$this->getUsDeshabilitado()."' ".
            " WHERE idusuario=".$this->getIdUsuario();
        if ($this->Iniciar()) {
            echo $sql;
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Usuario->modificar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Usuario->modificar: ".$this->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $sql = "DELETE FROM usuario WHERE idusuario=".$this->getIdUsuario();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Usuario->eliminar: ".$this->getError());
            }
        } else {
            $this->setMensajeOperacion("Usuario->eliminar: ".$this->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $sql = "SELECT * FROM usuario ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            echo $sql;
        $res = $this->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $this->Registro()){
                    $obj= new Usuario();
                    $obj->setIdUsuario($row['idusuario']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
               
            }
            
        }
        else {
           $this->setMensajeOperacion("Usuario->listar: ".$this->getError());
        }
        }
        return $arreglo;
    }
    
    

}
?>