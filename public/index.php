<?php
// Cabecera para respuestas JSON
header("Content-Type: application/json");

// Obtener ruta y metodo de la peticion
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$matched = false;

// Rutas de cada recurso
$routes = [
    './routes/UsuarioRoutes.php',
    './routes/ProductoRoutes.php',
    './routes/PedidoRoutes.php',
    './routes/ProductoPedidoRoutes.php',
    './routes/CategoriaRoutes.php',
    './routes/ValoracionRoutes.php',
    './routes/CarritoRoutes.php'
];

// Iterar sobre las rutas hasta que una maneje la peticion
foreach ($routes as $routeFile) {
    // Cada archivo debe retornar true si maneja la ruta actual
    if (require $routeFile) {
        $matched = true;
        break;
    }
}

// Si ninguna ruta coincidió, devolver un error 404
if (!$matched) {
    http_response_code(404);
    echo json_encode(["mensaje" => "Ruta no encontrada"]);
}

?>