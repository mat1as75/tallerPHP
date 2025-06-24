<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;

$allowedOrigins = ['https://tallerphp.uy', 'http://localhost:4200'];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
}

//echo "HTTP_ORIGIN: " . $_SERVER['HTTP_ORIGIN'] . "\n";

//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: http://localhost:4200");
//header("Access-Control-Allow-Origin: https://tallerphp.uy");
header("Acces-Control-Allow-Headers: access");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit();
}


$router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Incluir archivo de rutas
require_once __DIR__ . '/../routes/web.routes.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ejecutar la coincidencia
$router->dispatch();

?>