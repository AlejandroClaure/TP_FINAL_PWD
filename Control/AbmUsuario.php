<?php

class AbmUsuario
{

    /** CARGAR OBJETO SIN CLAVE (ALTA) */
    private function cargarObjetoSinClave($param)
    {
        $obj = null;

        if (
            isset($param['usnombre']) &&
            isset($param['uspass']) &&
            isset($param['usmail'])
        ) {

            $hash = password_hash($param['uspass'], PASSWORD_DEFAULT);

            $obj = new Usuario();
            $obj->setear(
                null,
                $param['usnombre'],
                $hash,
                $param['usmail'],
                null
            );
        }
        return $obj;
    }

    /** CARGAR OBJETO CON CLAVE (MODIFICAR) */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (
            isset($param['idusuario']) &&
            isset($param['usnombre']) &&
            isset($param['usmail'])
        ) {

            $obj = new Usuario();

            // Si hay contraseña nueva → hash
            $pass = null;
            if (!empty($param['uspass'])) {
                $pass = password_hash($param['uspass'], PASSWORD_DEFAULT);
            } else {
                // Si la contraseña viene vacía, mantenemos la actual
                $obj->setIdUsuario($param['idusuario']);
                $obj->cargar();
                $pass = $obj->getUsPass();
            }

            $obj->setear(
                $param['idusuario'],
                $param['usnombre'],
                $pass,
                $param['usmail'],
                null
            );
        }

        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        return isset($param['idusuario']);
    }

    /** ALTA DESDE PANEL ADMIN */
    public function alta($param)
    {
        $obj = $this->cargarObjetoSinClave($param);

        if ($obj != null && $obj->insertar()) {
            return $obj->getIdUsuario();
        }
        return false;
    }

    /** REGISTRAR DESDE FORMULARIO PÚBLICO */
    public function registrar($param)
    {
        return $this->alta($param);
    }

    /** BAJA */
    public function baja($param)
    {
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {
            $obj = new Usuario();
            $obj->setIdUsuario($param['idusuario']);

            if ($obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /** MODIFICACION */
    public function modificacion($param)
    {
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);

            if ($obj != null && $obj->modificar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /** BUSCAR */
    public function buscar($param)
    {
        $where = " true ";

        if ($param != null) {
            if (isset($param['idusuario']))
                $where .= " AND idusuario = " . (int)$param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " AND usnombre = '" . $param['usnombre'] . "'";
            if (isset($param['usmail']))
                $where .= " AND usmail = '" . $param['usmail'] . "'";
        }

        $obj = new Usuario();
        return $obj->listar($where);
    }


/**
 * Crea un nuevo usuario
 * @param string $nombre   Nombre del usuario
 * @param string $mail     Email
 * @param string $pass     Contraseña en texto plano (se hashea dentro)
 * @return bool            true = creado, false = falló o ya existe
 */
public function crearUsuario($nombre, $mail, $pass)
{
    // Validación básica (nunca está de más)
    if (empty(trim($nombre)) || empty(trim($mail)) || empty($pass)) {
        return false;
    }

    $abm = new AbmUsuario();

    $param = [
        "usnombre"       => trim($nombre),
        "usmail"         => trim($mail),
        "uspass"         => password_hash($pass, PASSWORD_DEFAULT), // ← siempre hashear!
        "usdeshabilitado" => null
    ];

    // alta() devuelve el objeto o false
    return $abm->alta($param) !== false;
}


/**
 * Edita un usuario existente (sin tocar contraseña)
 * @param int    $id       ID del usuario
 * @param string $nombre   Nuevo nombre
 * @param string $mail     Nuevo mail
 * @return bool
 */
public function editarUsuario($id, $nombre, $mail)
{
    if ($id <= 0 || empty(trim($nombre)) || empty(trim($mail))) {
        return false;
    }

    $abm = new AbmUsuario();

    $param = [
        "idusuario" => (int)$id,
        "usnombre"  => trim($nombre),
        "usmail"    => trim($mail)
        // no tocamos uspass
    ];

    return $abm->modificacion($param);
}
}
