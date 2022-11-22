<?php
require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $sector = $parametros['sector'];
        $email = $parametros['email'];
        $telefono = $parametros['telefono'];

        // Creamos el Empleado
        $auxEmpleado = new Empleado();
        $auxEmpleado->sector = $sector;
        $auxEmpleado->email = $email;
        $auxEmpleado->telefono = $telefono;
        $auxEmpleado->crearEmpleado();

        $payload = json_encode(array("mensaje" => "Empleado creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::obtenerTodos();
        $payload = json_encode(array("listaEmpleado" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
