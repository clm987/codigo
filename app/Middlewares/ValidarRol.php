<?php
require_once './utils/AutentificadorJWT.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as ResquestHandler;
use Slim\Psr7\Response as ResponseMW;

class ValidarRol
{
    public $roleArray = []; // Array de roles

    public function __construct($roleArray)
    {
        array_push($this->roleArray, $roleArray);
    }


    public function __invoke(Request $request, ResquestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $datosToken = AutentificadorJWT::ObtenerData($token);
        $rol = $datosToken->rol;
        if (!is_null(intval($rol)) && in_array($rol, $this->roleArray, false)) {
            $response = $handler->handle($request->withAddedHeader('idMozo', $datosToken->id));
            return $response;
        }

        $response = new ResponseMW();
        $response->getBody()->write("No autorizado para realizar esta operacion.");
        return $response;
    }
}
