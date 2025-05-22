<?php
require_once './models/Categoria.php';

class CategoriaController
{
    private $categoria;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function getCategorias()
    {
        echo json_encode($this->categoria->getUsuarios());
    }

    public function getCategoriaById($id)
    {
        echo json_encode($this->categoria->getCategoriaById($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->categoria->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->categoria->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->categoria->delete($id));
    }
}
?>