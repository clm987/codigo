<?php
require_once './utils/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as ResquestHandler;
use Slim\Psr7\Response;

class ValidarRol
{
    public function __invoke(Request $request, ResquestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $datosToken = AutentificadorJWT::ObtenerData($token);
        try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }

        if ($esValido) {
            if ($datosToken->rol == "MOZO") {
                $response = $handler->handle($request->withAddedHeader('idMozo', $datosToken->id));
                return $response;
            } else {
                $response = new Response();
                $response->getBody()->write("No autorizado para realizar esta operacion.");
                return $response;
            }
        }
    }

    public function funcionDeprueba(Request $request, ResquestHandler $handler)
    {
        echo "entro a la funcion de prueba";
        $response = $handler->handle($request);
        return $response;
    }
}
