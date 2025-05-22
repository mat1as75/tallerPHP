<?php
require_once './controllers/CategoriaController.php';

$categoriaController = new CategoriaController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /categorias
if (preg_match('/^\/categorias(\/\d+)?$/', $uri)) {
    $id = null;

    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/categorias\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $categoriaController->getCategoriaById($id) : $categoriaController->getCategorias();
            break;
        case 'POST':
            $categoriaController->create();
            break;
        case 'PUT':
            if ($id) {
                $categoriaController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $categoriaController->delete($id);
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