<?php
require_once './models/Arma.php';

class ArmaController extends Arma
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $precio = $parametros['precio'];
        $nombre = $parametros['nombre'];
        $nacionalidad = $parametros['nacionalidad'];
        $stock = $parametros['stock'];

        // Creamos el Arma
        $arma = new Arma();
        $arma->guardarFotoArma($nombre, $nacionalidad);
        $arma->nombre = $nombre;
        $arma->nacionalidad = $nacionalidad;
        $arma->precio = $precio;
        $arma->stock = $stock;
        $arma->foto = $arma->guardarRuta($nombre, $nacionalidad);
        $IdArma = $arma->crearArma();
        $payload = json_encode(array("mensaje" => "Arma creada con exito", "id" => strval($IdArma)));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorPais($request, $response, $args)
    {
        // Buscamos Arma por pais
        $nacionalidad = $args['nacionalidad'];
        $arma = Arma::obtenerArmasPorPais($nacionalidad);
        $payload = json_encode($arma);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUsuariosPorNombre($request, $response, $args)
    {
        // Buscamos Arma por nombre
        $nombre = $args['nombre'];
        $arma = Arma::obtenerArmaPorNombre($nombre);
        $lisVentas = Venta::obtenerVentasPorIdArma($arma->id);
        $listaUsuarios = Venta::obtenerListadoUsuarios($lisVentas);
        $payload = json_encode($listaUsuarios);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUnoPorId($request, $response, $args)
    {
        // Buscamos Arma por id
        $id = $args['id'];
        $arma = Arma::obtenerArmaPorId($id);
        $payload = json_encode($arma);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Arma::obtenerTodos();
        $payload = json_encode(array("listaArmas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        Arma::borrarArma($id);

        $payload = json_encode(array("mensaje" => "Arma borrado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $nombre = $args['nombre'];
        $nuevoNombre = $args['nuevoNombre'];
        $arma = Arma::obtenerArmaPorNombre($nombre);
        Arma::modificarArma($arma->id, $nuevoNombre);
        $armaModificada = Arma::obtenerArmaPorNombre($nuevoNombre);

        $payload = json_encode(array("mensaje" => "Arma modificado con exito", $armaModificada));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
