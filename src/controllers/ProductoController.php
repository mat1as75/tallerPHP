<?php
require_once './models/Producto.php';

class ProductoController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    public function getProductos()
    {
        echo json_encode($this->producto->getProductos());
    }

    public function getProductoById($id)
    {
        echo json_encode($this->producto->getProductoById($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->producto->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->producto->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->producto->delete($id));
    }
}

?>