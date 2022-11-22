<?php
require_once './models/Venta.php';
require_once './models/Cliente.php';
require_once './Controllers/UsuarioController.php';

class VentaController extends Venta
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idUser = $parametros['idUser'];
        $idCliente = $parametros['idCliente'];
        $idArma = $parametros['idArma'];
        $cantidad = $parametros['cantidad'];

        // Creamos el Venta
        $venta = new Venta();
        $venta->id_usuario = $idUser;
        $venta->id_cliente = $idCliente;
        $venta->id_arma = $idArma;
        $venta->cantidad = $cantidad;
        $fechaAlta = Venta::GenerarFecha();
        $IdVenta = intval($venta->crearVenta());
        if ($IdVenta > 0) {
            $venta->guardarFotoVenta(Arma::obtenerArmaPorId($idArma)->nombre, Cliente::obtenerClientePorId($idCliente)->nombre, $fechaAlta);
            $payload = json_encode(array("mensaje" => "Arma creada con exito", "id" => strval($IdVenta)));
            $response->getBody()->write($payload);
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error al generar la venta"));
            $response->getBody()->write($payload);
        }
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


    public function ListadoPaisFecha($request, $response, $args)
    {
        $fechaInicial = $args['fechaInicial'];
        $fechaFinal = $args['fechaFinal'];
        $pais = $args['pais'];
        $lista = Venta::obtenerListaPaisFechas($fechaInicial, $fechaFinal);
        $listaIdsArmas = Arma::devolverArrayArmas($lista, $pais);
        $payload = json_encode(array("listaArmas" => $listaIdsArmas));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
