<?php

class AbmUsuario {

    /**
     * REGISTRAR USUARIO
     */
    public function registrar($param) {
    $obj = new Usuario();

    // Hashear contraseña
    $hash = password_hash($param["uspass"], PASSWORD_DEFAULT);

    $obj->setear(0, $param["usnombre"], $hash, $param["usmail"], null);

    return $obj->insertar();
}


    /**
     * BUSCAR USUARIOS
     */
    public function buscar($param) {

        $obj = new Usuario();
        $where = " true ";

        if (isset($param['idusuario'])) {
            $where .= " AND idusuario = " . $param['idusuario'];
        }
        if (isset($param['usnombre'])) {
            $where .= " AND usnombre = '" . $param['usnombre'] . "'";
        }
        if (isset($param['usmail'])) {
            $where .= " AND usmail = '" . $param['usmail'] . "'";
        }
        if (isset($param['uspass'])) {
            $where .= " AND uspass = '" . $param['uspass'] . "'";
        }

        return $obj->listar($where);
    }
}
