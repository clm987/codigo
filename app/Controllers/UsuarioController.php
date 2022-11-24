<?php
require_once './models/Usuario.php';
require_once './Enums/ERol.php';

class UsuarioController extends Usuario
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $nombre = $parametros['nombre'];
    $clave = $parametros['clave'];
    $rol = $parametros['rol'];
    $nuevoId = -1;

    if (ERol::isValidValue($rol)) {
      $auxUsr = new Usuario();
      $auxUsr->nombre = strtolower($nombre);
      $auxUsr->clave = $clave;
      $auxUsr->rol = $rol;
      if (Usuario::obtenerUsuarioPornombre(strtolower($nombre)) == null) {
        $nuevoId = intval($auxUsr->crearUsuario());
      } else {
        $payload = json_encode(array("mensaje" => "Usuario ya existente."));
        $response->getBody()->write($payload);
      }
    }

    if ($nuevoId > 0) {
      $payload = json_encode(array("mensaje" => "Usuario creado con exito.", "usuarioId" => strval($nuevoId)));
      $response->getBody()->write($payload);
    } else {
      $payload = json_encode(array("mensaje" => "Ocurrió un error."));
      $response->getBody()->write($payload);
    }

    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $listaFormateada = Usuario::armarListaSinClave($lista);
    $payload = json_encode(array("listaUsuario" => $listaFormateada));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
