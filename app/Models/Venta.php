<?php
define("RUTA_BASE1", "C:/Users/usuario/Desktop/Versiones/Parcial2/ModeloArmas/PrimeraParte/app/FotosArma/");

class Venta
{
    // public static function GenerarFecha()
    // {
    //     return $auxFecha = date("Y-m-d");
    // }

    // public static function guardarRuta($nombreArma, $nombreCliente, $fechaAlta)
    // {
    //     $atributoFoto = "";
    //     $_partes = explode(".", $_FILES["foto"]["name"]);
    //     $_extensionArchivo = $_partes[1];
    //     $atributoFoto = RUTA_BASE1 . $nombreArma . $nombreCliente . $fechaAlta . "_" . rand(1, 1000) . "." . $_extensionArchivo;
    //     return $atributoFoto;
    // }

    // public static function calcularMonto($cantidad, $precio)
    // {
    //     $monto = 0;

    //     if ($cantidad = null && $precio != null) {
    //         $monto = $cantidad * $precio;
    //     }
    //     return $monto;
    // }

    // public function crearVenta()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Venta (id_usuario, id_cliente, id_arma, cantidad, monto, foto,fechaAlta) VALUES (:id_usuario,:id_cliente,:id_arma, :cantidad, :monto, :foto, :fechaAlta)");
    //     $this->fechaAlta = self::GenerarFecha();
    //     $auxCliente = Cliente::obtenerClientePorId($this->id_cliente);
    //     $auxArma = Arma::obtenerArmaPorId($this->id_arma);
    //     $this->monto = self::calcularMonto($this->cantidad, $auxArma->precio);
    //     $this->foto = Self::guardarRuta($auxArma->nombre, $auxCliente->nombre, $this->fechaAlta);
    //     $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
    //     $consulta->bindValue(':id_cliente', $this->id_cliente, PDO::PARAM_INT);
    //     $consulta->bindValue(':id_arma', $this->id_arma, PDO::PARAM_INT);
    //     $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
    //     $consulta->bindValue(':monto', $this->monto, PDO::PARAM_STR);
    //     $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
    //     $consulta->bindValue(':fechaAlta', $this->fechaAlta);
    //     $consulta->execute();

    //     return $objAccesoDatos->obtenerUltimoId();
    // }

    // public static function obtenerTodos()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Venta");
    //     $consulta->execute();
    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    // }
    // public static function obtenerListaPaisFechas($fechaInicio, $fechaFinal)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Venta WHERE fechaAlta BETWEEN :fechaInicio AND :fechaFinal");
    //     $consulta->bindValue(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
    //     $consulta->bindValue(':fechaFinal', $fechaFinal, PDO::PARAM_STR);
    //     $consulta->execute();
    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    // }

    // public static function obtenerVentasPorPais($nacionalidad)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, stock, foto, fechaBaja FROM Venta WHERE nacionalidad = :nacionalidad");
    //     $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    // }

    // public static function obtenerVentaPorId($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, nacionalidad, precio, stock, foto, fechaBaja FROM Venta WHERE id = :id");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_STR);
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    // }

    // public static function obtenerVentasPorIdArma($idArma)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Venta WHERE id_arma = :idArma");
    //     $consulta->bindValue(':idArma', $idArma, PDO::PARAM_INT);
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    // }

    // public static function obtenerListadoUsuarios($lista)
    // {
    //     $arrayUsuarios = [];
    //     foreach ($lista as $value) {
    //         $usuario = Usuario::obtenerUsuarioPorId($value->id_usuario);
    //         array_push($arrayUsuarios, $usuario);
    //     }
    //     return $arrayUsuarios;
    // }

    // public function guardarFotoVenta($nombreArma, $nombreCliente, $fecha)
    // {
    //     $_partes = explode(".", $_FILES["foto"]["name"]);
    //     $_extensionArchivo = $_partes[1];
    //     $_FILES["foto"]["name"] = $nombreArma . $nombreCliente . $fecha . "." . $_extensionArchivo;
    //     $path = RUTA_BASE1;
    //     if (!file_exists($path)) {
    //         mkdir($path, 0777, true);
    //         move_uploaded_file($_FILES["foto"]["tmp_name"], RUTA_BASE1 . $_FILES["foto"]["name"]);
    //     } else {
    //         move_uploaded_file($_FILES["foto"]["tmp_name"], RUTA_BASE1 .     $_FILES["foto"]["name"]);
    //     }
    // }
}
