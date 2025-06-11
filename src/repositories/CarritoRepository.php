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

    // Obtener el carrito de un usuario   
    public function getCarrito($id_usuario)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM carrito WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
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
    
     // Agregar un producto al carrito 
    public function addProducto($id_usuario, $id_producto, $cantidad)
    {
        try {
            // Comprobar si el producto ya existe en el carrito
            $stmt = $this->conn->prepare("SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
            $stmt->bind_param("ii", $id_usuario, $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Actualizar la cantidad si ya existe
                $row = $result->fetch_assoc();
                $nuevaCantidad = $row['cantidad'] + $cantidad;
                return $this->updateCantidad($id_usuario, $id_producto, $nuevaCantidad);
            } else {
                // Agregar nuevo 
                $stmtInsert = $this->conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
                $stmtInsert->bind_param("iii", $id_usuario, $id_producto, $cantidad);
                $stmtInsert->execute();
                if ($stmtInsert->error) {
                    return false;
                }
                $stmtInsert->close();
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
  
     // Eliminar un producto del carrito
    public function removeProducto($id_usuario, $id_producto)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
            $stmt->bind_param("ii", $id_usuario, $id_producto);
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
    public function clearCarrito($id_usuario)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
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
    public function updateCantidad($id_usuario, $id_producto, $nuevaCantidad)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
            $stmt->bind_param("iii", $nuevaCantidad, $id_usuario, $id_producto);
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
}    
?>