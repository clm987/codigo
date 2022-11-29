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
require_once './Controllers/EncuestaController.php';
require_once './Controllers/CSVController.php';
require_once './Middlewares/ValidarRol.php';
require_once './Middlewares/ValidarToken.php';
require_once './Enums/ERol.php';

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
$app->post('/leerCSV[/]', \CsvController::class . ':LeerCsv')->add(new ValidarRol(ERol::SOCIO))->add(new ValidarToken());
$app->post('/generarCSV[/]', \CsvController::class . ':GenerarCSV')->add(new ValidarRol(ERol::SOCIO))->add(new ValidarToken());

$app->group('/clientes', function (RouteCollectorProxy $group) {
  $group->get('[/{codigo_mesa}/{numero_pedido}]', \PedidoController::class . ':ConsultarTiempoPedido');
  $group->post('[/]', \EncuestaController::class . ':CargarUno');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
})->add(new ValidarRol(ERol::SOCIO))->add(new ValidarToken());

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{producto}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
})->add(new ValidarRol(ERol::MOZO))->add(new ValidarToken());

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new ValidarRol(ERol::SOCIO));
  $group->get('/{mesa}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(new ValidarRol(ERol::SOCIO));
})->add(new ValidarToken());

$app->group('/servirpedido', function (RouteCollectorProxy $group) {
  $group->get('/{estado_Pedido}', \PedidoController::class . ':TraerListadoPorEstado');
  $group->post('[/]', \MesaController::class . ':ModificarUno');
})->add(new ValidarRol(ERol::MOZO))->add(new ValidarToken());

$app->group('/cerrarpedido', function (RouteCollectorProxy $group) {
  $group->get('/{id_Pedido}', \PedidoController::class . ':cerrarPedido')->add(new ValidarRol(ERol::MOZO));
  $group->post('[/]', \MesaController::class . ':CerrarMesa')->add(new ValidarRol(ERol::SOCIO));
})->add(new ValidarToken());

$app->group('/informes', function (RouteCollectorProxy $group) {
  $group->get('/mesas/{id_informe}', \MesaController::class . ':informes');
  $group->get('/encuestas/{id_informe}', \EncuestaController::class . ':informes');
})->add(new ValidarRol(ERol::SOCIO))->add(new ValidarToken());

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new ValidarRol(ERol::SOCIO));
  $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new ValidarRol(ERol::MOZO));
})->add(new ValidarToken());

$app->group('/cocina', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerListadoPorRol');
  $group->post('[/]', \PedidoController::class . ':ModificarUno');
})->add(new ValidarRol(ERol::COCINERO));

$app->group('/bar', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerListadoPorRol');
  $group->post('[/]', \PedidoController::class . ':ModificarUno');
})->add(new ValidarRol(ERol::BARTENDER));

$app->group('/cerveceria', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerListadoPorRol');
  $group->post('[/]', \PedidoController::class . ':ModificarUno');
})->add(new ValidarRol(ERol::CERVECERO));

$app->get('[/{numero_pedido}]', \PedidoController::class . ':ConsultarTiempoPedido')->add(new ValidarRol(ERol::SOCIO))->add(new ValidarToken());
$app->run();
