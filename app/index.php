<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './BaseDeDatos/AccesoDatos.php';
require_once './Controllers/UsuarioController.php';
require_once './Controllers/MesaController.php';
require_once './Controllers/PedidoController.php';
require_once './Controllers/ProductoController.php';
require_once './Controllers/LogginController.php';
require_once './Middlewares/ValidarRol.php';
require_once './Middlewares/ValidarToken.php';
// require_once './Middlewares/LogguerMD.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


// Routes

$app->post('/login[/]', \LogginController::class . ':ValidarUsuario');
$app->post('/fotoPedido[/]', \PedidoController::class . ':CargarFoto');

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{producto}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
})->add(new ValidarToken());

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{mesa}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
})->add(new ValidarToken());

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{pedido}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new ValidarRol());
})->add(new ValidarToken());

// $app->group('/armasPublic', function (RouteCollectorProxy $group) {
//   $group->get('[/]', \ArmaController::class . ':TraerTodos');
//   $group->get('/{nacionalidad}', \ArmaController::class . ':TraerUnoPorPais');
// });

// $app->group('/armasUsers', function (RouteCollectorProxy $group) {
//   $group->get('/{id}', \ArmaController::class . ':TraerUnoPorId')->add(new ValidarToken());
//   $group->post('[/]', \ArmaController::class . ':CargarUno')->add(new Validaciones());
// });

// $app->delete('/armas/[{id}]', \ArmaController::class . ':BorrarUno')->add(new LogguerMD())->add(new Validaciones());

// $app->put('/armas[/{nombre}/{nuevoNombre}]', \ArmaController::class . ':ModificarUno')->add(new Validaciones());

// $app->get('/armas/{nombre}', \ArmaController::class . ':TraerUsuariosPorNombre')->add(new Validaciones());

// $app->group('/ventas', function (RouteCollectorProxy $group) {
//   $group->get('[/{fechaInicial}/{fechaFinal}/{pais}]', \VentaController::class . ':ListadoPaisFecha')->add(new Validaciones());
//   $group->post('[/]', \VentaController::class . ':CargarUno');
// })->add(new ValidarToken());

$app->run();
