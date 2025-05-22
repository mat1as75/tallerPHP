<?php
require_once './controllers/PedidoController.php';

$pedidoController = new PedidoController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /pedidos
if (preg_match('/^\/pedidos(\/\d+)?$/', $uri)) {
    $id = null;

    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/pedidos\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $pedidoController->getPedidoById($id) : $pedidoController->getPedidos();
            break;
        case 'POST':
            $pedidoController->create();
            break;
        case 'PUT':
            if ($id) {
                $pedidoController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $pedidoController->delete($id);
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