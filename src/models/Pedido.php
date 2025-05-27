<?php
include_once __DIR__ . '/../config/database.php';

class Pedido
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getPedidos()
    {
        $sql = "SELECT * FROM pedido";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPedidoById($id)
    {
        $sql = "SELECT * FROM pedido WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO pedido (
            id_usuario, 
            total, 
            estado, 
            ) VALUES (?, ?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['id_usuario'],
                $data['total'],
                $data['estado'],
            ]
        );
        return ['mensaje' => 'Pedido creado'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE pedido SET 
            estado = ?,  
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['estado'],
                $id
            ]
        );
        return ['mensaje' => 'Pedido actualizado'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM pedido WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Pedido eliminado'];
    }
}
?>