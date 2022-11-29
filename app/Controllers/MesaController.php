<?php
require_once './models/Mesa.php';
require_once './Enums/EEmesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args)
    {
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
        $lista = Mesa::modificarEtiquetasEstado($lista);
        $payload = json_encode(array("ListaDeMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $auxMesa = $parametros['id_mesa'];
        $auxEstado = $parametros['estado'];

        if (Mesa::modificarEstadoMesa($auxMesa, $auxEstado)) {
            $payload = json_encode(array("mensaje" => "Estado de la mesa modificado con exito", "Estado_Mesa: " => EEmesa::getName(intval($auxEstado))));
            $response->getBody()->write($payload);
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $auxMesa = $parametros['Id_mesa'];
        $auxEstado = $parametros['estado'];

        if (Mesa::modificarEstadoMesa($auxMesa, $auxEstado)) {
            $payload = json_encode(array("mensaje" => "Mesa cerrada con exito", "Estado_Mesa: " => EEmesa::getName(intval($auxEstado))));
            $response->getBody()->write($payload);
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function informes($request, $response, $args)
    {
        $auxNumeroInforme = $args['id_informe'];
        switch ($auxNumeroInforme) {
            case '1':
                $auxMesaUsada = Mesa::mesaMasUsada();
                $auxIdMesa = $auxMesaUsada[0]->id_mesa;
                $cantidadVeces = $auxMesaUsada[0]->maximo;
                $payload = json_encode(array("Mesa mas usada: " => $auxIdMesa, "Cantidad de usos: " => $cantidadVeces));
                $response->getBody()->write($payload);
                return $response
                    ->withHeader('Content-Type', 'application/json');
                break;
            default:
                # code...
                break;
        }
        $payload = json_encode(array("mensaje" => "Ocurrio un error."));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
