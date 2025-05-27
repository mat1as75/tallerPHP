<?php
include_once __DIR__ . '/../src/repositories/UsuarioRepository.php';

$usuarioController = new UsuarioController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la ruta empieza con /usuarios
if (str_contains($uri, '/usuarios')) {
    $id = null;
    echo "request: $request[0]\n";
    if (isset($request[1]) && is_numeric($request[1])) {
        echo "ID de usuario detectado: $request[1]\n";
        $id = (int) $request[1];
    }

    try {
        switch ($method) {
            case 'GET':
                $id ? $usuarioController->getUsuarioById($id) : $usuarioController->getUsuarios();
                break;
            case 'POST':
                $usuarioController->create();
                break;
            case 'PUT':
                if (!$id) {
                    throw new Exception("ID requerido para actualizar", 400);
                }
                $usuarioController->update($id);
                break;
            case 'DELETE':
                if (!$id) {
                    throw new Exception("ID requerido para eliminar", 400);
                }
                $usuarioController->delete($id);
                break;
            default:
                throw new Exception("Método no permitido", 405);
        }
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(["error" => $e->getMessage()]);
    }

    return true;
}

return false;
?>