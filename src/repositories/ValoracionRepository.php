<?php
include_once '../models/Valoracion.php';

class ValoracionController
{
    private $valoracion;

    public function __construct()
    {
        $this->valoracion = new Valoracion();
    }

    public function getValoraciones()
    {
        echo json_encode($this->valoracion->getValoraciones());
    }

    public function getValoracionesByIdCliente($id)
    {
        echo json_encode($this->valoracion->getValoracionesByIdCliente($id));
    }

    public function getValoracionesByIdProducto($id)
    {
        echo json_encode($this->valoracion->getValoracionesByIdProducto($id));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->valoracion->create($data));
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->valoracion->update($id, $data));
    }

    public function delete($id)
    {
        echo json_encode($this->valoracion->delete($id));
    }
}
?>