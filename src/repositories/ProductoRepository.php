<?php
include_once __DIR__ . '/../config/database.php';

class ProductoRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($nombre, $descripcion, $precio, $stock, $id_marca, $url_imagen, $id_categoria){
        try {
            $sql = "INSERT INTO Producto (
                nombre, descripcion, precio, stock, id_marca, url_imagen, id_categoria
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conn->error);
            }

            $stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $stock, $id_marca, $url_imagen, $id_categoria);

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            return [
                'mensaje' => 'Producto creado',
                'id' => $this->conn->insert_id
            ];
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            return false;
        }
    }

    public function update($id, $nombre, $descripcion, $precio, $stock, $id_marca, $url_imagen, $id_categoria)
    {
        $sql = "UPDATE Producto SET 
            nombre = ?, descripcion = ?, precio = ?, stock = ?, 
            id_marca = ?, url_imagen = ?, id_categoria = ?
            WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdissii", $nombre, $descripcion, $precio, $stock, $id_marca, $url_imagen, $id_categoria, $id);

        if ($stmt->execute()) {
            return ['mensaje' => 'Producto actualizado'];
        }
        return false;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Producto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ['mensaje' => 'Producto eliminado'];
        }
        return false;
    }

    public function getProductos(array $filtros = [])
    {
        $sql = "SELECT * FROM Producto";
        $params = [];
        $types = [];
        $condiciones = [];

        if (isset($filtros['Marca'])) {
            $condiciones[] = "Marca = ?";
            $params[] = $filtros['Marca'];
            $types[] = "s";
        }

        if (isset($filtros['ID_Categoria'])) {
            $condiciones[] = "ID_Categoria = ?";
            $params[] = $filtros['ID_Categoria'];
            $types[] = "i";
        }

        if (isset($filtros['PrecioMin'])) {
            $condiciones[] = "Precio >= ?";
            $params[] = $filtros['PrecioMin'];
            $types[] = "d";
        }

        if (isset($filtros['PrecioMax'])) {
            $condiciones[] = "Precio <= ?";
            $params[] = $filtros['PrecioMax'];
            $types[] = "d";
        }

        if (isset($filtros['Nombre'])) {
            $condiciones[] = "Nombre LIKE ?";
            $params[] = "%" . $filtros['Nombre'] . "%";
            $types[] = "s";
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param(implode("", $types), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        return $productos;
    }


    public function getProductoById($id)
    {
        $sql = "SELECT * FROM Producto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getProductosByCategoria($id_categoria)
    {
        $sql = "SELECT * FROM Producto WHERE id_categoria = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }

    public function getProductosByMarca($marca)
    {
        $sql = "SELECT * FROM Producto WHERE id_marca = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $marca);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }


    public function updateStock($id, $nuevo_stock)
    {
        $sql = "UPDATE Producto SET stock = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_stock, $id);

        if ($stmt->execute()) {
            return ['mensaje' => 'Stock actualizado'];
        }
        return false;
    }

}
?>