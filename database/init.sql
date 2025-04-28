-- Creación de la base de datos con UTF-8
CREATE DATABASE IF NOT EXISTS iga_cursos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iga_cursos;

-- Tabla de cursos con UTF-8
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    detalle TEXT NOT NULL,
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);

-- Tabla de clientes con UTF-8
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);

-- Tabla de compras con UTF-8
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    curso_id INT NOT NULL,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'completada', 'cancelada') DEFAULT 'completada',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);

-- Tabla de usuarios administradores con UTF-8
CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);

-- Insertar algunos datos de prueba
-- Cursos
INSERT INTO cursos (nombre, descripcion, precio, detalle, imagen) VALUES 
('Cocina Italiana Básica', 'Aprende los fundamentos de la cocina italiana', 299.99, 'En este curso aprenderás a preparar pasta fresca, salsas clásicas y postres italianos tradicionales. Incluye 10 clases en video y material descargable.', 'italiana.jpg'),
('Repostería Francesa', 'Domina el arte de la pastelería francesa', 349.99, 'Curso completo de repostería francesa con técnicas profesionales. Aprenderás a hacer croissants, éclairs, macarons y más. 15 clases en video y recetario digital.', 'reposteria.jpg'),
('Cocina Asiática', 'Descubre los sabores de Asia', 249.99, 'Explora la diversidad de la cocina asiática con recetas de Japón, China, Tailandia y Vietnam. 12 clases en video con demostraciones paso a paso.', 'asiatica.jpg'),
('Técnicas de Cocina Profesional', 'Aprende como un chef profesional', 499.99, 'Curso avanzado con técnicas utilizadas en restaurantes de alto nivel. Incluye cortes, métodos de cocción, presentación de platos y más. 20 clases en video.', 'profesional.jpg');

-- Administrador por defecto
INSERT INTO administradores (nombre, email, password) VALUES 
('Admin IGA', 'admin@iga.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password
