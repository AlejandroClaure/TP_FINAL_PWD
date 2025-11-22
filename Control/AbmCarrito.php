<?php

class AbmCarrito
{
    private $abmCompra;
    private $abmCompraItem;
    private $abmProducto;

    public function __construct()
    {
        $this->abmCompra     = new AbmCompra();
        $this->abmCompraItem = new AbmCompraItem();
        $this->abmProducto   = new AbmProducto();
    }


    /** CREA LA COMPRA COMPLETA DESDE EL CARRITO **/
    public function procesarCompra($idUsuario, $carrito)
    {
        if (empty($carrito)) {
            return ["error" => "El carrito está vacío"];
        }

        // 1) Crear COMPRA
        $datosCompra = [
            "cofecha"   => date("Y-m-d H:i:s"),
            "idusuario" => $idUsuario
        ];

        if (!$this->abmCompra->alta($datosCompra)) {
            return ["error" => "No se pudo crear la compra"];
        }

        // Última compra creada
        $compraCreada = $this->abmCompra->buscar(["idusuario" => $idUsuario]);
        $compra = end($compraCreada);
        $idCompra = $compra->getIdCompra();


        // 2) Agregar estado inicial a la compra
        $abmCompraEstado = new AbmCompraEstado();
        $abmCompraEstado->alta([
            "idcompra"           => $idCompra,
            "idcompraestadotipo" => 1,          // estado inicial
            "cefechaini"         => date("Y-m-d H:i:s"),
            "cefechafin"         => null
        ]);


        // 3) Cargar items dentro de CompraItem
        foreach ($carrito as $item) {

            $this->abmCompraItem->alta([
                "idproducto" => $item["idproducto"],
                "idcompra"   => $idCompra,
                "cicantidad" => $item["cantidad"]
            ]);

            // 4) Descontar stock
            $producto = $this->abmProducto->buscarPorId($item["idproducto"]);
            $nuevoStock = $producto->getProCantStock() - $item["cantidad"];

            $this->abmProducto->modificacion([
                "idproducto"   => $item["idproducto"],
                "pronombre"    => $producto->getProNombre(),
                "prodetalle"   => $producto->getProDetalle(),
                "proprecio"    => $producto->getProPrecio(),
                "procantstock" => $nuevoStock,
                "proimagen"    => $producto->getProImagen()
            ]);
        }

        // 5) Vaciar carrito
        unset($_SESSION["carrito"]);

        return ["ok" => true, "idcompra" => $idCompra];
    }
}
