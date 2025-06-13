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

    public function getProductos()
    {
        $sql = "SELECT * FROM Producto";
        $result = mysqli_query($this->conn, $sql);
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }

    public function getProductoById($id)
    {
        $sql = "SELECT * FROM Producto WHERE id = $id";
        $result = mysqli_query($this->conn, $sql);
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos;
    }

    public function create($data)
    {
        $sql = "INSERT INTO producto (
            nombre, 
            descripcion, 
            precio, 
            stock, 
            id_categoria, 
            marca, 
            url_imagen
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['nombre'],
                $data['descripcion'],
                $data['precio'],
                $data['stock'],
                $data['id_categoria'],
                $data['marca'],
                $data['url_imagen']
            ]
        );
        return ['mensaje' => 'Producto creado'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE producto SET 
            nombre = ?, 
            descripcion = ?, 
            precio = ?, 
            stock = ?, 
            marca = ?, 
            url_imagen = ? 
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['nombre'],
                $data['descripcion'],
                $data['precio'],
                $data['stock'],
                $data['marca'],
                $data['url_imagen'],
                $id
            ]
        );
        return ['mensaje' => 'Producto actualizado'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM producto WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Producto eliminado'];
    }
}
?>