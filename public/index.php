<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cabecera para respuestas JSON
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtener ruta y metodo de la peticion
//$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$path_info = $_SERVER['PATH_INFO'] ?? '';
$request = explode("/", substr($path_info, 1));
echo "Ruta solicitada: $path_info\n";
$matched = false;

// Rutas de cada recurso
$routes = [
    '../routes/usuario.routes.php',
    '../routes/producto.routes.php',
    '../routes/pedido.routes.php',
    '../routes/producto_pedido.routes.php',
    '../routes/categoria.routes.php',
    '../routes/valoracion.routes.php',
    '../routes/carrito.routes.php',
];

// Iterar sobre las rutas hasta que una maneje la peticion
foreach ($routes as $routeFile) {
    echo 'Rutas: ' . $routes[0] . "\n";
    echo "Procesando ruta: $routeFile\n";
    $i = 0;
    // Cada archivo debe retornar true si maneja la ruta actual
    if (include $routeFile) { //$routeFile
        $matched = true;
        break;
    }
    $i++;
}

// Si ninguna ruta coincidió, devolver un error 404
if (!$matched) {
    http_response_code(404);
    echo json_encode(["mensaje" => "Ruta no encontrada"]);
}

//echo "<h1>API TALLER PHP</h1>"

?>