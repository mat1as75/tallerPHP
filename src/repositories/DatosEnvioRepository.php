<?php
include_once __DIR__ . '/../config/database.php';

class DatosEnvioRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // public function getDatosEnvio()
    // {
    //     $query = "SELECT * FROM datos_envio";
    //     return $this->db->query($query);
    // }

    // public function getDatosEnvioById($id)
    // {
    //     $query = "SELECT * FROM datos_envio WHERE ID_DatosEnvio = :id";
    //     return $this->db->query($query, ['id' => $id]);
    // }

    public function create($telefono, $direccion, $departamento, $ciudad)
    {
        $stmt = $this->conn->prepare("INSERT INTO DatosEnvio (TelefonoCliente, DireccionCliente, DepartamentoCliente, CiudadCliente) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $telefono, $direccion, $departamento, $ciudad);
        $stmt->execute();

        if ($stmt->error)
            return ['error' => 'Error al crear datos de envío: ' . $stmt->error];
        $stmt->close();
        return ['mensaje' => 'Datos de envío creados correctamente'];
    }
}
?>