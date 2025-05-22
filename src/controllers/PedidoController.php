<?php
require_once './models/Pedido.php';

class PedidoController
{
    private $pedido;

    public function __construct()
    {
        $this->pedido = new Pedido();
    }

    public function getPedidos()
    {
        echo json_encode($this->pedido->getPedidos());
    }

    public function getPedidoById($id)
    {
        echo json_encode($this->pedido->getPedidoById($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->pedido->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->pedido->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->pedido->delete($id));
    }
}
?>