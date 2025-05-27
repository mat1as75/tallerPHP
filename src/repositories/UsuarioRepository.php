<?php
include_once __DIR__ . '/../../src/models/Usuario.php';
include_once __DIR__ . '/../../src/config/database.php';

class UsuarioController
{
    private $usuario;
    private $conn;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->conn = (new Database())->connect();
    }

    public function getUsuarios()
    {
        $usuarios = $this->usuario->getUsuarios();
        echo json_encode($usuarios);
    }

    public function getUsuarioById($id)
    {
        $usuario = $this->usuario->getUsuarioById($id);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(["mensaje" => "Usuario no encontrado"]);
        }
    }

    public function create()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre'], $input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan datos requeridos"]);
            return;
        }

        $email = trim($input['email']);
        $password = trim($input['password']);
        $nombre = trim($input['nombre']);
        $apellido = trim($input['apellido']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Email no válido"]);
            return;
        }

        $success = $this->usuario->create($email, $password, $nombre, $apellido);

        if ($success) {
            http_response_code(201);
            echo json_encode(["mensaje" => "Usuario creado con éxito"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al crear el usuario"]);
        }
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre'], $input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan datos requeridos"]);
            return;
        }

        $email = trim($input['email']);
        $password = trim($input['password']);
        $nombre = trim($input['nombre']);
        $apellido = trim($input['apellido']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Email no válido"]);
            return;
        }

        $success = $this->usuario->update($email, $password, $nombre, $apellido);

        if ($success) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Usuario actualizado con éxito"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al actualizar el usuario"]);
        }
    }

    public function delete($email)
    {
        $success = $this->usuario->delete($email);

        if ($success) {
            echo json_encode(["mensaje" => "Usuario eliminado con éxito"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al eliminar el usuario"]);
        }
    }
}
?>