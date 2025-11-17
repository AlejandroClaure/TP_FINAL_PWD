<?php

class CompraEstadoTipo extends BaseDatos
{
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeoperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idcompraestadotipo = 0;
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
        $this->mensajeoperacion = "";
    }

    // =========================================
    //               SETEAR
    // =========================================
    public function setear($id, $descripcion, $detalle)
    {
        $this->idcompraestadotipo = $id;
        $this->cetdescripcion = $descripcion;
        $this->cetdetalle = $detalle;
    }

    // =========================================
    //               GETTERS
    // =========================================

    public function getIdCompraEstadoTipo()
    {
        return $this->idcompraestadotipo;
    }

    public function getCetDescripcion()
    {
        return $this->cetdescripcion;
    }

    public function getCetDetalle()
    {
        return $this->cetdetalle;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }

    // =========================================
    //               SETTERS
    // =========================================

    public function setIdCompraEstadoTipo($id)
    {
        $this->idcompraestadotipo = $id;
    }

    public function setCetDescripcion($descripcion)
    {
        $this->cetdescripcion = $descripcion;
    }

    public function setCetDetalle($detalle)
    {
        $this->cetdetalle = $detalle;
    }

    public function setMensajeOperacion($valor)
    {
        $this->mensajeoperacion = $valor;
    }

    // =========================================
    //               CRUD
    // =========================================

    public function cargar()
    {
        $resp = false;
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $this->idcompraestadotipo;

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);

            if ($res > 0) {
                $row = $this->Registro();
                $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                $resp = true;
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->cargar: " . $this->getError());
        }

        return $resp;
    }

    public function insertar()
    {
        $resp = false;

        $sql = "INSERT INTO compraestadotipo (idcompraestadotipo, cetdescripcion, cetdetalle)
                VALUES (
                    {$this->idcompraestadotipo},
                    '{$this->cetdescripcion}',
                    '{$this->cetdetalle}'
                )";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $this->getError());
        }

        return $resp;
    }

    public function modificar()
    {
        $resp = false;

        $sql = "UPDATE compraestadotipo SET 
                cetdescripcion = '{$this->cetdescripcion}', 
                cetdetalle = '{$this->cetdetalle}'
                WHERE idcompraestadotipo = {$this->idcompraestadotipo}";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->modificar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->modificar: " . $this->getError());
        }

        return $resp;
    }

    public function eliminar()
    {
        $resp = false;

        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo = {$this->idcompraestadotipo}";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->eliminar: " . $this->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->eliminar: " . $this->getError());
        }

        return $resp;
    }

    public function listar($condicion = "")
    {
        $arreglo = [];
        $sql = "SELECT * FROM compraestadotipo";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);

            if ($res > 0) {
                while ($row = $this->Registro()) {
                    $obj = new CompraEstadoTipo();
                    $obj->setear(
                        $row['idcompraestadotipo'],
                        $row['cetdescripcion'],
                        $row['cetdetalle']
                    );
                    $arreglo[] = $obj;
                }
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->listar: " . $this->getError());
        }

        return $arreglo;
    }
}
?>
