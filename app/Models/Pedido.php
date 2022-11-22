<?php
define("RUTA_BASE", "C:/UTN/3TercerCuatrimestre/Programacion 3/Reciente/TrabajoPractico/codigo/app/ImagenesPedidos/");


class Pedido
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    public static function GenerarCodigoPedido()
    {
        $length = 2;
        $letrasRandom = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
        return $auxCodigo  = $letrasRandom . Rand(100, 999);
    }

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (id_mozo, id_mesa, codigo_pedido, estado, nombre_cliente, monto, fecha_alta) VALUES (:id_mozo, :id_mesa, :codigo_pedido, :estado, :nombre_cliente, :monto, :fecha_alta)");
        $this->codigo_pedido = self::GenerarCodigoPedido();
        $this->monto = 0;
        $consulta->bindValue(':id_mozo', $this->id_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_pedido', $this->codigo_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', self::GenerarFecha(), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function guardarFotoPedido($codigo_mesa, $codigo_pedido)
    {
        $_partes = explode(".", $_FILES["foto"]["name"]);
        $_extensionArchivo = $_partes[1];
        $_FILES["foto"]["name"] = $codigo_mesa . $codigo_pedido . "." . $_extensionArchivo;
        $path = RUTA_BASE;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            move_uploaded_file($_FILES["foto"]["tmp_name"], RUTA_BASE . rand(1, 1000) . $_FILES["foto"]["name"]);
        } else {
            move_uploaded_file($_FILES["foto"]["tmp_name"], RUTA_BASE . rand(1, 1000) . $_FILES["foto"]["name"]);
        }
    }

    public static function obtenerPedidoPorCodigo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE codigo_pedido = :codigo_pedido");
        $consulta->bindValue(':codigo_pedido', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }



    // public static function obtenerPedidoPorId($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE id = :id");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_STR);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Pedido');
    // }
}
