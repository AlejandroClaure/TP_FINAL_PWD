<?php

class AbmRol {

    public function alta($param) {
        $resp = false;
        $objRol = new Rol();

        // idrol = null → autoincremental
        $objRol->setear(null, $param['rodescripcion']);

        if ($objRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param) {
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {
            $objRol = new Rol();
            $objRol->setIdRol($param['idrol']);

            if ($objRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param) {
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {
            $objRol = new Rol();
            $objRol->setear($param['idrol'], $param['rodescripcion']);

            if ($objRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param) {
        $where = " true ";

        if ($param != null) {
            if (isset($param['idrol'])) 
                $where .= " AND idrol = " . $param['idrol'];

            if (isset($param['rodescripcion'])) 
                $where .= " AND rodescripcion LIKE '%" . $param['rodescripcion'] . "%'";
        }

        $objRol = new Rol();
        return $objRol->listar($where);
    }

   
    public function listar() {
        $objRol = new Rol();
        return $objRol->listar();
    }

    private function seteadosCamposClaves($params) {
        return isset($params['idrol']);
    }
}
