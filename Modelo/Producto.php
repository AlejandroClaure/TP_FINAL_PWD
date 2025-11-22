<?php
class Producto extends BaseDatos
{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $proprecio;
    private $procantstock;
    private $prodeshabilitado;
    private $idusuario;
    private $proimagen;

    public function __construct()
    {
        parent::__construct();
        $this->idproducto        = 0;
        $this->pronombre         = "";
        $this->prodetalle        = "";
        $this->proprecio         = 0;
        $this->procantstock      = 0;
        $this->prodeshabilitado  = null;
        $this->idusuario         = 0;
        $this->proimagen         = null;
    }

    public function setear(
        $idproducto,
        $pronombre,
        $prodetalle,
        $proprecio,
        $procantstock,
        $prodeshabilitado,
        $idusuario,
        $proimagen
    ) {
        $this->idproducto        = $idproducto;
        $this->pronombre         = $pronombre;
        $this->prodetalle        = $prodetalle;
        $this->proprecio         = $proprecio;
        $this->procantstock      = $procantstock;
        $this->prodeshabilitado  = $prodeshabilitado;
        $this->idusuario         = $idusuario;
        $this->proimagen         = $proimagen;
    }

    // ==================
    // GETTERS
    // ==================
    public function getIdProducto()
    {
        return $this->idproducto;
    }
    public function getProNombre()
    {
        return $this->pronombre;
    }
    public function getProDetalle()
    {
        return $this->prodetalle;
    }
    public function getProPrecio()
    {
        return $this->proprecio;
    }
    public function getProCantStock()
    {
        return $this->procantstock;
    }
    public function getProDeshabilitado()
    {
        return $this->prodeshabilitado;
    }
    public function getIdUsuario()
    {
        return $this->idusuario;
    }
    public function getProimagen()
    {
        return $this->proimagen;
    }

    // ==================
    // SETTERS
    // ==================
    public function setIdProducto($v)
    {
        $this->idproducto       = $v;
    }
    public function setProNombre($v)
    {
        $this->pronombre        = $v;
    }
    public function setProDetalle($v)
    {
        $this->prodetalle       = $v;
    }
    public function setProPrecio($v)
    {
        $this->proprecio        = $v;
    }
    public function setProCantStock($v)
    {
        $this->procantstock     = $v;
    }
    public function setProDeshabilitado($v)
    {
        $this->prodeshabilitado = $v;
    }
    public function setIdUsuario($v)
    {
        $this->idusuario        = $v;
    }
    public function setProimagen($v)
    {
        $this->proimagen        = $v;
    }

    // ==================
    // CARGAR
    // ==================
    public function cargar()
    {
        $sql = "SELECT * FROM producto WHERE idproducto = {$this->idproducto}";
        $res = $this->Ejecutar($sql);

        if ($res > 0) {
            $row = $this->Registro();
            $this->setear(
                $row['idproducto'],
                $row['pronombre'],
                $row['prodetalle'],
                $row['proprecio'],
                $row['procantstock'],
                $row['prodeshabilitado'],
                $row['idusuario'],
                $row['proimagen']
            );
            return true;
        }
        return false;
    }

    // ==================
    // INSERTAR
    // ==================
    public function insertar()
    {
        $sql = "INSERT INTO producto 
            (pronombre, prodetalle, proprecio, procantstock, prodeshabilitado, idusuario, proimagen)
            VALUES (
                '{$this->pronombre}',
                '{$this->prodetalle}',
                {$this->proprecio},
                {$this->procantstock},
                " . ($this->prodeshabilitado !== null ? "'{$this->prodeshabilitado}'" : "NULL") . ",
                {$this->idusuario},
                " . ($this->proimagen !== null ? "'{$this->proimagen}'" : "NULL") . "
            )";

        $id = $this->Ejecutar($sql);

        if ($id > 0) {
            $this->idproducto = $id;
            return true;
        }
        return false;
    }

    // ==================
    // MODIFICAR
    // ==================
    public function modificar()
    {
        $sql = "UPDATE producto SET
            pronombre       = '{$this->pronombre}',
            prodetalle      = '{$this->prodetalle}',
            proprecio       = {$this->proprecio},
            procantstock    = {$this->procantstock},
            prodeshabilitado = " . ($this->prodeshabilitado !== null ? "'{$this->prodeshabilitado}'" : "NULL") . ",
            proimagen       = " . ($this->proimagen !== null ? "'{$this->proimagen}'" : "NULL") . "
            WHERE idproducto = {$this->idproducto}";

        return $this->Ejecutar($sql) >= 0;
    }

    // ==================
    // DESHABILITAR (BAJA LÓGICA)
    // ==================
    public function deshabilitar()
    {
        $sql = "UPDATE producto SET 
            prodeshabilitado = '" . date('Y-m-d H:i:s') . "'
            WHERE idproducto = {$this->idproducto}";

        return $this->Ejecutar($sql) >= 0;
    }

    // ==================
    // HABILITAR (VOLVER A NULL)
    // ==================
    public function habilitar()
    {
        $sql = "UPDATE producto SET 
            prodeshabilitado = NULL
            WHERE idproducto = {$this->idproducto}";

        return $this->Ejecutar($sql) >= 0;
    }


    // ==================
    // ELIMINAR
    // ==================
    public function eliminar()
    {
        $sql = "DELETE FROM producto WHERE idproducto = {$this->idproducto}";
        return $this->Ejecutar($sql) >= 0;
    }

    // ==================
    // LISTAR
    // ==================
    public function listar($condicion = "")
    {
        $arreglo = [];
        $sql = "SELECT * FROM producto";
        if ($condicion != "") {
            $sql .= " WHERE $condicion";
        }

        $res = $this->Ejecutar($sql);

        if ($res > 0) {
            while ($row = $this->Registro()) {
                $obj = new Producto();
                $obj->setear(
                    $row['idproducto'],
                    $row['pronombre'],
                    $row['prodetalle'],
                    $row['proprecio'],
                    $row['procantstock'],
                    $row['prodeshabilitado'],
                    $row['idusuario'],
                    $row['proimagen']
                );
                $arreglo[] = $obj;
            }
        }
        return $arreglo;
    }

    

}
