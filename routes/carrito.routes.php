<?php
include_once __DIR__ . '/../src/repositories/CarritoRepository.php';

$carritoController = new CarritoController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /carritos
if (preg_match('/^\/carritos(\/\d+)?$/', $uri)) {
    $id = null;
    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/carritos\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $carritoController->getCarritoById($id) : $carritoController->getCarritos();
            break;
        case 'POST':
            $carritoController->create();
            break;
        case 'PUT':
            if ($id) {
                $carritoController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $carritoController->delete($id);
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