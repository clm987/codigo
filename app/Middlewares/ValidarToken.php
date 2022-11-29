<?php
require_once './utils/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as ResquestHandler;
use Slim\Psr7\Response;

class ValidarToken
{
    public function __invoke(Request $request, ResquestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $esValido = false;
        try {
            if ($header != null) {
                $token = trim(explode("Bearer", $header)[1]);
                AutentificadorJWT::verificarToken($token);
                $esValido = true;
            } else {
                $esValido = false;
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }

        if ($esValido) {
            $response = $handler->handle($request);
            return $response;
        } else {
            $response = new Response();
            $payload = json_encode(array('Mensaje: ' => 'No se encuentra logueado;'));
            $response->getBody()->write($payload);
            return $response;
        }
    }
}
