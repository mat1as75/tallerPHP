<?php
include_once __DIR__ . '/../../src/models/Carrito.php';

class CarritoController
{
    private $carrito;

    public function __construct()
    {
        $this->carrito = new Carrito();
    }

    public function getCarritos()
    {
        echo json_encode($this->carrito->getCarritos());
    }

    public function getCarritoById($id)
    {
        echo json_encode($this->carrito->getCarritoById($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->carrito->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->carrito->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->carrito->delete($id));
    }
}
?>