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
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos;
    }

    // Obtener un pedido por ID
    public function getPedidoById($id)
    {
        $sql = "SELECT * FROM Pedido WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Obtener pedidos por cliente
    public function getPedidoByCliente($id_cliente)
    {
        $sql = "SELECT * FROM Pedido WHERE ID_Cliente = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();
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
            // Chequear existencia de DatosEnvio
            $stmtCheck = $this->conn->prepare("SELECT ID FROM DatosEnvio WHERE TelefonoCliente = ? AND DireccionCliente = ? AND DepartamentoCliente = ? AND CiudadCliente = ?");
            $stmtCheck->bind_param(
                "ssss",
                $datosEnvio['telefonoCliente'],
                $datosEnvio['direccionCliente'],
                $datosEnvio['departamentoCliente'],
                $datosEnvio['ciudadCliente']
            );
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();
            $row = $result->fetch_assoc();
            $id_datosEnvio = $row['ID'];
            $stmtCheck->close();

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
            return json_encode(['ID_Pedido' => $id_pedido]);

        } catch (Exception $e) {
            $this->conn->rollback(); // Revertir en caso de error
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    // Actualizar el estado de un pedido
    public function updateStatus($id, $estado)
    {
        $stmt = $this->conn->prepare("UPDATE Pedido SET Estado = ? WHERE ID = ?");
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();

        if ($stmt->error)
            return json_encode(['error' => 'Error al actualizar el estado del pedido: ' . $stmt->error]);
        $stmt->close();
        return json_encode(['mensaje' => 'Estado del pedido actualizado']);
    }

    // Cancelar un pedido
    public function cancel($id)
    {
        if ($this->checkPedidoEntregado($id)) {
            return json_encode(['error' => 'No se puede cancelar un pedido ya entregado']);
        }

        $stmt = $this->conn->prepare("UPDATE Pedido SET Estado = 'cancelado' WHERE ID = ?");
        $stmt->execute([$id]);
        if ($stmt->error) {
            return json_encode(['error' => 'Error al cancelar el pedido: ' . $stmt->error]);
        }
        return json_encode(['mensaje' => 'Pedido cancelado']);
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
            return json_encode(['error' => 'Datos de envío no proporcionados']);
        }

        // Verifico existencia de Cliente
        if ($this->checkClientePedido($id_cliente) === false) {
            return json_encode(['error' => 'Cliente no encontrado']);
        }

        // Verifico existencia de Productos
        $productos = $data['productos'] ?? [];
        if (!empty($productos)) {
            foreach ($productos as $producto) {
                if (!$this->checkPedidoProducto($producto['id_producto'])) {
                    return json_encode(['error' => 'Producto inválido en el pedido']);
                }
            }
        } else {
            return json_encode(['error' => 'No se han agregado productos al pedido']);
        }
    }

    public function sendEmailOrderConfirmation($data)
    {
        $mailHelper = new MailService();

        $email = $data['Email'];
        $nombreCliente = $data['Nombre'];
        $idPedido = $data['ID_Pedido'];
        $montoTotal = $data['Total'];
        $metodoPago = $data['MetodoPago'];
        $productos = $data['productos'];
        $fechaPedido = $data['FechaPedido'];

        switch ($metodoPago) {
            case 'creditCard':
                $metodoPago = "Tarjeta de Crédito";
                break;
            case 'bankTransfer':
                $metodoPago = "Transferencia Bancaria";
                break;
            case 'cashPayment':
                $metodoPago = "Depósito en Redes de Cobranza";
                break;
        }

        $listaProductos = "<ul>";
        foreach ($productos as $producto) {
            $nombre = htmlspecialchars($producto['Nombre']);
            $cantidad = intval($producto['Cantidad']);
            $listaProductos .= "<li>{$nombre} | {$cantidad} unidad" . ($cantidad > 1 ? "es" : "") . "</li>";
        }
        $listaProductos .= "</ul>";

        $fechaString = $this->dateString($fechaPedido);

        $msgAsunto = '¡Gracias por tu compra! Confirmación del Pedido #' . $idPedido;
        $msgCuerpo = "
        <html>
        <head>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    color: #333;
                    background-color: #f6f6f6;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 30px auto;
                    background-color: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    padding: 30px;
                }
                h2 {
                    color: #15297C;
                }
                p {
                    line-height: 1.6;
                }
                .order-summary {
                    background-color: #f0f4f8;
                    border-radius: 6px;
                    padding: 15px;
                    margin-top: 20px;
                    margin-bottom: 20px;
                }
                .order-summary ul {
                    padding-left: 20px;
                    margin: 0;
                }
                .order-summary li {
                    margin-bottom: 8px;
                }
                .footer {
                    font-size: 12px;
                    color: #888;
                    border-top: 1px solid #ddd;
                    margin-top: 30px;
                    padding-top: 10px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>¡Gracias por tu compra, {$nombreCliente}!</h2>
                <p>Te confirmamos que hemos recibido tu pedido <strong>#{$idPedido}</strong> realizado el <strong>{$fechaString}</strong>.</p>
                <p>Estamos preparando todo para enviártelo lo antes posible.</p>

                <div class='order-summary'>
                    <h3>Resumen del pedido:</h3>
                    {$listaProductos}
                    <p><strong>Total:</strong> {$montoTotal} USD</p>
                    <p><strong>Método de pago:</strong> {$metodoPago}</p>
                </div>

                <p>📦 Te notificaremos nuevamente cuando tu pedido esté en camino.</p>
                <p>Si tenés alguna pregunta, podés responder este correo o visitar nuestro centro de ayuda.</p>

                <p>¡Gracias por confiar en nosotros!</p>
                <p>Saludos,<br><strong>El equipo de MNJTecno.com</strong></p>

                <div class='footer'>
                    Este mensaje es una confirmación automática. Por favor, no respondas si no es necesario.
                </div>
            </div>
        </body>
        </html>
        ";

        return $mailHelper->EnvioMail($email, $nombre, null, $msgAsunto, $msgCuerpo, true);
    }

    public function getNamesMonth(int $monthNumber): string
    {
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return $months[$monthNumber] ?? 'Mes invalido';
    }

    public function dateString(string $fecha): string
    {
        $dt = new DateTime($fecha);
        $dia = $dt->format('j');
        $mes = $this->getNamesMonth((int) $dt->format('n'));
        $anio = $dt->format('Y');

        return "$dia de $mes de $anio";
    }
}
?>