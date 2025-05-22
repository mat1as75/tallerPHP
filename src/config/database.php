<?php
class Database
{
    private $host = "localhost";
    private $db_name = "webpagedb";
    private $username = "root";
    private $password = "";
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