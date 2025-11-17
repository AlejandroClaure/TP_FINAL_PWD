<?php
class AbmProducto {

    public function crear($param) {
        $obj = new Producto();
        $obj->setear(
            0,
            $param['pronombre'],
            $param['prodetalle'],
            $param['procantstock'],
            null,
            $param['idusuario']
        );
        return $obj->insertar();
    }

    public function modificar($param) {
        $obj = new Producto();
        $obj->setear(
            $param['idproducto'],
            $param['pronombre'],
            $param['prodetalle'],
            $param['procantstock'],
            null,
            $param['idusuario']
        );
        return $obj->modificar();
    }

    public function eliminar($id) {
        $obj = new Producto();
        $obj->setear($id, "", "", "", null, "");
        return $obj->eliminar();
    }

    public function listarPorUsuario($idusuario) {
        $obj = new Producto();
        return $obj->listar("idusuario = $idusuario");
    }

    public function buscarPorId($id) {
        $obj = new Producto();
        $obj->setear($id, "", "", "", null, "");
        $obj->cargar();
        return $obj;
    }

    public function listar() {
        $obj = new Producto();
        return $obj->listar();
    }
}
?>
