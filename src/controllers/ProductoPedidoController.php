<?php
require_once './models/ProductoPedido.php';

class ProductoPedidoController
{
    private $productoPedido;

    public function __construct()
    {
        $this->productoPedido = new ProductoPedido();
    }

    public function getProductosPedidos()
    {
        echo json_encode($this->productoPedido->getProductosPedidos());
    }

    public function getProductoPedidoById($id)
    {
        echo json_encode($this->productoPedido->getProductoPedidoByIdPedido($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->productoPedido->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->productoPedido->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->productoPedido->delete($id));
    }
}

?>