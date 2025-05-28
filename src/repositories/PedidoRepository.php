<?php
include_once __DIR__ . '/../config/database.php';

class PedidoRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener todos los pedidos
    public function getPedidos()
    {
        $sql = "SELECT * FROM Pedido";
        $result = mysqli_query($this->conn, $sql);
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos;
    }

    // Obtener un pedido por ID
    public function getPedidoById($id)
    {
        $sql = "SELECT * FROM Pedido WHERE ID = $id";
        $result = mysqli_query($this->conn, $sql);
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos;
    }

    // Obtener pedidos por cliente
    public function getPedidoByCliente($id_cliente)
    {
        $sql = "SELECT * FROM Pedido WHERE ID_Cliente = $id_cliente";
        $result = mysqli_query($this->conn, $sql);
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos;
    }

    // Crear un nuevo pedido
    public function create($id_cliente, $id_datosEnvio, $total, $estado)
    {
        if ($this->checkClientePedido($id_cliente) === false) {
            return ['error' => 'Cliente no encontrado'];
        }
        if ($this->checkDatosEnvioPedido($id_datosEnvio) === false) {
            return ['error' => 'Datos de envío no encontrados'];
        }


        $sql = "INSERT INTO Pedido (ID_Cliente, ID_DatosEnvio, Total, Estado) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente, $id_datosEnvio, $total, $estado);
        $stmt->execute();
        return ['mensaje' => 'Pedido creado'];
    }

    // Actualizar el estado de un pedido
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE Pedido SET Estado = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$status, $id]);
        return ['mensaje' => 'Estado del pedido actualizado'];
    }

    // Cancelar un pedido
    public function cancel($id)
    {
        if ($this->checkPedidoEntregado($id)) {
            return ['error' => 'No se puede cancelar un pedido ya entregado'];
        }

        $sql = "UPDATE Pedido SET Estado = 'cancelado' WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Pedido cancelado'];
    }

    // Checkear si el cliente existe
    public function checkClientePedido($id_cliente)
    {
        $sql = "SELECT ID FROM Cliente WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Checkear si los datos de envio existen
    public function checkDatosEnvioPedido($id_datosEnvio)
    {
        $sql = "SELECT ID FROM DatosEnvio WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_datosEnvio);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Checkear si el pedido ya fue entregado
    public function checkPedidoEntregado($id)
    {
        $sql = "SELECT Estado FROM Pedido WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $pedido = $result->fetch_assoc();
            return $pedido['Estado'] === 'entregado';
        }
        return false;
    }
}
?>