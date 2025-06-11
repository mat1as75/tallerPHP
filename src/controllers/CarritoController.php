<?php
include_once __DIR__ . '/../../src/repositories/CarritoRepository.php';

class CarritoController
{
    private $carrito;

    public function __construct()
    {
        $this->carrito = new CarritoRepository();
    }

    // Obtener el carrito de un usuario
    public function getCarrito($id_usuario)
    {
        $carrito = $this->carrito->getCarrito($id_usuario);
        echo json_encode($carrito);
    }

    // Agregar un producto al carrito
    public function addProducto()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['id_usuario'] || !$data['id_producto'] || !$data['cantidad']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Usuario, Producto y Cantidad es requerido"]);
            return;
        }

        $resultado = $this->carrito->addProducto(
            $data['id_usuario'],
            $data['id_producto'],
            $data['cantidad']
        );

        echo json_encode(["success" => $resultado]);
    }

    // Eliminar un producto del carrito
    public function removeProducto()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['id_usuario'] || !$data['id_producto']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Usuario y Producto es requerido"]);
            return;
        }

        $resultado = $this->carrito->removeProducto(
            $data['id_usuario'],
            $data['id_producto']
        );

        echo json_encode(["success" => $resultado]);
    }

    // Vaciar el carrito 
    public function clearCarrito()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['id_usuario']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Usuario es requerido"]);
            return;
        }

        $resultado = $this->carrito->clearCarrito($data['id_usuario']);

        echo json_encode(["success" => $resultado]);
    }

    // Actualizar la cantidad de un producto en el carrito
    public function updateCantidad()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['id_usuario'] || !$data['id_producto'] || !$data['cantidad']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Usuario, Producto y Cantidad es requerido"]);
            return;
        }

        $resultado = $this->carrito->updateCantidad(
            $data['id_usuario'],
            $data['id_producto'],
            $data['cantidad']
        );

        echo json_encode(["success" => $resultado]);
    }
}
?>
