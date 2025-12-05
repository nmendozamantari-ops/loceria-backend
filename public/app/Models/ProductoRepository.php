<?php
require_once __DIR__ . '/../../core/Database.php';

class ProductoRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "
            SELECT 
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.precio,
                p.stock,
                p.imagen,
                p.id_categoria,
                c.nombre AS categoria
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            ORDER BY p.id_producto DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "
            SELECT 
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.precio,
                p.stock,
                p.imagen,
                p.id_categoria,
                c.nombre AS categoria
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
