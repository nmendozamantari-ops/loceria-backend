<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {

        // Cargar configuración correcta del hosting
        $config = require __DIR__ . '/../config/database.php';

        // IMPORTANTE: InfinityFree requiere puerto 3306
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']};port=3306";

        try {
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            // Mostrar error real en InfinityFree
            die("ERROR DB: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Evitar clonación
    private function __clone() {}

    // Evitar deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}
