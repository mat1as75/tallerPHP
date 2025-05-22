<?php
require_once './config/database.php';

class Carrito
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getCarritos()
    {
        $sql = "SELECT * FROM carrito";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCarritoById($id)
    {
        $sql = "SELECT * FROM carrito WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO carrito (
            id_usuario, 
            id_producto, 
            cantidad 
            ) VALUES (?, ?, ?)
        ";
        $stmt = $this->conn->prepare(query: $sql);
        $stmt->execute(
            [
                $data['id_usuario'],
                $data['id_producto'],
                $data['cantidad']
            ]
        );
        return ['mensaje' => 'Carrito creado'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE carrito SET 
            id_usuario = ?, 
            id_producto = ?, 
            cantidad = ?, 
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(
            [
                $data['id_usuario'],
                $data['id_producto'],
                $data['cantidad'],
                $id
            ]
        );
        return ['mensaje' => 'Carrito actualizado'];
    }

    public function delete($id)
    {
        $sql = "DELETE FROM carrito WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return ['mensaje' => 'Carrito eliminado'];
    }
}

?>