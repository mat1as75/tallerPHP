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
}

?>