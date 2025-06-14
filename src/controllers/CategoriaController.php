<?php
include_once __DIR__ . '/../../src/repositories/CategoriaRepository.php';

class CategoriaController
{
    private $categoria;

    public function __construct()
    {
        $this->categoria = new CategoriaRepository();
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validarCampos($data, ['Nombre', 'Descripcion'])) return;

        $resultado = $this->categoria->create($data['Nombre'], $data['Descripcion']);
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear la categoría"]);
            return;
        }
        echo json_encode($resultado);
    }

    public function update($id)
    {
        if (!$this->validarId($id)) return;

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->validarCampos($data, ['Nombre', 'Descripcion'])) return;

        $resultado = $this->categoria->update($id, $data['Nombre'], $data['Descripcion']);

        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la categoría"]);
            return;
        }

        echo json_encode($resultado);
    }

    public function delete($id)
    {
        if (!$this->validarId($id)) return;

        $resultado = $this->categoria->delete($id);
        if (!$resultado) {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la categoría"]);
            return;
        }

        echo json_encode(["mensaje" => "Categoría eliminada correctamente"]);
    }

    public function getCategorias()
    {
        echo json_encode($this->categoria->getCategorias());
    }

    public function getCategoriaById($id)
    {
        if (!$this->validarId($id)) return;

        $categoria = $this->categoria->getCategoriaById($id);
        if (!$categoria) {
            http_response_code(404);
            echo json_encode(["error" => "Categoría no encontrada"]);
            return;
        }
        echo json_encode($categoria);
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