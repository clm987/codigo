<?php

class Encuesta
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    // public static function GenerarCodigoEncuesta()
    // {
    //     $length = 2;
    //     $letrasRandom = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
    //     return $auxCodigo  = $letrasRandom . Rand(100, 999);
    // }

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuesta (puntuacion_mesa, puntuacion_restaurante, puntuacion_mozo,puntuacion_cocinero, descripcion_experiencia, promedio_puntuacion,fecha_alta) VALUES (:puntuacion_mesa, :puntuacion_restaurante, :puntuacion_mozo, :puntuacion_cocinero, :descripcion_experiencia,:promedio_puntuacion, :fecha_alta)");
        $consulta->bindValue(':puntuacion_mesa', $this->puntuacion_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_restaurante', $this->puntuacion_restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_mozo', $this->puntuacion_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_cocinero', $this->puntuacion_cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion_experiencia', $this->descripcion_experiencia, PDO::PARAM_STR);
        $consulta->bindValue(':promedio_puntuacion', $this->promedio_puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_alta', self::GenerarFecha(), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuestaPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Encuesta');
    }

    public static function obtenerEncuestaPorCodigo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa WHERE codigo_mesa = :codigo_mesa");
        $consulta->bindValue(':codigo_mesa', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Encuesta');
    }

    public function EncuestaToCsv()
    {
        $dataString = $this->id;
        $dataString .= ",";
        $dataString .= $this->puntuacion_mesa;
        $dataString .= ",";
        $dataString .= $this->puntuacion_restaurante;
        $dataString .= ",";
        $dataString .= $this->puntuacion_mozo;
        $dataString .= ",";
        $dataString .= $this->puntuacion_cocinero;
        $dataString .= ",";
        $dataString .= $this->descripcion_experiencia;
        $dataString .= ",";
        $dataString .= $this->promedio_puntuacion;
        $dataString .= ",";
        $dataString .= $this->fecha_alta;
        $dataString .= "\n";
        return $dataString;
    }

    public static function mejoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT descripcion_experiencia
            FROM encuesta
            ORDER BY promedio_puntuacion DESC
            LIMIT 5"
        );
        //$consulta->bindValue(':id_pedido', $pedido->id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'PedidoProducto');
    }
}
