<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Incluir archivo de rutas
require_once __DIR__ . '/../routes/web.routes.php';

// Ejecutar la coincidencia
$router->dispatch();

?>