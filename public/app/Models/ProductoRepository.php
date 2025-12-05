<?php
require_once __DIR__ . '/../../core/Database.php';

class ProductoRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM producto ORDER BY id_producto DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, id_categoria, imagen, estado) 
                VALUES (:nombre, :descripcion, :precio, :stock, :id_categoria, :imagen, :estado)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'id_categoria' => $data['id_categoria'],
            'imagen' => $data['imagen'],
            'estado' => $data['estado']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE producto SET 
                nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                stock = :stock,
                id_categoria = :id_categoria,
                imagen = :imagen,
                estado = :estado
                WHERE id_producto = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'id_categoria' => $data['id_categoria'],
            'imagen' => $data['imagen'],
            'estado' => $data['estado'],
            'id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
