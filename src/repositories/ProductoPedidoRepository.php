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
        $sql = "
            SELECT 
                pp.ID_Producto,
                p.Nombre,
                p.URL_Imagen,
                pp.Cantidad,
                pp.Precio
            FROM 
                Producto_Pedido pp 
            INNER JOIN 
                Producto p ON pp.ID_Producto = p.ID
            WHERE ID_Pedido = $id_pedido
        ";
        $result = mysqli_query($this->conn, $sql);
        $productosPedidos = [];
        while ($row = $result->fetch_assoc()) {
            $productosPedidos[] = $row;
        }

        return $productosPedidos;
    }

}
?>