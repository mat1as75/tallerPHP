<?php
include_once __DIR__ . '/../../src/repositories/UsuarioRepository.php';
include_once __DIR__ . '/../../src/config/database.php';

class UsuarioController
{
    private $usuario;

    public function __construct()
    {
        $this->usuario = new UsuarioRepository();
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

        echo json_encode("LLEGASTE PAPU");
       
        if (!isset($input['nombre'], $input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan datos requeridos LA CONCHA DE TU MADRE"]);
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

        echo json_encode("LLEGASTE PAPU2");
       
        $success = $this->usuario->create($email, $password, $nombre, $apellido);

        if ($success) {
            http_response_code(201);
            echo json_encode(["mensaje" => "Usuario creado con éxito PELOTUDO"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al crear el usuario PELOTUDO"]);
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


    public function iniciarSecion(){
        
         // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);


    if(!isset($input['email'], $input['password'])){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta email o Password"]);
        return;
    }
    

    $email= trim($input['email']);
    $password= trim($input(['password']));

    $usuario = $this->usuario->buscarUsuarioporMail($email);

 if (!$usuario) {
        http_response_code(401);
        echo json_encode(["mensaje" => "Usuario no encontrado"]);
        return;
    }

    // Aquí va la verificación de la contraseña
    if (!password_verify($password, $usuario['password'])) {
        http_response_code(401);
        echo json_encode(["mensaje" => "Contraseña incorrecta"]);
        return;
    }

    // Si pasa la verificación, inicio exitoso
    http_response_code(200);
    echo json_encode([
        "mensaje" => "Inicio de sesión exitoso",
        "usuario" => [
            "id" => $usuario['id'],
            "nombre" => $usuario['nombre'],
            "email" => $usuario['email']
        ]
    ]);



    }


    public function RecuperarPassword(){


           // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);



    if(!isset($input['email'])){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta email"]);
        return;
    }


    $email= trim($input['email']);

 $usuario = $this->usuario->buscarUsuarioporMail($email);

if (!$usuario) {
        http_response_code(401);
        echo json_encode(["mensaje" => "email no registrado"]);
        return;
    }

error_log("ESTOY ACA PODES VER ANTES DEL VERIFICADO");
    if($this->usuario->enviomailverificado($email)){
        http_response_code(200);
        echo json_encode(["mensaje"=> "Mail de verificacion enviado"]);
    }else{
        http_response_code(400);
        echo json_encode(["mensaje"=> "Error al enviar el mail"]);
    }

}


public function CambioPassword(){

           // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if(!isset($input['email'])){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta email"]);
        return;
    }


    $email= trim($input['email']);
    $token= trim($input['token']);
    $nuevaPass = trim($input['password']);

    $usuario = $this->usuario->buscarUsuarioporMail($email);


    if (!$usuario) {
        http_response_code(400);
        echo json_encode(['mensaje'=> 'Usuario con ese email no encontrado el email RECIBIDO = > '. $email]);
    }

    //$otrotoken = $usuario['token'];

   /* if ($otrotoken != $token) {
        http_response_code(400);
        echo json_encode(['mensaje'=> 'El Token no coincide'. $token. 'EL OTRO TOKE =>' . $otrotoken]);
    }*/


    if($this->usuario->AtualizoPassword( $email, $token, $nuevaPass)){
        http_response_code(200);
        echo json_encode(['mensaje'=> 'Contrasenia Actualizada con exito']);
        return true;
    }else{
        http_response_code(400);
        echo json_encode(['mensaje'=> 'Contrasenia Actualizada con exito']);
        return false;
    }




}


}
?>