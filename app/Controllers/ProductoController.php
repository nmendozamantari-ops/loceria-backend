<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/ProductoRepository.php';

class ProductoController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new ProductoRepository();
    }

    public function getAll($data) {
        $productos = $this->repo->getAll();
        $this->jsonResponse($productos);
    }

    public function getById($data) {
        if (!isset($data['id_producto'])) {
            return $this->errorResponse('Falta id_producto', 400);
        }
        $producto = $this->repo->getById($data['id_producto']);
        if ($producto) {
            $this->jsonResponse($producto);
        } else {
            $this->errorResponse('Producto no encontrado', 404);
        }
    }
}