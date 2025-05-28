<?php
include_once __DIR__ . '/../config/database.php';

class ValoracionRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getValoraciones()
    {
        $sql = "SELECT * FROM valoracion";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValoracionesByIdCliente($id)
    {
        $sql = "SELECT * FROM valoracion WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getValoracionesByIdProducto($id)
    {
        $sql = "SELECT * FROM valoracion WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO valoracion (
            id_usuario, 
            id_producto, 
            clasificacion, 
            comentario, 
            ) VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['id_usuario'],
                $data['id_producto'],
                $data['clasificacion'],
                $data['comentario'],
            ]
        );
        return ['mensaje' => 'Valoracion creada'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE valoracion SET 
            clasificacion = ?, 
            comentario = ?,   
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['clasificacion'],
                $data['comentario'],
                $id
            ]
        );
        return ['mensaje' => 'Valoracion actualizada'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM valoracion WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Valoracion eliminada'];
    }
}
?>