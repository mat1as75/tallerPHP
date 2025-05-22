<?php
require_once './controllers/ProductoController.php';

$productoController = new ProductoController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /productos
if (preg_match('/^\/productos(\/\d+)?$/', $uri)) {
    $id = null;

    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/productos\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $productoController->getProductoById($id) : $productoController->getProductos();
            break;
        case 'POST':
            $productoController->create();
            break;
        case 'PUT':
            if ($id) {
                $productoController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $productoController->delete($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para eliminar"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["mensaje" => "Método no permitido"]);
            break;
    }

    return true;
}

return false;
?>