<?php
require_once './models/Producto.php';
require_once './Enums/ESector.php';

class ProductoController extends Producto
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $stock = $parametros['stock'];
        $sector = $parametros['sector'];
        $precio = $parametros['precio'];
        $nuevoId = -1;
        $auxProdExistente = Producto::obtenerProductoPornombre(strtoupper($nombre));

        if ($auxProdExistente == null) {
            if (ESector::isValidName($sector)) {
                $auxProducto = new Producto();
                $auxProducto->nombre = strtoupper($nombre);
                $auxProducto->stock = $stock;
                $auxProducto->sector = $sector;
                $auxProducto->precio = $precio;
                $nuevoId = intval($auxProducto->crearProducto());
            } else {
                $payload = json_encode(array("mensaje" => "Marque un sector valido."));
                $response->getBody()->write($payload);
                return $response;
            }
        } else {
            $nuevoStock = $auxProdExistente->stock + $stock;
            Producto::actualizarStock($auxProdExistente->id, $nuevoStock);
            $payload = json_encode(array("mensaje" => "Producto ya existente, se actualiza el stock"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }

        if ($nuevoId > 0) {
            $payload = json_encode(array("mensaje" => "Producto creado con exito.", "productoId" => strval($nuevoId)));
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
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
