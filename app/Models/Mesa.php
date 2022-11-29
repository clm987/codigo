<?php

class Mesa
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    public static function GenerarCodigoMesa()
    {
        $length = 2;
        $letrasRandom = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
        return $auxCodigo  = $letrasRandom . Rand(100, 999);
    }

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (codigo_mesa, estado, fecha_alta) VALUES (:codigo_mesa, :estado, :fecha_alta)");
        $codigoMesa = self::GenerarCodigoMesa();
        $consulta->bindValue(':codigo_mesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', self::GenerarFecha(), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function modificarEtiquetasEstado($lista)
    {
        foreach ($lista as $value) {
            $value->estado = EEmesa::getName($value->estado);
        }

        return $lista;
    }

    public static function obtenerMesaPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerMesaPorCodigo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE codigo_mesa = :codigo_mesa");
        $consulta->bindValue(':codigo_mesa', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function modificarEstadoMesa($idMesa, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesa SET estado = :estado WHERE id = :id_mesa");
        $consulta->bindValue(':id_mesa', $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function mesaMasUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT p.id_mesa, count(p.id_mesa) maximo
            FROM pedido as p
            GROUP BY
            p.id_mesa HAVING count(p.id_mesa) =
            (SELECT
            max(i.total) FROM
            (SELECT count(p.id_mesa) as total
            FROM pedido as p
            GROUP BY p.id_mesa) as i)"
        );
        //$consulta->bindValue(':id_pedido', $pedido->id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }
}
