<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ .'/MailService.php';
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
        $sql = "SELECT * FROM usuario";
        $result = mysqli_query($this->conn, $sql);
        $usuarios = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function getUsuarioById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($email, $password, $nombre, $apellido)
    {

        $expiracionToken = "0000-00-00";
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuario (
            email, 
            contrasena, 
            nombre, 
            apellido,
            expiracion_token
            ) VALUES (?, ?, ?, ?, ?)");   
        $stmt->bind_param("sssss", $email, $hashed, $nombre, $apellido, $expiracionToken);
        return $stmt->execute();
    }

    public function update($email, $password, $nombre, $apellido)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuario SET  
            contrasena = ?, 
            nombre = ?, 
            apellido = ? 
            WHERE email = ?");
        $stmt->bind_param("ssi", $hashed, $nombre, $apellido, $email);
        return $stmt->execute();
    }

    public function delete($email)
    {
        $stmt = $this->conn->prepare("UPDATE usuario SET activo = 0 WHERE email = ?");
        $stmt->bind_param("i", $email);
        return $stmt->execute();
    }



        public function buscarUsuarioporMail($email)
    {
        $sql = "SELECT * FROM usuario WHERE email = ?";
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
    error_log("ENTRÉ A VERIFICADO");

    $mailer = new MailService();
    $usuario = $this->buscarUsuarioporMail($email);
    if (!$usuario) {
        return false;
    }
    // Acceso a array asociativo
    $nombre = $usuario['nombre'];

    $token = bin2hex(random_bytes(16));
    $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $this->conn->prepare("UPDATE usuario SET tokenrecuperacion = ?, expiracion_token= ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiracion, $email);
    if (!$stmt->execute()) {
        return false;
    };
    // Llamo al archivo MailSender
   $enviado =$mailer->enviarRecuperacion($email, $usuario['nombre'], $token);
    if ($enviado == true) {
         echo json_encode(["Correo enviado exitosamente"]);

        return true;
    } else {
        echo json_encode(["Correo no enviado exitosamente"]);
        return false;
    
    }

    
}


        
        

public function tokenexiste($email, $token) {

    $sql = "SELECT expiracion_token FROM usuario WHERE email = :email AND tokenrecuperacion = :token";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':token', $token);
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


public function comparoTokens($email, $token) {
    // SQL usando placeholders (?) en lugar de :email/:token
    $sql = "
        SELECT tokenrecuperacion, expiracion_token
        FROM usuario
        WHERE email = ?
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en prepare(): " . $this->conn->error);
    }

    // Enlazar el email a la consulta
    $stmt->bind_param("s", $email);

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



public function AtualizoPassword($email, $token, $nuevaPassword) {

    if (!$this->comparoTokens($email, $token)) {
        return false;
    }

    // Hashear la nueva contraseña
    $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE usuario SET password = :password, token = NULL WHERE email = :email AND token = :token";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':password', $hash);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':token', $token);

    if ($stmt->execute()) {
        echo json_encode(["PASSWORD CAMBIADA EXITOSAMENTE"]);
    return true;
} else {
    // Para poder lo que paso
    error_log(print_r($stmt->errorInfo(), true));
    return false;
}

    }



}
?>