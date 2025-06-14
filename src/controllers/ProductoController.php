<?php
include_once __DIR__ . '/../../src/repositories/ProductoRepository.php';

class ProductoController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new ProductoRepository();
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validarCampos($data, ['Nombre', 'Descripcion', 'Precio',
        'Stock', 'Marca', 'URL_Imagen', 'ID_Categoria'])) return;

        $resultado = $this->producto->create($data['Nombre'],
        $data['Descripcion'], $data['Precio'], $data['Stock'],
        $data['Marca'], $data['URL_Imagen'], $data['Categoria']);
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el producto"]);
            return;
        }
        echo json_encode($resultado);
    }

    public function update($id)
    {
        if (!$this->validarId($id)) return;

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validarCampos($data, ['Nombre', 'Descripcion', 'Precio',
        'Stock', 'Marca', 'URL_Imagen', 'ID_Categoria'])) return;

        $resultado = $this->producto->update($id, $data['Nombre'],
        $data['Descripcion'], $data['Precio'], $data['Stock'],
        $data['Marca'], $data['URL_Imagen'], $data['ID_Categoria']);

        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el producto"]);
            return;
        }

        echo json_encode($resultado);
    }

    public function delete($id)
    {
        if (!$this->validarId($id)) return;

        $resultado = $this->producto->delete($id);
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el producto"]);
            return;
        }

        echo json_encode(["mensaje" => "Producto eliminado correctamente"]);
    }

    public function getProductos()
    {
        $filtros = $_GET;
        echo json_encode($this->producto->getProductos($filtros));
    }

    public function getProductoById($id)
    {
        if (!$this->validarId($id)) return;

        $producto = $this->producto->getProductoById($id);
        if (!$producto) {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
            return;
        }
        echo json_encode($producto);
    }

    public function getProductosByCategoria($id_categoria)
    {
        if (!$this->validarId($id_categoria)) return;
        $result = $this->producto->getProductosByCategoria($id_categoria);
        echo json_encode($result);
    }

    public function getProductosByMarca($id_marca)
    {
        if (!$this->validarId($id_marca)) return;
        $result = $this->producto->getProductosByMarca($id_marca);
        echo json_encode($result);
    }

    public function updateStock($id)
    {
        if (!$this->validarId($id)) return;

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nuevo_stock']) || !is_numeric($data['nuevo_stock'])) {
            http_response_code(400);
            echo json_encode(["error" => "Campo 'nuevo_stock' inválido o faltante"]);
            return;
        }
        $resultado = $this->producto->updateStock($id, $data['nuevo_stock']);
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar stock"]);
            return;
        }

        echo json_encode($resultado);
    }

    private function validarCampos(array $data, array $requeridos)
    {
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(["error" => "JSON inválido"]);
            return false;
        }
        foreach ($requeridos as $campo) {
            if (!isset($data[$campo])) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el campo: $campo"]);
                return false;
            }
        }
        return true;
    }

    private function validarId($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID inválida"]);
            return false;
        }
        return true;
    }

}

?>