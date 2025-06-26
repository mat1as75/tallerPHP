<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Credenciales para entorno local
            $this->host = "127.0.0.1";
            $this->db_name = "tallerphpdb";
            $this->username = "admin";
        } else {
            // Credenciales para entorno de producción
            $this->host = "localhost";
            $this->db_name = "hphp_equipo2";
            $this->username = "hphp_equipo2";
        }
        $this->password = "AdminPass123";
    }

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

            if (!$this->conn)
                throw new Exception(mysqli_connect_error());

            if (!mysqli_set_charset($this->conn, "utf8mb4"))
                throw new Exception("Error configurando charset utf8mb4: " . mysqli_error($this->conn));

        } catch (Exception $e) {
            echo json_encode(["error" => "Conexion fallida: " . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
?>