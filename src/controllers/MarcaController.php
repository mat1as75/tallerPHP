<?php
include_once __DIR__ . '/../../src/repositories/MarcaRepository.php';

class MarcaController
{
    private $marca;

    public function __construct()
    {
        $this->marca = new MarcaRepository();
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