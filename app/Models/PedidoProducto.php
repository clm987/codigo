<?php

class PedidoProducto
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    public static function GenerarHora()
    {
        return $auxFecha = date("G:i:s");
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

    public static function obtenerItemsPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT p.nombre, pp.cantidad
            from producto as p, pedido as pe, pedidoproducto as pp
            WHERE (pe.id = pp.id_pedido) and (p.id = pp.Id_producto) and (p.sector = :sector)"
        );
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function modificarEstadoPedido($idPedido, $idProducto, $idEmpleado, $tiempoEstimado, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        if ($estado != 3) {
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidoproducto SET id_empleado_responsable = :idEmpleado, tiempo_estimado_min = :tiempoEstimado, inicio_preparacion = :hora_inicio, estado = :estado WHERE (id_pedido = :id AND id_producto = :id_producto)");
            $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
            $consulta->bindValue(':hora_inicio', self::GenerarHora(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
            $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':id_producto', $idProducto, PDO::PARAM_INT);
            return $consulta->execute();
        } else {
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidoproducto SET id_empleado_responsable = :idEmpleado, tiempo_estimado_min = :tiempoEstimado, fin_preparacion = :hora_fin, estado = :estado WHERE (id_pedido = :id AND id_producto = :id_producto)");
            $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
            $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
            $consulta->bindValue(':hora_fin', self::GenerarHora(), PDO::PARAM_STR);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
            $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':id_producto', $idProducto, PDO::PARAM_INT);
            return $consulta->execute();
        }
    }

    public static function obtenerTiempoMaximo($numero_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT MAX(pp.tiempo_estimado_min) as 'Tiempo en minutos'
            FROM `pedido` as p, `pedidoproducto` as pp 
            WHERE pp.id_pedido = p.id AND (p.codigo_pedido = :numero_pedido)"
        );
        $consulta->bindValue(':numero_pedido', $numero_pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }

    public static function obtenerItemsPorPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT p.precio as 'Precio', pp.cantidad as 'Cantidad'
            FROM producto as p, pedido as pe, pedidoproducto as pp
            WHERE (pe.id = pp.id_pedido) and (p.id = pp.Id_producto) and pe.id = :id_pedido"
        );
        $consulta->bindValue(':id_pedido', $pedido->id, PDO::PARAM_STR);
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
    /*
             $consulta->bindValue(':tiempo_estimado_min', $this->tiempo_estimado_min, PDO::PARAM_INT);
                $consulta->bindValue(':id_empleado_responsable', $this->id_empleado_responsable, PDO::PARAM_INT);
        */
}
