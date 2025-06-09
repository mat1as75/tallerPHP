<?php
include_once __DIR__ . '/../config/database.php';

class MarcaRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getMarcas()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Marca");
        $stmt->execute();
        $result = $stmt->get_result();
        $marcas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $marcas;
    }

    public function create($nombre)
    {
        $stmt = $this->conn->prepare("INSERT INTO Marca (Nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return ['error' => 'Error al crear la marca'];
        }
        $stmt->close();
        return ['mensaje' => 'Marca creada correctamente'];
    }
}
