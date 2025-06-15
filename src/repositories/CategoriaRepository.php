<?php
include_once __DIR__ . '/../config/database.php';

class CategoriaRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($nombre, $descripcion)
    {
        $sql = "INSERT INTO Categoria (nombre, descripcion) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $descripcion);

        if ($stmt->execute()) {
            return [
                'mensaje' => 'Categoría creada correctamente',
                'id' => $this->conn->insert_id
            ];
        }
        return false;
    }

    public function update($id, $nombre, $descripcion)
    {
        $sql = "UPDATE Categoria SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);

        if ($stmt->execute()) {
            return ['mensaje' => 'Categoría actualizada correctamente'];
        }
        return false;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Categoria WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ['mensaje' => 'Categoría eliminada correctamente'];
        }
        return false;
    }

    public function getCategorias()
    {
        $sql = "SELECT * FROM Categoria";
        $result = $this->conn->query($sql);

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        return $categorias;
    }

    public function getCategoriaById($id)
    {
        $sql = "SELECT * FROM Categoria WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // devuelve array o null
    }
}
?>
