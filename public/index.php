<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;

// === Encabezados CORS ===
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// === Manejar preflight (OPTIONS) ===
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// === Ruteo ===
$router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Incluir archivo de rutas
require_once __DIR__ . '/../routes/web.routes.php';

// Ejecutar la coincidencia
$router->dispatch();
