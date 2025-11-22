<?php

class AbmMenu {

  
    public function alta($param) {

    $objMenu = new Menu();

    // Normalizar valores
    $idpadre = empty($param['idpadre']) ? null : $param['idpadre'];
    $medeshabilitado = isset($param['medeshabilitado']) ? $param['medeshabilitado'] : 0;
    $melink = isset($param['melink']) ? $param['melink'] : '';

    $objMenu->setear(
        null,
        $param['menombre'],
        $melink,
        $idpadre,
        $param['medescripcion'] ?? '',
        $medeshabilitado
    );

    return $objMenu->insertar();
}


   
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {

            $objMenu = new Menu();
            $objMenu->setIdMenu($param['idmenu']);   

            if ($objMenu->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    
    public function modificacion($param) {

    if (!$this->seteadosCamposClaves($param)) return false;

    $objMenu = new Menu();

    $objMenu->setear(
        $param['idmenu'],
        $param['menombre'],
        $param['melink'],
        $param['idpadre'] ?? null,
        $param['medescripcion'] ?? '',
        $param['medeshabilitado'] ?? null
    );

    return $objMenu->modificar();
}

    public function buscar($param) {

    $where = " true ";

    if ($param != null) {

        if (isset($param['idmenu'])) {
            $where .= " AND idmenu = " . intval($param['idmenu']);
        }

        if (isset($param['menombre'])) {
            $where .= " AND menombre LIKE '%" . addslashes($param['menombre']) . "%'";
        }

        if (isset($param['melink'])) {
            $where .= " AND melink LIKE '%" . addslashes($param['melink']) . "%'";
        }

        if (isset($param['idpadre'])) {
            $where .= " AND idpadre = " . intval($param['idpadre']);
        }

        if (isset($param['medeshabilitado'])) {
            $where .= " AND medeshabilitado = '" . $param['medeshabilitado'] . "'";
        }
    }

    return Menu::listar($where);
}

    /* Obtiene los menús accesibles por un usuario */
 public function obtenerMenuPorRoles($roles) {

    // $roles puede venir como ["admin", "cliente"] o como array de objetos Rol
    $idsRoles = [];

    foreach ($roles as $r) {
        if (is_object($r) && method_exists($r, "getIdRol")) {
            $idsRoles[] = $r->getIdRol();
        } else {
            // Si es string, obtenemos ID de la BD
            $objRol = new Rol();
            $rolesEncontrados = $objRol->listar("rodescripcion = '" . $r . "'");
            if (!empty($rolesEncontrados)) {
                $idsRoles[] = $rolesEncontrados[0]->getIdRol();
            }
        }
    }

    if (empty($idsRoles)) {
        return [];
    }

    // Convertir a lista separada por comas
    $ids = implode(",", $idsRoles);

    $sql = "
        idmenu IN (
            SELECT idmenu 
            FROM menurol 
            WHERE idrol IN ($ids)
        )
        AND (medeshabilitado IS NULL OR medeshabilitado = '0000-00-00 00:00:00')
    ";

    return Menu::listar($sql);
}



    /* Verifica existencia de clave primaria */
    private function seteadosCamposClaves($params) {
        return isset($params['idmenu']);
    }
}
