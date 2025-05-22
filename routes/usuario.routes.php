<?php
require_once './controllers/UsuarioController.php';

$usuarioController = new UsuarioController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /usuarios
if (preg_match('/^\/usuarios(\/\d+)?$/', $uri)) {
    $id = null;
    // Si tiene un ID, lo extraemos
    if (preg_match('/^\/usuarios\/(\d+)$/', $uri, $matches)) {
        $id = $matches[1];
    }

    switch ($method) {
        case 'GET':
            $id ? $usuarioController->getUsuarioById($id) : $usuarioController->getUsuarios();
            break;
        case 'POST':
            $usuarioController->create();
            break;
        case 'PUT':
            if ($id) {
                $usuarioController->update($id);
            } else {
                http_response_code(400);
                echo json_encode(["mensaje" => "ID requerido para actualizar"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $usuarioController->delete($id);
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