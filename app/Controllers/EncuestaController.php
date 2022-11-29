<?php
require_once './models/Encuesta.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './Enums/EEmesa.php';

class EncuestaController extends Encuesta
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo_mesa = $parametros['codigo_mesa'];
        $codigo_pedido = $parametros['codigo_pedido'];
        $punt_mesa = $parametros['punt_mesa'];
        $punt_resto = $parametros['punt_resto'];
        $punt_mozo = $parametros['punt_mozo'];
        $punt_cocinero = $parametros['punt_cocinero'];
        $descripcion = $parametros['descripcion'];
        $auxPedidoEncontrado = Pedido::obtenerPedidoPorCodigo($codigo_pedido);
        $auxMesaEncontrada = Mesa::obtenerMesaPorCodigo($codigo_mesa);
        if ($auxPedidoEncontrado != null && $auxMesaEncontrada != null) {
            $nuevaEncuesta = new Encuesta();
            $nuevaEncuesta->puntuacion_mesa = $punt_mesa;
            $nuevaEncuesta->puntuacion_restaurante = $punt_resto;
            $nuevaEncuesta->puntuacion_mozo = $punt_mozo;
            $nuevaEncuesta->puntuacion_cocinero = $punt_cocinero;
            $nuevaEncuesta->descripcion_experiencia = $descripcion;
            $nuevaEncuesta->promedio_puntuacion = $nuevaEncuesta->calcularPromedio();
            $nuevoId = $nuevaEncuesta->crearEncuesta();
        }
        if ($nuevoId > 0) {
            $payload = json_encode(array("mensaje" => "Encuesta creada con exito", "Id encuesta: " => strval($nuevoId)));
            $response->getBody()->write($payload);
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("ListaDeEncuestas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function informes($request, $response, $args)
    {
        $auxNumeroInforme = $args['id_informe'];
        switch ($auxNumeroInforme) {
            case '1':
                $auxLista = Encuesta::mejoresComentarios();
                $payload = json_encode($auxLista);
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
