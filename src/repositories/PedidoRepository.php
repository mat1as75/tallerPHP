<?php
include_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/ProductoRepository.php';
require_once __DIR__ . '/DatosEnvioRepository.php';

class PedidoRepository
{
    private $conn;

    private $productoRepository;

    private $productoPedidoRepository;

    private $datosEnvioRepository;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
        $this->productoRepository = new ProductoRepository();
        $this->productoPedidoRepository = new ProductoPedidoRepository();
        $this->datosEnvioRepository = new DatosEnvioRepository();
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
    public function create($data)
    {
        $res = $this->verifyData($data);
        if (isset($res['error'])) {
            return $res;
        }

        // Extraer datos principales
        $id_cliente = $data['ID_Cliente'];
        $total = $data['Total'];
        $estado = $data['Estado'];
        $productos = $data['productos'];
        $datosEnvio = $data['datosEnvio'];

        $this->conn->begin_transaction();

        try {
            // Insertar en DatosEnvio
            $stmtEnvio = $this->conn->prepare("INSERT INTO DatosEnvio (TelefonoCliente, DireccionCliente, DepartamentoCliente, CiudadCliente) VALUES (?, ?, ?, ?)");
            $stmtEnvio->bind_param("ssss", $datosEnvio['telefonoCliente'], $datosEnvio['direccionCliente'], $datosEnvio['departamentoCliente'], $datosEnvio['ciudadCliente']);
            $stmtEnvio->execute();
            if ($stmtEnvio->error) {
                throw new Exception('Error al crear datos de envío: ' . $stmtEnvio->error);
            }
            $id_datosEnvio = $this->conn->insert_id; // Obtener el ID del último registro insertado
            $stmtEnvio->close();

            // Insertar en Pedido
            $stmtPedido = $this->conn->prepare("INSERT INTO Pedido (ID_Cliente, ID_DatosEnvio, Total, Estado) VALUES (?, ?, ?, ?)");
            $stmtPedido->bind_param("iids", $id_cliente, $id_datosEnvio, $total, $estado);
            $stmtPedido->execute();
            if ($stmtPedido->error) {
                throw new Exception('Error al crear el pedido: ' . $stmtPedido->error);
            }
            $id_pedido = $this->conn->insert_id; // Obtener el ID del último pedido insertado
            $stmtPedido->close();

            // Insertar productos en ProductoPedido
            $stmtProd = $this->conn->prepare("INSERT INTO Producto_Pedido (ID_Pedido, ID_Producto, Cantidad, Precio) VALUES (?, ?, ?, ?)");
            foreach ($productos as $prod) {
                $id_producto = $prod['id_producto'];
                $cantidad = $prod['cantidad'];
                $precio = $prod['precio'];

                $stmtProd->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio);
                $stmtProd->execute();

                if ($stmtProd->error) {
                    throw new Exception('Error al agregar producto al pedido: ' . $stmtProd->error);
                }
            }
            $stmtProd->close();

            // Commit transaction
            $this->conn->commit();
            return ['mensaje' => 'Pedido creado correctamente', 'ID_Pedido' => $id_pedido];

        } catch (Exception $e) {
            $this->conn->rollback(); // Revertir en caso de error
            return ['error' => $e->getMessage()];
        }
    }

    // Actualizar el estado de un pedido
    public function updateStatus($id, $estado)
    {
        $stmt = $this->conn->prepare("UPDATE Pedido SET Estado = ? WHERE ID = ?");
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();

        if ($stmt->error)
            return ['error' => 'Error al actualizar el estado del pedido: ' . $stmt->error];
        $stmt->close();
        return ['mensaje' => 'Estado del pedido actualizado'];
    }

    // Cancelar un pedido
    public function cancel($id)
    {
        if ($this->checkPedidoEntregado($id)) {
            return ['error' => 'No se puede cancelar un pedido ya entregado'];
        }

        $stmt = $this->conn->prepare("UPDATE Pedido SET Estado = 'cancelado' WHERE ID = ?");
        $stmt->execute([$id]);
        if ($stmt->error) {
            return ['error' => 'Error al cancelar el pedido: ' . $stmt->error];
        }
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

    public function checkPedidoProducto($id_producto)
    {
        return $this->productoRepository->getProductoById($id_producto) ? true : false;
    }

    public function verifyData($data)
    {
        // Obtención de datos del Pedido
        $id_cliente = $data['ID_Cliente'] ?? null;
        $total = $data['Total'] ?? 0;
        $estado = $data['Estado'] ?? 'pendiente';

        // Datos de envio
        $datosEnvio = $data['datosEnvio'] ?? null;
        if ($datosEnvio) {
            $telefono = $datosEnvio['telefonoCliente'] ?? null;
            $direccion = $datosEnvio['direccionCliente'] ?? null;
            $deparatamento = $datosEnvio['departamentoCliente'] ?? null;
            $ciudad = $datosEnvio['ciudadCliente'] ?? null;

            $this->datosEnvioRepository->create($telefono, $direccion, $deparatamento, $ciudad);
        } else {
            return ['error' => 'Datos de envío no proporcionados'];
        }

        // Verifico existencia de Cliente
        if ($this->checkClientePedido($id_cliente) === false) {
            return ['error' => 'Cliente no encontrado'];
        }

        // Verifico existencia de Productos
        $productos = $data['productos'] ?? [];
        if (!empty($productos)) {
            foreach ($productos as $producto) {
                if (!$this->checkPedidoProducto($producto['id_producto'])) {
                    return ['error' => 'Producto inválido en el pedido'];
                }
            }
        } else {
            return ['error' => 'No se han agregado productos al pedido'];
        }
    }
}
?>
