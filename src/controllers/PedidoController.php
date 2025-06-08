<?php
include_once __DIR__ . '/../../src/repositories/PedidoRepository.php';

class PedidoController
{
    private $pedido;

    public function __construct()
    {
        $this->pedido = new PedidoRepository();
    }

    public function getPedidos()
    {
        echo json_encode($this->pedido->getPedidos());
    }

    public function getPedidoById($id)
    {
        echo json_encode($this->pedido->getPedidoById($id));
    }

    public function getPedidoByCliente($id_cliente)
    {
        echo json_encode($this->pedido->getPedidoByCliente($id_cliente));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['ID_Cliente']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de cliente es requerido"]);
            return;
        }

        echo json_encode($this->pedido->create($data));
    }

    public function updateStatus($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data['Estado'] ?? null;
        if (!$status) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Estado es requerido"]);
            return;
        }
        if (!in_array($status, ['pendiente', 'pago', 'entregado', 'cancelado'])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Estado inválido"]);
            return;
        }
        echo json_encode($this->pedido->updateStatus($id, $status));
    }

    public function cancel($id)
    {
        echo json_encode($this->pedido->cancel($id));
    }
}
?>