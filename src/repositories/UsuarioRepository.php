<?php
include_once __DIR__ . '/../config/database.php';

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
        $stmt = $this->conn->prepare("SELECT * FROM Usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($email, $password, $nombre, $apellido)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO usuario (
            email, 
            contrasena, 
            nombre, 
            apellido
        ) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed, $nombre, $apellido);
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
}
?>