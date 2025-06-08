<?php
include_once __DIR__ . '/../../src/repositories/DatosEnvioRepository.php';

class DatosEnvioController
{
    private $datosEnvio;

    public function __construct()
    {
        $this->datosEnvio = new DatosEnvioRepository();
    }

    // public function getDatosEnvio()
    // {
    //     echo json_encode($this->datosEnvio->getDatosEnvio());
    // }

    // public function getDatosEnvioById($id)
    // {
    //     echo json_encode($this->datosEnvio->getDatosEnvioById($id));
    // }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $telefono = $data['Telefono'] ?? null;
        $direccion = $data['Direccion'] ?? null;
        $departamento = $data['Departamento'] ?? null;
        $ciudad = $data['Ciudad'] ?? null;

        if (!$telefono || !$direccion || !$departamento || !$ciudad) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Todos los campos son requeridos"]);
            return;
        }

        echo json_encode($this->datosEnvio->create($telefono, $direccion, $departamento, $ciudad));
    }
}

?>