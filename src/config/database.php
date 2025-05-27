<?php
class Database
{
    private $host = "127.0.0.1";
    private $db_name = "tallerphpdb";
    private $username = "admin";
    private $password = "AdminPass123";
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = mysqli_connect(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );
            mysqli_select_db(
                $this->conn,
                $this->db_name
            );
        } catch (Exception $e) {
            echo json_encode(["error" => "Conexion fallida: " . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
?>