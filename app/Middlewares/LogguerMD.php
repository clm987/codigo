<?php
require_once './utils/AutentificadorJWT.php';
require_once './Models/Logger.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as ResquestHandler;
use Slim\Psr7\Response;

class LogguerMD
{
    public function __invoke(Request $request, ResquestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $nivel = AutentificadorJWT::ObtenerData($token);
        $IdUsuario = $nivel->id;
        try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
        }

        if ($esValido) {
            if ($nivel->perfil == "admin") {
                $response = $handler->handle($request);
                return $response;
            } else {
                $response = new Response();
                $response->getBody()->write("No tiene nivel suficiente");
                return $response;
            }
        }
    }
}
