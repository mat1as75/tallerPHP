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

    public function getDatosEnvio()
    {
        $sql = "SELECT * FROM DatosEnvio";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $datosEnvio = [];
        while ($row = $result->fetch_assoc()) {
            $datosEnvio[] = $row;
        }
        return $datosEnvio;
    }

    public function getDatosEnvioById($id)
    {
        $sql = "SELECT * FROM DatosEnvio WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

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