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
        if (
            !$this->validarCampos($data, [
                'Nombre',
                'Descripcion',
                'Precio',
                'Stock',
                'ID_Marca',
                'URL_Imagen',
                'ID_Categoria'
            ])
        )
            return;
        $nombreArchivo = $data['URL_Imagen'];
        $rutaImagenCompleta = 'http://localhost/PHP/tallerPHP/assets/images/' . $nombreArchivo;
        $resultado = $this->producto->create(
            $data['Nombre'],
            $data['Descripcion'],
            $data['Precio'],
            $data['Stock'],
            $data['ID_Marca'],
            $rutaImagenCompleta,
            $data['ID_Categoria']
        );
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el producto"]);
            return;
        }
        echo json_encode($resultado);
    }

    public function update($id)
    {
        if (!$this->validarId($id))
            return;

        $data = json_decode(file_get_contents("php://input"), true);
        if (
            !$this->validarCampos($data, [
                'Nombre',
                'Descripcion',
                'Precio',
                'Stock',
                'ID_Marca',
                'URL_Imagen',
                'ID_Categoria'
            ])
        )
            return;

        $resultado = $this->producto->update(
            $id,
            $data['Nombre'],
            $data['Descripcion'],
            $data['Precio'],
            $data['Stock'],
            $data['ID_Marca'],
            $data['URL_Imagen'],
            $data['ID_Categoria']
        );

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
    $producto = $this->producto->getProductoById($id);

    if (!$producto) {
        http_response_code(404);
        echo json_encode(["error" => "Producto no encontrado"]);
        return;
    }

    $urlImagen = $producto['URL_Imagen'] ?? '';
    if ($urlImagen && str_starts_with($urlImagen, 'http')) {
        $nombreArchivo = basename($urlImagen);
        $rutaImagen = __DIR__ . '/../../assets/images/' . $nombreArchivo;
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen); // Intentar borrar la imagen física
        }
    }

    $resultado = $this->producto->delete($id);
    if (!$resultado) {
        http_response_code(500);
        echo json_encode(["error" => "Error al eliminar el producto"]);
        return;
    }

    echo json_encode(["mensaje" => "Producto e imagen (si existía) eliminados correctamente"]);
}


    public function getProductos()
    {
        $filtros = $_GET;
        echo json_encode($this->producto->getProductos($filtros));
    }

    public function getProductoById($id)
    {
        if (!$this->validarId($id))
            return;

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
        if (!$this->validarId($id_categoria))
            return;
        $result = $this->producto->getProductosByCategoria($id_categoria);
        echo json_encode($result);
    }

    public function getProductosByMarca($id_marca)
    {
        if (!$this->validarId($id_marca))
            return;
        $result = $this->producto->getProductosByMarca($id_marca);
        echo json_encode($result);
    }

    public function updateStock($id)
    {
        if (!$this->validarId($id))
            return;

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

public function uploadImage()
{
// Verifica que se haya enviado un archivo
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    // Recupera la información del archivo subido
    $tempName = $_FILES['imagen']['tmp_name'];  // Ruta temporal del archivo
    $newFileName = uniqid() . '_' . $_FILES['imagen']['name'];  // Nombre único para el archivo

    // Directorio de destino para guardar la imagen
    $uploadDir = '/opt/lampp/htdocs/PHP/tallerPHP/assets/images/';
    $uploadFile = $uploadDir . $newFileName;

    // Mueve el archivo a la carpeta de destino
    if (move_uploaded_file($tempName, $uploadFile)) {
        echo json_encode(['success' => 'Imagen subida con éxito', 'file' => $newFileName]);
    } else {
        echo json_encode(['error' => 'Error al mover el archivo']);
    }
} else {
    echo json_encode(['error' => 'No se recibió archivo o hubo un error']);
}


}



}

?>