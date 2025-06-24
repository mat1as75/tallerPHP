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

        if (!isset($input['Nombre'], $input['Email'], $input['Password'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan datos requeridos"]);
            return;
        }

        $email = trim($input['Email']);
        $password = trim($input['Password']);
        $nombre = trim($input['Nombre']);
        $apellido = trim($input['Apellido']);
        $rol = trim($input['Rol']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Email no válido"]);
            return;
        }

        $usr = $this->usuario->buscarUsuarioporMail($email);

        if ($usr) {
            if ($usr["Activo"] === "0") {
                http_response_code(400);
                echo json_encode(["Mensaje" => "El usuario esta dado de baja, debe mandar un email"]);
                return;
            }
            http_response_code(400);
            echo json_encode(["Mensaje" => "Usuario ya registrado"]);
            return;
        }


  
        //SI ES GESTOR -------------------------------------
        if ($rol == 'gestor'){

            $p_producto = $input['p_producto'] ?? 0;
            $p_inventario = $input['p_inventario'] ?? 0;
            $p_pedidos = $input['p_pedidos'] ?? 0;
            $p_validacion = $input['p_validacion'] ?? 0;
            $p_soporte = $input['p_soporte'] ?? 0;

            $success = $this->usuario->create(
            $email, $password, $nombre, $apellido, $rol,
            $p_producto, $p_inventario, $p_pedidos, $p_validacion, $p_soporte);

        }else if ($rol == 'cliente'){ //SI ES CLIENTE-------------
            
            $success = $this->usuario->create($email, $password, $nombre, $apellido, $rol,null ,null ,null ,null ,null);
            $usuario = $this->usuario->buscarUsuarioporMail($email); 

        }

        if ($success) {

            $this->creoCookie($usuario);
            $this->creoSession($usuario);
          
            ob_clean(); //LIMPIAR SI TRAE ALGO ANTES DEL JSON

            if($rol == "cliente") {
                http_response_code(201);
                return json_encode(["Mensaje" => "Cliente creado con éxito "]);
                //return;
            } else if ($rol == 'Administrador') {
                http_response_code(201);

                echo json_encode(['mensaje'=> 'Administrador Creado con exito']);
            }else if ($rol == 'gestor'){
                http_response_code(201);
                echo json_encode(['mensaje'=> 'Gestor Creado con exito']);
            }else{
                http_response_code(201);
                echo json_encode(['mensaje'=> 'Usuario creado con exito']);
            }       

        } else {
            http_response_code(500);
            echo json_encode(["Mensaje" => "Error al crear el usuario"]);
        }
    }

    public function update($id)
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre'], $input['email'], $input['password'])) {
            http_response_code(204);
            echo json_encode(["Mensaje" => "Faltan datos requeridos"]);
            return;
        }

        $email = trim($input['email']);
        $password = trim($input['password']);
        $nombre = trim($input['nombre']);
        $apellido = trim($input['apellido']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Email no válido"]);
            return;
        }

        $success = $this->usuario->update($email, $password, $nombre, $apellido);

        if ($success) {
            http_response_code(200);
            echo json_encode(["Mensaje" => "Usuario actualizado con éxito"]);
        } else {
            http_response_code(500);
            echo json_encode(["Mensaje" => "Error al actualizar el usuario"]);
        }
    }

    public function delete($email)
    {
        $success = $this->usuario->delete($email);

        if ($success) {
            echo json_encode(["Mensaje" => "Usuario eliminado con éxito"]);
        } else {
            http_response_code(500);
            echo json_encode(["Mensaje" => "Error al eliminar el usuario"]);
        }
    }


    public function iniciarSecion()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['email'], $input['password'])) {
            http_response_code(204);
            echo json_encode(["Mensaje" => "Falta email o Password"]);
            return;
        }

        $email = trim($input['email']);
        $password = trim($input['password']);
        $usuario = $this->usuario->buscarUsuarioporMail($email);

        //VERIFICO EXISTE USUARIO 
        if (!$usuario) {
            http_response_code(401);
            echo json_encode(["Mensaje" => "Usuario no encontrado"]);
            return;
        }

        //VERIFICO CLIENTE ACTIVO
        if (!$this->usuario->clienteActivo($usuario)) {
            http_response_code(400);
            echo json_encode(['Mensaje' => 'Cliente dado de baja']);
            return;
        }

        if ($usuario && isset($usuario["Contrasena"])) {
            $contra = $usuario["Contrasena"];
        }

        // Aquí va la verificación de la contraseña
        if (!password_verify($password, $contra)) {
            http_response_code(401);
            echo json_encode(["Mensaje" => "Contraseña incorrecta"]);
            return;
        }

        $this->creoCookie($usuario);
        $this->creoSession($usuario);

        http_response_code(200);
        echo json_encode(["Mensaje" => "Se Inicio sesion de manera exitosa"]);
    }

    public function RecuperarPassword()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['email'])) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Falta email"]);
            return;
        }

        $email = trim($input['email']);
        $usuario = $this->usuario->buscarUsuarioporMail($email);

        if (!$usuario) {
            http_response_code(401);
            echo json_encode(["Mensaje" => "email no registrado"]);
            return;
        }

        if ($this->usuario->enviomailverificado($email)) {
            http_response_code(200);
            echo json_encode(["Mensaje" => "Mail de verificacion enviado"]);
        } else {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Error al enviar el mail"]);
        }
    }


    public function CambioPassword()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['token']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Falta token o password"]);
            return;
        }

        $token = trim($input['token']);
        $nuevaPass = trim($input['password']);

        if ($this->usuario->AtualizoPassword($token, $nuevaPass)) {
            http_response_code(200);
            echo json_encode(['Mensaje' => 'Contrasenia Actualizada con exito']);
            return true;
        } else {
            http_response_code(400);
            echo json_encode(['Mensaje' => 'Contrasenia Actualizada con exito']);
            return false;
        }
    }



    public function VerificoToken()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $token = trim($input["token"]);
        $verifico = $this->usuario->verificoToken($token);

        if ($verifico == null) {
            http_response_code(400);
            echo json_encode(['valido' => false, 'Mensaje' => 'El token no es válido']);
            return false;
        } else {
            http_response_code(200);
            echo json_encode(['valido' => true, 'Mensaje' => 'El token es válido']);
            return true;
        }
    }

    public function todastuscompras($id)
    {


        $usr = $this->usuario->getUsuarioById($id);

        if ($usr == null) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Uusario no encontrado"]);
            return false;
        }

        $pedidos = $this->usuario->comprasRealizadas($usr["ID"]);

        if ($pedidos == null) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "El usuario no tiene compras"]);
            return false;
        } else {
            http_response_code(200);
            echo json_encode(["pedidos" => $pedidos]);
        }
    }


    public function cerrarsesion($id)
    {
        $usuario = $this->usuario->getUsuarioById($id);

        if ($usuario == null) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "El usuario no fue encontrado"]);
            return;
        }

        $cook = $this->borroCookie($usuario["ID"]);
        $sess = $this->borroSesion();

        if ($cook && $sess) {
            http_response_code(200);
            echo json_encode(['Mensaje' => 'Cookie eliminada con exito']);
        } else {
            http_response_code(400);
            echo json_encode(['Mensaje' => 'No se pudo borrar la cookie']);
        }
    }

    public function borroCookie(string $cookieid)
    {
        setcookie("session_ID", $cookieid, time() - 3600, '/');
        return true;
    }

    public function desactivacuenta($id)
    {
        $usuario = $this->usuario->getUsuarioById($id);

        if ($usuario == null) {
            http_response_code(400);
            echo json_encode(['Mensaje' => 'usuario no encontrado']);
            return;
        }

        $this->borroCookie($usuario['ID']);
        $sess = $this->borroSesion();
        $delete = $this->usuario->darBajaUsuario($usuario);
    }

    public function borroSesion()
    {
        session_start();       // 1. Reanuda la sesión actual del usuario
        $_SESSION = [];        // 2. Limpia todas las variables de la sesión

        // 3. Elimina la cookie de sesión PHP (PHPSESSID)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 3600,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        if (session_destroy()) {
            return true;
        }
        return false;
    }



    public function creoCookie($usuario)
    {
        // Verifico si la cookie 'session_ID' no existe todavía
        if (!isset($_COOKIE['session_ID'])) {
            // Crear cookie con configuración adecuada
            setcookie(
                'session_ID',
                $usuario['ID'],
                [
                    'expires' => time() + 3600,  // 1 hora de duración
                    'path' => '/',
                    'secure' => false,
                    'httponly' => false,
                    'samesite' => 'Lax'          // Para que pueda pasar entre dominios
                ]
            );

            // Nota: setcookie() solo envía la cookie en la respuesta HTTP,
            // no actualiza $_COOKIE en esta ejecución.
            // por eso lo seteo
            $_COOKIE['session_ID'] = $usuario['ID'];

            // Podés retornar un mensaje o true si querés indicar éxito
            return true;
        } else {
            // La cookie ya existe, podés retornar false o el valor si querés
            return false;
        }
    }

    public function creoSession($usuario)
    {
        // Iniciar o reanudar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya hay sesión para este usuario, no hago nada
        if (isset($_SESSION["session_ID"]) && $_SESSION["session_ID"] == $usuario["ID"]) {
            return false; // Sesión ya activa para este usuario
        }

        // Limpio la sesión por si hubiera datos de otro usuario
        $_SESSION = [];

        // Guardo datos de usuario en la sesión
        $_SESSION["session_ID"] = $usuario["ID"];
        $_SESSION["email"] = $usuario['Email'];

        return true; // Sesión creada o actualizada
    }

    public function cambiopassdesdeDetalles()
    {

        $input = json_decode(file_get_contents("php://input"), true);

        $email = trim($input["email"]);
        $passWord = trim($input["password"]);
        $nuevaPass = trim($input["nuevapass"]);


        $usr = $this->buscarUsuarioporMail($email);

         if (!$usr) {
            http_response_code(401);
            echo json_encode(["Mensaje" => "usuario no registrado"]);
            return;
        }

        if(!password_verify($passWord,$usr['Contrasena'])){
            http_response_code(401);
            echo json_encode(['Mensaje'=> 'Su Password actual no coincide']);
            return;
        }

        if ($nuevaPass == $passWord) {
            http_response_code(400);
            echo json_encode(["Mensaje"=> "La nueva Password y la anterior no pueden coinsidir"]);
            return;
        }

       $bol = $this->usuario->NuevaPass($usr, $nuevaPass);

        if (!$bol) {
            http_response_code(400);
            echo json_encode(["Mensaje"=> "Ocurrio un error al actualizar el password"]);
        }else{
            http_response_code(200);
            echo json_encode(["Mensaje"=>'Password cambiada exitosamente']);
        }

    }

    public function buscarUsuarioporMail($email)
    {

        return $this->usuario->buscarUsuarioporMail($email);

    }

    public function hashPasswords()
    {
        if ($this->usuario->hashPasswords()) {
            http_response_code(200);
            echo json_encode(["Mensaje" => "Contraseñas actualizadas con éxito"]);
            return;
        }
        http_response_code(500);
        echo json_encode(["Mensaje" => "Error al actualizar las contraseñas"]);
        return;
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

    public function crearGestor()
    {

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
    public function modificarGestor()
    {

        $data = json_decode(file_get_contents("php://input"), true);
        

        $campos = ['Email', 'p_producto', 'p_inventario', 'p_pedidos', 'p_validacion', 'p_soporte'];

        foreach ($campos as $campo) {

            if (!isset($data[$campo])) {

                http_response_code(400);
                echo json_encode(["error" => "Falta el campo '$campo'"]);
                return;

            }

        }

        $usuario = $this->buscarUsuarioporMail($data['Email']);

        //SACAR ID DEL USUARIO
        $id = $usuario['ID'];

        try {

            $resultado = $this->usuario->modificarGestor(

                $data['Email'],
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

    public function eliminarGestor()
    {

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