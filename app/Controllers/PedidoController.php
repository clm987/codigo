<?php
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/PedidoProducto.php';
require_once './utils/AutentificadorJWT.php';
require_once './Enums/EEPedido.php';

class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_mesa = $parametros['id_mesa'];
        $nombre_cliente = $parametros['nombre_cliente'];
        $listaProductos = $parametros['lista_productos'];
        $id_mozo = $request->getHeaderLine('idMozo');

        // Creamos el Pedido
        $auxPedido = new Pedido();
        $auxPedido->estado = EEPedido::PENDIENTE;
        $auxPedido->id_mesa = $id_mesa;
        $auxPedido->nombre_cliente = $nombre_cliente;
        $auxPedido->id_mozo = $id_mozo;
        $nuevoId = $auxPedido->crearPedido();
        if ($nuevoId > 0) {
            if (PedidoProducto::cargarLista($listaProductos, $nuevoId)) {
                $payload = json_encode(array("mensaje" => "Pedido creado con exito", "Codigo_Pedido: " => $auxPedido->codigo_pedido));
                $response->getBody()->write($payload);
            } else {
                $payload = json_encode(array("mensaje" => "Ocurrio un error."));
                $response->getBody()->write($payload);
            }
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
        $payload = json_encode(array("mensaje" => "prueba."));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CargarFoto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo_mesa = $parametros['codigo_mesa'];
        $codigo_pedido = $parametros['codigo_pedido'];
        $auxPedidoEncontrado = Pedido::obtenerPedidoPorCodigo($codigo_pedido);
        $auxMesaEncontrada = Mesa::obtenerMesaPorCodigo($codigo_mesa);
        if ($auxPedidoEncontrado != null && $auxMesaEncontrada != null) {
            $auxPedidoEncontrado->guardarFotoPedido($codigo_mesa, $codigo_pedido);
            $payload = json_encode(array("mensaje" => "Foto subida con exito."));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(array("mensaje" => "Pedido o mesa no encontrados."));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("ListaDePedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
