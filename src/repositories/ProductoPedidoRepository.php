<?php
include_once __DIR__ . '/../config/database.php';

class ProductoPedidoRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getProductoPedidoByIdPedido($id_pedido)
    {
        $sql = "SELECT * FROM Producto_Pedido WHERE ID_Pedido = $id_pedido";
        $result = mysqli_query($this->conn, $sql);
        $productosPedidos = [];
        while ($row = $result->fetch_assoc()) {
            $productosPedidos[] = $row;
        }

        return $productosPedidos;
    }

    public function create($id_pedido, $id_producto, $cantidad, $precio)
    {
        if ($this->checkPedidoProducto($id_pedido, $id_producto)) {
            return ['error' => 'El producto ya está agregado al pedido'];
        }
        $sql = "INSERT INTO Producto_Pedido (ID_Pedido, ID_Producto, Cantidad, Precio) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_pedido, $id_producto, $cantidad, $precio]);
        return ['mensaje' => 'Producto ' . $id_producto . ' agregado a Pedido ' . $id_pedido];
    }

    public function checkPedidoProducto($id_pedido, $id_producto)
    {
        $sql = "SELECT * FROM Producto_Pedido WHERE ID_Pedido = $id_pedido AND ID_Producto = $id_producto";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_num_rows($result) > 0;
    }

}
?>