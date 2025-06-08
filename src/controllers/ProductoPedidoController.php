<?php
include_once __DIR__ . '/../../src/repositories/ProductoPedidoRepository.php';

class ProductoPedidoController
{
    private $productoPedido;

    public function __construct()
    {
        $this->productoPedido = new ProductoPedidoRepository();
    }

    public function getProductoPedidoByIdPedido($id_pedido)
    {
        echo json_encode($this->productoPedido->getProductoPedidoByIdPedido($id_pedido));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $id_pedido = $data['ID_Pedido'] ?? null;
        $id_producto = $data['ID_Producto'] ?? null;
        $cantidad = $data['Cantidad'] ?? 1;
        $precio = $data['Precio'] ?? 0;

        if (!$id_pedido || !$id_producto) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de pedido y producto son requeridos"]);
            return;
        }

        echo json_encode($this->productoPedido->create($id_pedido, $id_producto, $cantidad, $precio));
    }
}

?>