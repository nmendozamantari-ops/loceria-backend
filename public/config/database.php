<?php
class Database {
    private static $instance = null;
    private $conn;

    // CONFIGURACIÓN POSTGRES
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "loceria_melchorita";
    private $user = "melchorita";
    private $password = "123456*"; // cámbialo por tu contraseña real

    private function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};";
            $this->conn = new PDO($dsn, $this->user, $this->password);

            // Configuración extra
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("❌ Error de conexión PostgreSQL: " . $e->getMessage());
        }
    }

    // Singleton
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
