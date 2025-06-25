<?php
include_once __DIR__ . '/../../src/repositories/PedidoRepository.php';
include_once __DIR__ . '/../repositories/MailService.php';
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

    public function sendEmailConfirmation()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$this->pedido->getPedidoById($data['ID_Pedido'])) {
            http_response_code(404);
            echo json_encode(["mensaje" => "El pedido no existe o no se encontro."]);
        }

        try {
            $sentMail = $this->pedido->sendEmailOrderConfirmation($data);
            if (!$sentMail) {
                http_response_code(400);
                echo json_encode(["error" => "Error al enviar el correo."]);
            }

            echo json_encode(["mensaje" => "Correo enviado exitosamente."]);
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function downloadOrderPDF()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$this->pedido->getPedidoById($data['ID_Pedido'])) {
            http_response_code(404);
            echo json_encode(["mensaje" => "El pedido no existe o no se encontro."]);
        }

        // Generar PDF en memoria
        $pdfContent = $this->pedido->generateOrderPDFContent($data);

        // Configurar headers para descarga
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="pedido_' . $data['ID_Pedido'] . '.pdf"');
        echo $pdfContent;
    }


}
?>