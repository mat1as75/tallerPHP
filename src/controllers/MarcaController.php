<?php
include_once __DIR__ . '/../../src/repositories/MarcaRepository.php';

class MarcaController
{
    private $marca;

    public function __construct()
    {
        $this->marca = new MarcaRepository();
    }

    public function getMarcas()
    {
        $marcas = $this->marca->getMarcas();
        if (empty($marcas)) {
            http_response_code(404);
            echo json_encode(["mensaje" => "No se encontraron marcas"]);
            return;
        }
        echo json_encode($marcas);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $nombre = $data['Nombre'] ?? null;

        if (!$nombre) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Nombre de Marca es requerido"]);
            return;
        }

        echo json_encode($this->marca->create($nombre));
    }
}