<?php

class Venta {

    private $db;

    public function __construct() {
        $this->db = new BaseDatos();
    }

    /**
     * Crea una nueva compra para un usuario
     */
    public function nuevaCompra($idusuario) {
        $sql = "INSERT INTO compra (idusuario) VALUES ($idusuario)";
        return $this->db->Ejecutar($sql);
    }

    /**
     * Inserta los productos en compraitem
     */
    public function agregarItem($idcompra, $idproducto, $cantidad) {
        $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad)
                VALUES ($idproducto, $idcompra, $cantidad)";
        return $this->db->Ejecutar($sql);
    }

    /**
     * Registra el estado de la compra
     * 1 = iniciada
     * 2 = aceptada
     * 3 = enviada
     * 4 = cancelada
     */
    public function setEstado($idcompra, $idestado) {
        // Primero cierro el estado anterior
        $sql = "UPDATE compraestado SET cefechafin = NOW() 
                WHERE idcompra = $idcompra AND cefechafin IS NULL";
        $this->db->Ejecutar($sql);

        // Creo el nuevo estado
        $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo)
                VALUES ($idcompra, $idestado)";
        return $this->db->Ejecutar($sql);
    }

    /**
     * Obtiene datos generales de la compra
     */
    public function getCompra($idcompra) {
        $sql = "SELECT c.idcompra, c.cofecha, u.usnombre, u.usmail
                FROM compra c
                JOIN usuario u ON c.idusuario = u.idusuario
                WHERE c.idcompra = $idcompra";

        if ($this->db->Ejecutar($sql) > 0) {
            return $this->db->Registro();
        }
        return null;
    }

    /**
     * Devuelve los items de una compra
     */
    public function getItems($idcompra) {
        $sql = "SELECT p.pronombre, p.prodetalle, ci.cicantidad
                FROM compraitem ci
                JOIN producto p ON ci.idproducto = p.idproducto
                WHERE ci.idcompra = $idcompra";

        $items = [];

        if ($this->db->Ejecutar($sql) > 0) {
            while ($row = $this->db->Registro()) {
                $items[] = $row;
            }
        }
        return $items;
    }
}
