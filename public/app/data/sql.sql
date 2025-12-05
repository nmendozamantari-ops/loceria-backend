-- Crear base de datos
CREATE DATABASE loceria_melchorita;
USE loceria_melchorita;

-- Tabla usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

-- Tabla productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(255),
    id_categoria INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- Tabla pedidos
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre_cliente VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'procesando', 'completado') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES users(id)
);

-- DATOS DE EJEMPLO - USUARIOS
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@melchorita.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: "password"
('cliente', 'cliente@melchorita.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'); -- password: "password"

-- DATOS DE EJEMPLO - CATEGORÍAS
INSERT INTO categorias (nombre, descripcion) VALUES
('Vajillas', 'Juegos completos de porcelana fina'),
('Tazas y Mugs', 'Tazas para café, té y regalos'),
('Adornos', 'Figuras decorativas religiosas'),
('Jarras', 'Para servir con estilo');

-- DATOS DE EJEMPLO - PRODUCTOS
INSERT INTO productos (nombre, descripcion, precio, stock, imagen, id_categoria) VALUES
('Juego de Té Floral 6 personas', 'Porcelana fina con diseño floral rosa', 189.90, 12, 'te-floral.jpg', 1),
('Taza "Con Amor" Rosa', 'Taza cerámica con mensaje romántico', 29.90, 25, 'taza-amor.jpg', 2),
('Ángel de la Guarda 25cm', 'Figura religiosa en cerámica blanca', 85.00, 8, 'angel-guarda.jpg', 3),
('Jarra Cerámica 2L', 'Jarra elegante para agua fresca', 79.90, 15, 'jarra-ceramica.jpg', 4),
('Set 6 Tazas Café', 'Tazas de porcelana para desayuno', 129.90, 10, 'set-tazas.jpg', 2);