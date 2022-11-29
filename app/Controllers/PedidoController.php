<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/PedidoProducto.php';
require_once './utils/AutentificadorJWT.php';
require_once './Enums/EEPedido.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

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
                Mesa::modificarEstadoMesa($id_mesa, EEmesa::CON_CLIENTE_ESPERANDO);

                $payload = json_encode(array("mensaje" => "Pedido creado con exito", "Codigo_Pedido: " => $auxPedido->codigo_pedido));
                $response->getBody()->write($payload);
            } else {
                $payload = json_encode(array("mensaje" => "No hay stock suficiente."));
                $response->getBody()->write($payload);
            }
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
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
            $payload = json_encode(array("Mensaje" => "Foto subida con exito."));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(array("Mensaje" => "Pedido o mesa no encontrados."));
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

    public function TraerListadoPorRol($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $datosToken = AutentificadorJWT::ObtenerData($token);
        $rol = $datosToken->rol;
        $listaSectores = PedidoProducto::obtenerItemsPorSector($rol);

        $payload = json_encode(array("Listado de pedidos" => $listaSectores));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_pedido = $parametros['id_pedido'];
        $id_producto = $parametros['id_producto'];
        $estado_pedido = $parametros['estado_pedido'];
        $tiempo_estimado = $parametros['tiempo_estimado'];
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $datosToken = AutentificadorJWT::ObtenerData($token);
        $id_responsable = $datosToken->id;

        if (PedidoProducto::modificarEstadoPedido($id_pedido, $id_producto, $id_responsable, $tiempo_estimado, $estado_pedido)) {
            if (Pedido::modificarEstadoPedido($id_pedido, $estado_pedido)) {
                $payload = json_encode(array("mensaje" => "Estado del pedido modificado con exito", "Estado_Pedido: " => EEPedido::getName(intval($estado_pedido))));
                $response->getBody()->write($payload);
            } else {
                $payload = json_encode(array("mensaje" => "no anda ni para atras."));
                $response->getBody()->write($payload);
            }
        } else {
            $payload = json_encode(array("mensaje" => "Ocurrio un error."));
            $response->getBody()->write($payload);
        }
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ConsultarTiempoPedido($request, $response, $args)
    {
        $numero_pedido = $args['numero_pedido'];
        $tiempoPedido = PedidoProducto::obtenerTiempoMaximo($numero_pedido);
        $payload = json_encode($tiempoPedido[0]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function TraerListadoPorEstado($request, $response, $args)
    {
        $auxEstado = $args['estado_Pedido'];
        $listaPedidos = Pedido::obtenerPedidosPorEstado($auxEstado);

        $payload = json_encode(array("Listado de pedidos" => $listaPedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function cerrarPedido($request, $response, $args)
    {
        $auxIdPedido = $args['id_Pedido'];
        $auxPedido = Pedido::obtenerPedidoPorId($auxIdPedido);
        $montoCuenta = Pedido::calcularCuenta($auxPedido);
        if (Mesa::modificarEstadoMesa($auxPedido->id_mesa, EEmesa::CON_CLIENTE_PAGANDO)) {
            $payload = json_encode(array("Monto de la cuenta: " => $montoCuenta, "Estado de la mesa: " => EEmesa::getName(EEmesa::CON_CLIENTE_PAGANDO)));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
        $payload = json_encode("Ocurrio un error.");
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
