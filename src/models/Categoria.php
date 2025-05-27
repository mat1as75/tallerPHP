<?php
include_once __DIR__ . '/../config/database.php';

class Categoria
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getCategorias()
    {
        $sql = "SELECT * FROM categoria";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriaById($id)
    {
        $sql = "SELECT * FROM categoria WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO categoria (
            nombre, 
            descripcion 
            ) VALUES (?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['nombre'],
                $data['descripcion']
            ]
        );
        return ['mensaje' => 'Categoria creada'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE categoria SET 
            nombre = ?, 
            descripcion = ?
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['nombre'],
                $data['descripcion'],
                $id
            ]
        );
        return ['mensaje' => 'Categoria actualizada'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM categoria WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Cateogria eliminada'];
    }
}
?>