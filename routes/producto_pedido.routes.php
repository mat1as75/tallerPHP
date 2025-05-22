<?php
require_once './controllers/ProductoPedidoController.php';

$productoPedidoController = new ProductoPedidoController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /productosPedidos
if (preg_match('/^\/productosPedidos(\/\d+)?$/', $uri)) {
    $id = null;

    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/productosPedidos\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $productoPedidoController->getProductoPedidoById($id) : $productoPedidoController->getProductosPedidos();
            break;
        case 'POST':
            $productoPedidoController->create();
            break;
        case 'PUT':
            if ($id) {
                $productoPedidoController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $productoPedidoController->delete($id);
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