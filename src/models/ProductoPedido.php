<?php
include_once __DIR__ . '/../config/database.php';

class ProductoPedido
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // public function getProductosPedidos()
    // {
    //     $sql = "SELECT * FROM producto_pedido";
    //     $stmt = $this->conn->query($sql);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function getProductoPedidoByIdPedido($id)
    // {
    //     $sql = "SELECT * FROM producto_pedido WHERE id_pedido = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    public function create($data)
    {
        $sql = "INSERT INTO producto_pedido (
            id_producto, 
            cantidad, 
            precio, 
            ) VALUES (?, ?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['id_producto'],
                $data['cantidad'],
                $data['precio']
            ]
        );
        return ['mensaje' => 'ProductoPedido creado'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE producto_pedido SET 
            id_producto = ?, 
            cantidad = ?, 
            precio = ?, 
            WHERE id_pedido = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['id_producto'],
                $data['cantidad'],
                $data['precio'],
                $id
            ]
        );
        return ['mensaje' => 'ProductoPedido actualizado'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM producto_pedido WHERE id_pedido = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'ProductoPedido eliminado'];
    }
}
?>