<?php
require_once './Models/Usuario.php';
require_once './Utils/AutentificadorJWT.php';

class LogginController
{
    public function ValidarUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();


        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $usuario = Usuario::obtenerUsuarioPornombre($nombre);
        if ($usuario != null) {
            if (password_verify($clave, $usuario->clave)) {
                $payload = json_encode(
                    array(
                        "mensaje" => "Login Exitoso!!",
                        "token" => AutentificadorJWT::CrearToken($usuario->nombre, $usuario->rol, $usuario->id)
                    )
                );
            } else {
                $payload = json_encode(array("mensaje" => "Clave invalida"));
            }
        } else {
            $payload = json_encode(array("mensaje" => "Usuario inexistente"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
