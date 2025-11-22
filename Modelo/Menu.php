<?php

class Menu extends BaseDatos
{

    private $idmenu;
    private $menombre;
    private $melink;
    private $objmenupadre;
    private $medescripcion;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idmenu = null;
        $this->menombre = "";
        $this->melink = "";
        $this->objmenupadre = null;
        $this->medescripcion = "";
        $this->medeshabilitado = null;
        $this->mensajeoperacion = "";
    }

    public function setear($idmenu, $menombre, $melink, $objpadre, $medescripcion, $medeshabilitado)
    {
        $this->idmenu = $idmenu;
        $this->menombre = $menombre;
        $this->melink = $melink;
        $this->objmenupadre = $objpadre;
        $this->medescripcion = $medescripcion;
        $this->medeshabilitado = $medeshabilitado;
    }

    // ======== GETTERS & SETTERS ========

    public function getIdMenu()
    {
        return $this->idmenu;
    }
    public function setIdMenu($id)
    {
        $this->idmenu = $id;
    }

    public function getMeNombre()
    {
        return $this->menombre;
    }
    public function setMeNombre($n)
    {
        $this->menombre = $n;
    }

    public function getMeLink()
    {
        return $this->melink;
    }
    public function setMeLink($l)
    {
        $this->melink = $l;
    }

    public function getObjMenuPadre()
    {
        return $this->objmenupadre;
    }
    public function setObjMenuPadre($o)
    {
        $this->objmenupadre = $o;
    }

    public function getMeDescripcion()
    {
        return $this->medescripcion;
    }
    public function setMeDescripcion($d)
    {
        $this->medescripcion = $d;
    }

    public function getMeDeshabilitado()
    {
        return $this->medeshabilitado;
    }
    public function setMeDeshabilitado($d)
    {
        $this->medeshabilitado = $d;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($m)
    {
        $this->mensajeoperacion = $m;
    }


    // ======== CARGAR ========

    public function cargar()
    {

        $resp = false;
        $sql = "SELECT * FROM menu WHERE idmenu = " . $this->idmenu;

        if ($this->Iniciar()) {

            $res = $this->Ejecutar($sql);
            if ($res > 0) {

                $row = $this->Registro();

                $padreObj = null;
                if (!empty($row['idpadre'])) {
                    $padreObj = new Menu();
                    $padreObj->setIdMenu($row['idpadre']);
                    $padreObj->cargar();
                }

                $this->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['melink'],
                    $padreObj,
                    $row['medescripcion'],
                    $row['medeshabilitado']
                );

                $resp = true;
            }
        } else {
            $this->mensajeoperacion = "menu->cargar: " . $this->getError();
        }

        return $resp;
    }


    // ======== INSERTAR ========

    public function insertar() {
    $idpadre = (!empty($this->objmenupadre))
        ? (is_object($this->objmenupadre) ? $this->objmenupadre->getIdMenu() : intval($this->objmenupadre))
        : "NULL";

    $melink = !empty($this->melink) ? "'{$this->melink}'" : "NULL";
    $deshab = !empty($this->medeshabilitado) ? "'{$this->medeshabilitado}'" : "NULL";
    $descripcion = $this->medescripcion ?? "";

    $sql = "INSERT INTO menu (menombre, melink, idpadre, medescripcion, medeshabilitado)
            VALUES ('{$this->menombre}', {$melink}, {$idpadre}, '{$descripcion}', {$deshab})";

    if ($this->Iniciar()) {
        $id = $this->Ejecutar($sql);
        if ($id) {
            $this->idmenu = $id;
            return true;
        } else {
            $this->mensajeoperacion = "menu->insertar: " . $this->getError();
            error_log($this->mensajeoperacion); // log para depuración
        }
    } else {
        $this->mensajeoperacion = "menu->insertar: no se pudo iniciar BD";
        error_log($this->mensajeoperacion);
    }

    return false;
}






    //======== MODIFICAR ========

    public function modificar()
    {

        $idpadre = "NULL";

        if (!empty($this->objmenupadre)) {

            if (!is_object($this->objmenupadre)) {
                $padreObj = new Menu();
                $padreObj->setIdMenu(intval($this->objmenupadre));
                $padreObj->cargar();
                $this->objmenupadre = $padreObj;
            }

            $idpadre = $this->objmenupadre->getIdMenu();
        }

        $menombre = addslashes($this->menombre);
        $melink = addslashes($this->melink);
        $medescripcion = addslashes($this->medescripcion);

        $sql = "
            UPDATE menu SET
                menombre = '$menombre',
                melink = '$melink',
                idpadre = $idpadre,
                medescripcion = '$medescripcion',
                medeshabilitado = " . (empty($this->medeshabilitado) ? "NULL" : "'{$this->medeshabilitado}'") . "
            WHERE idmenu = {$this->idmenu}
        ";

        if ($this->Iniciar()) {
            return $this->Ejecutar($sql) !== false;
        }

        return false;
    }


    // ======== ELIMINAR ========

    public function eliminar()
    {
        $sql = "DELETE FROM menu WHERE idmenu = {$this->idmenu}";

        if ($this->Iniciar()) {
            return $this->Ejecutar($sql) !== false;
        }

        return false;
    }


    // ======== LISTAR ========

    public static function listar($param = "")
    {

        $arreglo = [];
        $base = new BaseDatos();

        $sql = "SELECT * FROM menu";
        if (!empty($param)) {
            $sql .= " WHERE $param";
        }

        if ($base->Iniciar() && ($res = $base->Ejecutar($sql)) > 0) {

            while ($row = $base->Registro()) {

                $obj = new Menu();

                $padreObj = null;
                if (!empty($row['idpadre'])) {
                    $padreObj = new Menu();
                    $padreObj->setIdMenu($row['idpadre']);
                    $padreObj->cargar();
                }

                $obj->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['melink'],
                    $padreObj,
                    $row['medescripcion'],
                    $row['medeshabilitado']
                );

                $arreglo[] = $obj;
            }
        }

        return $arreglo;
    }
}
