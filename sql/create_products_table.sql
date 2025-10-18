-- Script para crear la tabla de productos
-- Ejecutar este script en HeidiSQL o tu cliente MySQL

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(500) DEFAULT NULL,
    categoria VARCHAR(50) NOT NULL,
    ingredientes TEXT DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo),
    INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos productos de ejemplo
INSERT INTO productos (nombre, precio, descripcion, imagen, categoria, ingredientes) VALUES
('Café Latte', 3900, 'Espresso suave con leche vaporizada y una capa de espuma.', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop', 'cafe', 'Espresso, Leche, Espuma de leche'),
('Cold Brew', 4500, 'Café infusionado en frío durante 16 horas. Refrescante y suave.', 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=1200&q=80&auto=format&fit=crop', 'bebidas-frias', 'Café molido, Agua filtrada, Hielo'),
('Sandwich Club', 6900, 'Pan tostado con pollo, tocino, vegetales y salsa especial.', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Pan, Pollo, Tocino, Lechuga, Tomate, Salsa'),
('Ensalada César', 5500, 'Clásica ensalada con aderezo césar y crutones.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Lechuga, Pollo, Parmesano, Crutones, Aderezo césar'),
('Té Verde', 2000, 'Refrescante y antioxidante', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=1200&q=80&auto=format&fit=crop', 'tes', 'Té verde, Agua caliente'),
('Croissant', 3000, 'Panadería francesa tradicional', 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=1200&q=80&auto=format&fit=crop', 'desayuno', 'Harina, Mantequilla, Levadura, Sal');
