<?php
include_once __DIR__ . '/../../src/repositories/CarritoRepository.php';

class CarritoController
{
    private $carrito;

    public function __construct()
    {
        $this->carrito = new CarritoRepository();
    }

    // Obtener el carrito de un cliente
    public function getCarrito($ID_Cliente)
    {
        $carrito = $this->carrito->getCarrito($ID_Cliente);
        echo json_encode($carrito);
    }

    // Obtener el carrito detallado de un cliente
    public function getCarritoDetallado($ID_Cliente)
    {
        $carrito = $this->carrito->getCarritoDetallado($ID_Cliente);
        echo json_encode($carrito);
    }

    // Obtener la cantidad de productos que tiene un carrito de cliente
    public function getQuantityProductsCart($ID_Cliente)
    {
        if (!$ID_Cliente) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Cliente es requerido"]);
            return;
        }

        $response = $this->carrito->getCountProducts($ID_Cliente);
        echo json_encode($response);
    }


    // Agregar un producto al carrito
    public function addProducto()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['ID_Cliente'] || !$data['ID_Producto'] || !$data['Cantidad']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Cliente, Producto y Cantidad es requerido"]);
            return;
        }

        $resultado = $this->carrito->addProducto(
            $data['ID_Cliente'],
            $data['ID_Producto'],
            $data['Cantidad']
        );

        echo json_encode(["success" => $resultado]);
    }

    // Eliminar un producto del carrito
    public function removeProducto()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['ID_Cliente'] || !$data['ID_Producto']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Cliente y Producto es requerido"]);
            return;
        }

        $resultado = $this->carrito->removeProducto(
            $data['ID_Cliente'],
            $data['ID_Producto']
        );

        echo json_encode(["success" => $resultado]);
    }

    // Vaciar el carrito 
    public function clearCarrito()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['ID_Cliente']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Cliente es requerido"]);
            return;
        }

        $resultado = $this->carrito->clearCarrito($data['ID_Cliente']);

        echo json_encode(["success" => $resultado]);
    }

    // Actualizar la cantidad de un producto en el carrito
    public function updateCantidad()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data['ID_Cliente'] || !$data['ID_Producto'] || !$data['Cantidad']) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de Cliente, Producto y Cantidad es requerido"]);
            return;
        }

        $resultado = $this->carrito->updateCantidad(
            $data['ID_Cliente'],
            $data['ID_Producto'],
            $data['Cantidad']
        );

        echo json_encode(["success" => $resultado]);
    }
}
?>