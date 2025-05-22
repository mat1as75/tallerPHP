<?php
require_once './controllers/ValoracionController.php';

$valoracionController = new ValoracionController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /valoracion/(cliente OR producto)
if (preg_match('/^\/valoraciones(\/cliente\/\d+|\/producto\/\d+)?$/', $uri)) {

    switch ($method) {
        case 'GET':
            // Ruta /valoraciones
            if ($uri === '/valoraciones') {
                $valoracionController->getValoraciones();
            }

            // Ruta /valoraciones/cliente/:id
            elseif (preg_match('/^\/valoraciones\/cliente\/(\d+)$/', $uri, $matches)) {
                $idCliente = $matches[1];
                $valoracionController->getValoracionesByIdCliente($idCliente);
            }

            // Ruta /valoraciones/producto/:id
            elseif (preg_match('/^\/valoraciones\/producto\/(\d+)$/', $uri, $matches)) {
                $idProducto = $matches[1];
                $valoracionController->getValoracionesByIdProducto($idProducto);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "Ruta no encontrada"]);
            }

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