<?php

class PedidoProducto
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    public function crearItemPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidoproducto (id_pedido, id_producto, cantidad, estado) VALUES (:id_pedido, :id_producto, :cantidad, :estado)");
        $this->monto = 0;
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
    /*
     $consulta->bindValue(':tiempo_estimado_min', $this->tiempo_estimado_min, PDO::PARAM_INT);
        $consulta->bindValue(':id_empleado_responsable', $this->id_empleado_responsable, PDO::PARAM_INT);
*/
    public static function cargarLista($lista, $idPedido)
    {
        $arrayDeIds = [];
        foreach ($lista as $value) {
            $auxPedidoItem = new PedidoProducto();
            $auxPedidoItem->id_pedido = $idPedido;
            $auxPedidoItem->id_producto = $value['id_producto'];
            $auxPedidoItem->cantidad = $value['cantidad'];
            $auxPedidoItem->estado = EEPedido::PENDIENTE;
            array_push($arrayDeIds, $auxPedidoItem->crearItemPedido());
        }
        if (count($arrayDeIds) == count($lista)) {
            return true;
        } else {
            return false;
        }
    }



    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidoproducto");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    // public static function obtenerPedidoPornombre($nombre)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE nombre = :nombre");
    //     $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Pedido');
    // }
    // public static function obtenerPedidoPorId($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE id = :id");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_STR);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Pedido');
    // }
}
