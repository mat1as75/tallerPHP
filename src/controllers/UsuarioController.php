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

 
       
        if (!isset($input['nombre'], $input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan datos requeridos LA CONCHA DE TU MADRE"]);
            return;
        }

        $email = trim($input['email']);
        $password = trim($input['password']);
        $nombre = trim($input['nombre']);
        $apellido = trim($input['apellido']);
        $rol = trim($input['rol']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Email no válido"]);
            return;
        }

       
        $success = $this->usuario->create($email, $password, $nombre, $apellido, $rol);

        if ($success) {
            if($rol == "cliente") {
                http_response_code(201);
                echo json_encode(["mensaje" => "Cliente creado con éxito "]);
            }else if ($rol == 'Administrador'){
                http_response_code(201);
                echo json_encode(['mensaje'=> 'Administrador Creado con exito']);
            }else{
                http_response_code(201);
                echo json_encode(['mensaje'=> 'Usuario creado con exito']);
            }
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


    public function iniciarSecion(){
        
         // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);


    if(!isset($input['email'], $input['password'])){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta email o Password"]);
        return;
    }
    

    $email= trim($input['email']);
    $password= trim($input['password']);

    $usuario = $this->usuario->buscarUsuarioporMail($email);

    
 if (!$usuario) {
        http_response_code(401);
        echo json_encode(["mensaje" => "Usuario no encontrado"]);
        return;
    }


    if ($usuario && isset($usuario["Contrasena"])) {
    $contra = $usuario["Contrasena"];
    }
    
    
    // Aquí va la verificación de la contraseña
    if (!password_verify($password, $contra)) {
        http_response_code(401);
        echo json_encode(["mensaje" => "Contraseña incorrecta"]);
        return;
    }

    if(!(isset($_COOKIE['session_ID']))) //isset() comprueba si la cookie (session_ID) está
                                         //definida dentro de la script que se está ejecutando.
     {
        setcookie(
    'session_ID',
    $usuario['ID'],
  [
           'expires' => time() + 3600,
            'path' => '/',
            'secure' => false,      
            'httponly' => false,
            'samesite' => 'Lax'    // Necesario para cookies entre dominios
  ]
);

        //echo  json_encode( "Se ha creado la cookie con el ID ");
     }else{
        $valor = $_COOKIE["session_ID"];
        //echo json_encode("YA EXISTE COOKIE". $valor);
     }
        







    // Si pasa la verificación, inicio exitoso
    http_response_code(200);
   /* echo json_encode([
        "mensaje" => "Inicio de sesión exitoso",
        "usuario" => [
            "id" => $usuario['ID'],
            "nombre" => $usuario['Nombre'],
            "email" => $usuario['Email']
        ] datos del usuarios
    ]);*/



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

     if(!isset($input['token']) || !isset($input['password']) ){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta token o password"]);
        return;
    }

    
    $token= trim($input['token']);
    $nuevaPass = trim($input['password']);

    //$otrotoken = $usuario['token'];


   
   /* if ($otrotoken != $token) {
        http_response_code(400);
        echo json_encode(['mensaje'=> 'El Token no coincide'. $token. 'EL OTRO TOKE =>' . $otrotoken]);
    }*/


    if($this->usuario->AtualizoPassword($token, $nuevaPass)){
        http_response_code(200);
        echo json_encode(['mensaje'=> 'Contrasenia Actualizada con exito']);
        return true;
    }else{
        http_response_code(400);
        echo json_encode(['mensaje'=> 'Contrasenia Actualizada con exito']);
        return false;
    }

 
    


}



 public function VerificoToken(){

              // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);

    $token= trim($input["token"]);


    $verifico = $this->usuario->verificoToken($token);


    if ($verifico == null) {
        http_response_code(400);
        echo json_encode(['valido' => false, 'mensaje' => 'El token no es válido']);
        return false;
    }else{
        http_response_code(200);
        echo json_encode(['valido' => true, 'mensaje' => 'El token es válido']);
        return true;
    }





 }

    public function buscarUsuarioporMail($email){

        return $this->usuario->buscarUsuarioporMail($email);

    }


    public function todastuscompras(){

                  // 1. Recibir y decodificar los datos del cuerpo JSON
    $input = json_decode(file_get_contents("php://input"), true);



    $email = trim($input["email"]);

       if(!isset($input['email'])){
        http_response_code(400);
        echo json_encode(["mensaje"=>"Falta email"]);
        return;
    }


    $usr = $this->usuario->buscarUsuarioporMail( $email);


        if ($usr == null){
            http_response_code(400);
            echo json_encode(["Mensaje"=> "Uusario no encontrado"]);
            return false;
        }


    $compras = $this->usuario->comprasRealizadas($usr["ID"]);    

    if($compras == null){
        http_response_code(400);
        echo json_encode(["Mensaje"=> "El usuario no tiene compras"]);
        return false;
    }else{
        http_response_code(200);
        echo json_encode(["Compras:"=> $compras]);
    }
}

//FUNCIONES ADMINISTRADOR----------

    // Busca usuarios con filtros
    public function buscarUsuarios()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // lo que venga lo usamos como filtro ej=  {"Nombre": "Juan", "Email": "gmail"}
        
        $filtros = !empty($data) ? $data : null;

        $usuarios = $this->usuario->buscarUsuarios($filtros);

        echo json_encode($usuarios);
    }


    //CREAR GESTOR
    //EJEMPLO JSON {
    /*
    
    "mail": carlos.álvarez@correo.com,
    "p_producto": 1,
    "p_inventario": 1,
    "p_pedidos": 0,
    "p_validacion": 0,
    "p_soporte": 1
    }
    
    */

    public function crearGestor(){

        $data = json_decode(file_get_contents("php://input"), true);

        $campos = ['mail', 'p_producto', 'p_inventario', 'p_pedidos', 'p_validacion', 'p_soporte'];

        //LLAMAR CONTROLADOR USUARIO PARA OBTENER ID DEL USUARIO VIA MAIL / INSTANCIAR CONTROLADOR
        
        $usuario = $this->buscarUsuarioporMail($data['mail']);

        //SACAR ID DEL USUARIO
        $id = $usuario['ID'];


        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el campo '$campo'"]);
                return;
            }
        }

        try {
            $resultado = $this->usuario->crearGestor(
                $id,
                $data['p_producto'],
                $data['p_inventario'],
                $data['p_pedidos'],
                $data['p_validacion'],
                $data['p_soporte']
            );
            echo json_encode(["success" => $resultado]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }

        
    }

    //MODIFICAR GESTOR
    public function modificarGestor(){

        $data = json_decode(file_get_contents("php://input"), true);

        $campos = ['id', 'p_producto', 'p_inventario', 'p_pedidos', 'p_validacion', 'p_soporte'];
        foreach ($campos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el campo '$campo'"]);
                return;
            }
        }

        try {
            $resultado = $this->usuario->modificarGestor(
                $data['id'],
                $data['p_producto'],
                $data['p_inventario'],
                $data['p_pedidos'],
                $data['p_validacion'],
                $data['p_soporte']
            );

            if ($resultado) {
                echo json_encode(["success" => true, "mensaje" => "Gestor modificado correctamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["success" => false, "mensaje" => "No se encontró el gestor o no hubo cambios"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    //ELIMINAR GESTOR

    public function eliminarGestor(){
            
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Falta el campo 'id'"]);
            return;
        }

        try {
            $resultado = $this->usuario->eliminarGestor($data['id']);

            if ($resultado) {
                echo json_encode(["success" => true, "mensaje" => "Gestor eliminado correctamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["success" => false, "mensaje" => "No se encontró el gestor"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }








}

?>