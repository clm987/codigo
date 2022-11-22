<?php


class Logger
{

    public function crearLogger()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Logger (
            id_usuario, id_arma, accion, fecha_accion) VALUES (:id_usuario, :id_arma, :accion, :fecha_accion)");
        $fechaAccion = self::GenerarFecha();
        $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':id_arma', $this->id_arma, PDO::PARAM_INT);
        $consulta->bindValue(':accion', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_accion', $fechaAccion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }
}
