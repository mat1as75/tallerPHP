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
            if($usr["Activo"] === "0") {
                http_response_code(400);
                echo json_encode(["Mensaje"=> "El usuario esta dado de baja, debe mandar un email"]);
                return;
            }
            http_response_code(400);
            echo json_encode(["Mensaje"=> "Usuario ya registrado"]);
            return;
        }

        $success = $this->usuario->create($email, $password, $nombre, $apellido, $rol);
        $usuario = $this->usuario->buscarUsuarioporMail($email);

        if ($success == true) {
            $this->creoCookie($usuario);
            $this->creoSession($usuario);

            if ($rol == "cliente") {
                http_response_code(201);
                return json_encode(["Mensaje" => "Cliente creado con éxito "]);
                //return;
            } else if ($rol == 'Administrador') {
                http_response_code(201);
                echo json_encode(['Mensaje' => 'Administrador Creado con exito']);
            } else {
                http_response_code(201);
                echo json_encode(['Mensaje' => 'Usuario creado con exito']);
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

    public function cambiopassdesdeDetalles(){

         $input = json_decode(file_get_contents("php://input"), true);



        if (!isset($input['email']) || !isset($input['password']) || !isset($input['nuevapass'])) {
            http_response_code(400);
            echo json_encode(["Mensaje" => "Falta algun dato"]);
            return;
        }

        $email  = $input["email"];
        $password = $input["password"];
        $nuevapass = $input["nuevapass"];


        $usuario = $this->usuario->buscarUsuarioporMail($email);

        if ($usuario==null){
            http_response_code(400);
            echo json_encode(["Mensaje"=> "Usuario no encontrado"]);
            return;
        }

        if (!password_verify($password, $usuario["Contrasena"])) {
        http_response_code(400);
        echo json_encode(["Mensaje" => "El password actual no coincide"]);
        return;
        }


        $check = $this->usuario->NuevaPass($usuario,$nuevapass);

        if ($check){
            http_response_code(200);
            echo json_encode(["Mensaje"=> "Password Actualizada"]);
            return;
        }else{
            http_response_code(400);
            echo json_encode(["Mensaje"=> "No se pudo actualizar la Password"]);

        }



    }





}

?>