<?php
require_once './models/Mesa.php';
require_once './Enums/EEmesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args)
    {
        //$parametros = $request->getParsedBody();
        $auxMesa = new Mesa();
        $auxMesa->estado = EEmesa::CERRADA;
        $nuevoId = $auxMesa->crearMesa();

        if ($nuevoId > 0) {
            $codigoMesa = Mesa::obtenerMesaPorId($nuevoId);
            $payload = json_encode(array("mensaje" => "Mesa creada con exito", "Codigo Mesa: " => strval($codigoMesa->codigo_mesa)));
            $response->getBody()->write($payload);
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrió un error."));
            $response->getBody()->write($payload);
        }
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("ListaDeMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
