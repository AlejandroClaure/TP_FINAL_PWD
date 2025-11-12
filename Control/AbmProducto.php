<?php

class ABMproducto{

 /**
     * Crea un objeto Producto a partir de los datos recibidos (array asociativo)
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (
            array_key_exists('idproducto', $param) &&
            array_key_exists('pronombre', $param) &&
            array_key_exists('prodetalle', $param) &&
            array_key_exists('procantstock', $param)
        ) {
            $obj = new Producto();
            $obj->setear(
                $param['idproducto'],
                $param['pronombre'],
                $param['prodetalle'],
                $param['procantstock']
            );
        }
        return $obj;
    }

    /**
     * Verifica si está la clave primaria para poder eliminar o buscar
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idproducto'])) {
            $obj = new Producto();
            $obj->setIdproducto($param['idproducto']);
        }
        return $obj;
    }

    /**
     * Verifica los campos clave
     */
    private function seteadosCamposClaves($param) {
        return isset($param['idproducto']);
    }

    /**
     * Alta de un producto
     */
    public function alta($param) {
        $resp = false;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Baja de un producto
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null && $obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Modificación de un producto
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null && $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Buscar productos (devuelve arreglo de objetos)
     */
    public function buscar($param) {
        $where = "true";
        if ($param != null) {
            if (isset($param['idproducto']))
                $where .= " AND idproducto = " . $param['idproducto'];
            if (isset($param['pronombre']))
                $where .= " AND pronombre = '" . $param['pronombre'] . "'";
            if (isset($param['prodetalle']))
                $where .= " AND prodetalle LIKE '%" . $param['prodetalle'] . "%'";
            if (isset($param['procantstock']))
                $where .= " AND procantstock = " . $param['procantstock'];
        }

        $obj = new Producto();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
?>