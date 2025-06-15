<?php
include_once __DIR__ . '/../config/database.php';

class CarritoRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener el carrito de un cliente   
    public function getCarrito($ID_Cliente)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM Carrito WHERE ID_Cliente = ?");
            $stmt->bind_param("i", $ID_Cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $carrito = [];

            while ($row = $result->fetch_assoc()) {
                $carrito[] = $row;
            }

            $stmt->close();
            return $carrito;
        } catch (Exception $e) {
            return [];
        }
    }

    // Obtener el carrito detallado de un cliente   
    public function getCarritoDetallado($ID_Cliente)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM Carrito WHERE ID_Cliente = ?");
            $stmt->bind_param("i", $ID_Cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $carrito = [];

            while ($row = $result->fetch_assoc()) {
                $ID_Producto = $row['ID_Producto'];
                $Cantidad = $row['Cantidad'];

                // Obtener datos del producto
                $stmtProducto = $this->conn->prepare("SELECT ID AS ID_Producto, Nombre, Precio, Descripcion, URL_Imagen FROM Producto WHERE ID = ?");
                $stmtProducto->bind_param("i", $ID_Producto);
                $stmtProducto->execute();
                $resProducto = $stmtProducto->get_result();
                $producto = $resProducto->fetch_assoc();
                $stmtProducto->close();

                if ($producto) {
                    // Añadir la cantidad al resultado del producto
                    $producto['Cantidad'] = $Cantidad;
                    $carrito[] = $producto;
                }
            }

            $stmt->close();
            return $carrito;
        } catch (Exception $e) {
            return [];
        }
    }

    // Agregar un producto al carrito 
    public function addProducto($ID_Cliente, $ID_Producto, $Cantidad)
    {
        try {
            // Comprobar si el producto ya existe en el carrito
            $stmt = $this->conn->prepare("SELECT Cantidad FROM Carrito WHERE ID_Cliente = ? AND ID_Producto = ?");
            $stmt->bind_param("ii", $ID_Cliente, $ID_Producto);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Actualizar la cantidad si ya existe
                $row = $result->fetch_all(MYSQLI_ASSOC);
                $nuevaCantidad = $row[0]['Cantidad'] + $Cantidad;
                return $this->updateCantidad($ID_Cliente, $ID_Producto, $nuevaCantidad);
            } else {
                // Agregar nuevo 
                $stmtInsert = $this->conn->prepare("INSERT INTO Carrito (ID_Cliente, ID_Producto, Cantidad) VALUES (?, ?, ?)");
                $stmtInsert->bind_param("iii", $ID_Cliente, $ID_Producto, $Cantidad);
                $stmtInsert->execute();
                if ($stmtInsert->error) {
                    return false;
                }
                $stmtInsert->close();
                return true;
            }
        } catch (Exception $e) {
            echo json_encode($e);
            return false;
        }
    }

    // Eliminar un producto del carrito
    public function removeProducto($ID_Cliente, $ID_Producto)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM Carrito WHERE ID_Cliente = ? AND ID_Producto = ?");
            $stmt->bind_param("ii", $ID_Cliente, $ID_Producto);
            $stmt->execute();
            if ($stmt->error) {
                return false;
            }
            $stmt->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Vaciar el carrito 
    public function clearCarrito($ID_Cliente)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM Carrito WHERE ID_Cliente = ?");
            $stmt->bind_param("i", $ID_Cliente);
            $stmt->execute();
            if ($stmt->error) {
                return false;
            }
            $stmt->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Actualizar la cantidad de un producto en el carrito
    public function updateCantidad($ID_Cliente, $ID_Producto, $nuevaCantidad)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE Carrito SET Cantidad = ? WHERE ID_Cliente = ? AND ID_Producto = ?");
            $stmt->bind_param("iii", $nuevaCantidad, $ID_Cliente, $ID_Producto);
            $stmt->execute();
            if ($stmt->error) {
                return false;
            }
            $stmt->close();
            //echo "aca";
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>