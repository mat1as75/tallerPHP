<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/MailService.php';
class UsuarioRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM Usuario";
        $result = mysqli_query($this->conn, $sql);
        $usuarios = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function getUsuarioById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Usuario WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($email, $password, $nombre, $apellido, $rol)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $activo = 1;

        $stmt = $this->conn->prepare("INSERT INTO Usuario (
        Email, Nombre, Apellido, Contrasena, Activo, Rol
        ) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssss", $email, $nombre, $apellido, $hashed, $activo, $rol);
        $r = $stmt->execute();

        // Verificamos si se insertó bien el usuario
        if (!$r) {
            echo json_encode("Error al insertar el usuario.");
            return false;
        }

        $user = $this->buscarUsuarioporMail($email);
        if ($user && isset($user["ID"])) {
            $id = $user["ID"];
        }

        // Según el rol, insertamos en la tabla correspondiente
        if (strtolower($rol) === "cliente") {
            $ok = $this->ConexionconCliente($id);
            if ($ok) {
                return true;
            } else {
                return false;
            }
        }//ACA SEGUIRIA PARA ADMINISTRADOR
    }


    public function darBajaUsuario($usuario)
    {
        $valor = 0;
        $stmt = $this->conn->prepare("UPDATE Usuario SET Activo = ? WHERE ID = ?");
        $stmt->bind_param("ii", $valor, $usuario["ID"]);
        $r = $stmt->execute();
        return $r;
    }


    public function clienteActivo($usuario)
    {

        $stmt = $this->conn->prepare("SELECT Activo FROM Usuario WHERE ID = ?");
        $stmt->bind_param("i", $usuario["ID"]);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }


        $stmt->store_result();

        $activo = null;
        $stmt->bind_result($activo);

        if ($stmt->fetch() && $activo == 1) {
            $stmt->close();
            return true;
        }

        $stmt->close();
        return false;
    }

    public function ConexionconCliente($id)
    {
        $stmt = $this->conn->prepare("INSERT INTO Cliente (ID  ,tokenrecuperacion) VALUES (?, 0)");

        $stmt->bind_param("s", $id);
        $success = $stmt->execute();

        return $success; // true si fue exitoso, false si falló
    }

    public function update($email, $password, $nombre, $apellido)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE Usuario SET  
            contrasena = ?, 
            nombre = ?, 
            apellido = ? 
            WHERE email = ?");
        $stmt->bind_param("ssi", $hashed, $nombre, $apellido, $email);
        return $stmt->execute();
    }

    public function delete($email)
    {
        $stmt = $this->conn->prepare("UPDATE Usuario SET activo = 0 WHERE email = ?");
        $stmt->bind_param("i", $email);
        return $stmt->execute();
    }

    public function buscarUsuarioporMail($email)
    {
        $sql = "SELECT * FROM Usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return false; // No encontró usuario
        }
    }



    public function enviomailverificado($email)
    {
        $mailer = new MailService();
        $user = $this->buscarUsuarioporMail($email);
        if ($user && isset($user["ID"])) {
            $id = $user["ID"];
        }

        if ($user && isset($user["Nombre"])) {
            $nombre = $user["Nombre"];
        }

        $token = bin2hex(random_bytes(16));
        //  $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $this->conn->prepare("UPDATE Cliente SET tokenrecuperacion = ? WHERE id = ?");
        $stmt->bind_param("ss", $token, $id);

        if (!$stmt->execute()) {
            return false;
        }

        // Llamo al archivo MailSender
        $mensaje1 = "Recuperación de contraseña";
        $mensaje2 = "Hola $nombre,\n\nEste es el token para recuperar su Password $token\n\nSaludos,\nMNJ Tecno";

        $enviado = $mailer->EnvioMail($email, $nombre, $token, $mensaje1, $mensaje2, false);
        if ($enviado == true) {
            return true;
        } else {
            return false;
        }
    }



    public function verificoToken($token)
    {
        $stm = $this->conn->prepare("SELECT * FROM Cliente WHERE tokenrecuperacion = ? ");
        $stm->bind_param("s", $token);
        $stm->execute();
        $result = $stm->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return false; // No encontró usuario
        }
    }


    public function tokenexiste($email, $token)
    {
        $usuario = $this->buscarUsuarioporMail($email);
        $id = $usuario["ID"];

        $sql = "SELECT expiracion_token FROM Cliente WHERE id = ? AND tokenrecuperacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $id, $token);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $expiracion = $resultado['expiracion_token'];
            $ahora = date('Y-m-d H:i:s');

            if ($expiracion >= $ahora) {
                return true; // Token válido y no expirado
            } else {
                return false; // Token expirado
            }
        }

        return false; // Token o email incorrectos
    }


    public function comparoTokens($email, $token)
    {
        $usuario = $this->buscarUsuarioporMail($email);
        $id = $usuario['ID'];

        // SQL usando placeholders (?) en lugar de :email/:token
        $sql = "SELECT tokenrecuperacion, expiracion_token
                FROM Cliente
                WHERE id = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare(): " . $this->conn->error);
        }

        // Enlazar el email a la consulta
        $stmt->bind_param("s", $id);

        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res) {
            $stmt->close();
            return false;
        }

        $row = $res->fetch_assoc();
        $stmt->close();

        // Si no hay fila, email no existe
        if (!$row) {
            return false;
        }

        // Comparar el token
        if (!hash_equals($row['tokenrecuperacion'], $token)) {
            return false;
        }

        // Verificar expiración
        $expiracion = $row['reset_token_expiracion'];
        $ahora = date('Y-m-d H:i:s');

        if ($expiracion < $ahora) {
            return false;
        }

        // Si todo es correcto
        return true;
    }

    public function AtualizoPassword($token, $nuevaPassword)
    {
        $tokenusr = $this->verificoToken($token);
        $id = $tokenusr['ID'];

        //Despues de obtener el usuario por medio del token
        $usr = $this->getUsuarioById($id);

        // Hashear la nueva contraseña
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        // Guarda la nueva contrasenia
        $sql = "UPDATE Usuario SET Contrasena = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $hash, $id);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            echo json_encode(["PASSWORD CAMBIADA EXITOSAMENTE"]);
            return true;
        } else {
            // Para poder saber lo que paso en caso de error
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    public function comprasRealizadas($id)
    {
        $sql = "SELECT * FROM Pedido WHERE ID_Cliente = ?";
        $stm = $this->conn->prepare($sql);
        $stm->bind_param("i", $id);
        $stm->execute();

        $result = $stm->get_result();
        $compras = [];

        while ($row = $result->fetch_assoc()) {
            $compras[] = $row;
        }

        return $compras;
    }

    public function NuevaPass($usuario, $nuevapass)
    {
        // Hashear la nueva contraseña
        $hash = password_hash($nuevapass, PASSWORD_DEFAULT);
        // Guarda la nueva contrasenia
        $sql = "UPDATE Usuario SET Contrasena = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $hash, $usuario['ID']);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            return true;
        } else {
            // Para poder saber lo que paso en caso de error
            error_log(print_r($stmt->errorInfo(), true));
            return false;
        }
    }
      
      


    //FUNCIONES ADMINISTRADOR-----------------------------

    //CREAR GESTOR
    public function crearGestor($id, $p_producto, $p_inventario, $p_pedidos, $p_validacion, $p_soporte) {
        $stmt = $this->conn->prepare("
            INSERT INTO Gestor (ID, P_Producto, P_Inventario, P_Pedidos, P_Validacion, P_Soporte)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("iiiiii", $id, $p_producto, $p_inventario, $p_pedidos, $p_validacion, $p_soporte);
        $resultado = $stmt->execute();

        if (!$resultado) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return $resultado;
    }

    //MODIFICAR GESTOR
    public function modificarGestor($id, $p_producto, $p_inventario, $p_pedidos, $p_validacion, $p_soporte) {
        $stmt = $this->conn->prepare("
            UPDATE Gestor
            SET P_Producto = ?, P_Inventario = ?, P_Pedidos = ?, P_Validacion = ?, P_Soporte = ?
            WHERE ID = ?
        ");

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("iiiiii", $p_producto, $p_inventario, $p_pedidos, $p_validacion, $p_soporte, $id);
        $resultado = $stmt->execute();

        if (!$resultado) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    //ELIMINAR DE GESTOR/QUITAR PERMISOS A USUARIO COMO GESTOR NO BORRARLO

    public function eliminarGestor($id) {
        $stmt = $this->conn->prepare("DELETE FROM Gestor WHERE ID = ?");
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();

        if (!$resultado) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    //BUSCAR USUARIOS CON FILTROS FUNCION ADMIN
    public function buscarUsuarios($filtros) {
        $query = "SELECT * FROM Usuario WHERE 1=1";
        $tipos = "";
        $params = [];

        if (!empty($filtros['ID'])) {
            $query .= " AND ID = ?";
            $tipos .= "i";
            $params[] = $filtros['ID'];
        }
        if (!empty($filtros['Nombre'])) {
            $query .= " AND Nombre = ?";
            $tipos .= "s";
            $params[] = $filtros['Nombre'];
        }
        if (!empty($filtros['Apellido'])) {
            $query .= " AND Apellido = ?";
            $tipos .= "s";
            $params[] = $filtros['Apellido'];
        }
        if (!empty($filtros['Email'])) {
            $query .= " AND Email = ?";
            $tipos .= "s";
            $params[] = $filtros['Email'];
        }
        if (!empty($filtros['Activo'])) {
            $query .= " AND Activo = ?";
            $tipos .= "i";
            $params[] = $filtros['Activo'];
        }
        if (!empty($filtros['Rol'])) {
            $query .= " AND Rol = ?";
            $tipos .= "s";
            $params[] = $filtros['Rol'];
        }

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        if (!$resultado) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $usuarios = [];
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function hashPasswords()
    {
        $usuarios = $this->getUsuarios();
        foreach ($usuarios as $usuario) {
            if (!password_get_info($usuario['Contrasena'])['algo']) {
                $hashed = password_hash($usuario['Contrasena'], PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare("UPDATE Usuario SET Contrasena = ? WHERE ID = ?");
                $stmt->bind_param("si", $hashed, $usuario['ID']);
                $stmt->execute();
            }
        }
        return true;
    }

}

?>