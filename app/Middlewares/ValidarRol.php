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

        try {
            $token = trim(explode("Bearer", $header)[1]);
            $datosToken = AutentificadorJWT::ObtenerData($token);
            if (!is_null($datosToken)) {
                $rol = $datosToken->rol;
                if (!is_null(intval($rol)) && in_array($rol, $this->roleArray, false)) {
                    $response = $handler->handle($request->withAddedHeader('idMozo', $datosToken->id));
                    return $response;
                }
            }
        } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
            $response = new ResponseMW();
            $response->getBody()->write($payload);
            return $response;
        }
        $response = new ResponseMW();
        $payload = json_encode(array("Mensaje" => "No esta autorizado para realizar esta operacion."));
        $response->getBody()->write($payload);
        return $response;
    }
}
