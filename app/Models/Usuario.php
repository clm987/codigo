<?php
require_once './Enums/EEusuario.php';


class Usuario
{
    public static function GenerarFecha()
    {
        return $auxFecha = date("Y-m-d");
    }

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, clave, rol, estado, fecha_alta) VALUES (:nombre, :clave, :rol, :estado, :fecha_alta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $estado = EEusuario::isValidValue(1);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_alta', self::GenerarFecha(), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuarioPornombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Usuario');
    }

    public static function armarListaSinClave($lista)
    {
        $arrayDatos = [];

        foreach ($lista as $key => $value) {
            if ($value != null) {
                unset($value->clave);
                array_push($arrayDatos, $value);
            }
        }
        return $arrayDatos;
    }


    // public static function obtenerUsuarioPorId($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE id = :id");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_STR);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Usuario');
    // }
}
