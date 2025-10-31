-- Base de datos completa y lista para usar (estructura + datos iniciales)
-- Ejecutar este archivo tal cual en HeidiSQL/MySQL (MariaDB compatible)

-- 0) Crear base de datos y seleccionar
CREATE DATABASE IF NOT EXISTS `u157683007_romcoffe`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `u157683007_romcoffe`;

-- 1) Tabla: usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_username` (`username`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  KEY `idx_usuarios_active` (`active`),
  KEY `idx_usuarios_role` (`role`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- 2) Tabla: pedidos (FK -> usuarios.id)
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id_pedido` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT UNSIGNED NOT NULL,
  `tipo_pedido` ENUM('local','retirar') NOT NULL,
  `productos` TEXT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `estado` ENUM('Pendiente','En preparación','Listo','Entregado') NOT NULL DEFAULT 'Pendiente',
  `fecha_pedido` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pedido`),
  KEY `idx_pedidos_usuario` (`id_usuario`),
  CONSTRAINT `fk_pedidos_usuarios`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- 3) Tabla: productos (unificada)
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `descripcion` TEXT NULL,
  `imagen` VARCHAR(500) NULL,
  `categoria` VARCHAR(100) NULL,
  `ingredientes` TEXT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `stock` INT NOT NULL DEFAULT 0,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  KEY `idx_productos_categoria` (`categoria`),
  KEY `idx_productos_activo` (`activo`),
  KEY `idx_productos_fecha_creacion` (`fecha_creacion`),
  KEY `idx_productos_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_productos_usuarios`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;

-- 4) Datos iniciales: usuario admin
-- Hash corresponde a 'admin123' (ejemplo). Puedes reemplazarlo por otro hash.
INSERT INTO `usuarios` (`username`, `email`, `password`, `role`, `active`, `created_at`)
VALUES (
  'admin',
  'admin@admin.com',
  '$2y$10$KQbP5iP7cRrU8wO0tqHj5u8q0y0wBq4wC4mYQ8P8m6bO1F0kY7dB2',
  'admin',
  1,
  NOW()
);

-- 5) Datos iniciales: productos de ejemplo (sin propietario)
INSERT INTO `productos` (`nombre`, `precio`, `descripcion`, `imagen`, `categoria`, `ingredientes`, `activo`, `stock`)
VALUES
('Café Latte', 3900, 'Espresso suave con leche vaporizada y una capa de espuma.', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop', 'cafe', 'Espresso, Leche, Espuma de leche', 1, 100),
('Cold Brew', 4500, 'Café infusionado en frío durante 16 horas. Refrescante y suave.', 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=1200&q=80&auto=format&fit=crop', 'bebidas-frias', 'Café molido, Agua filtrada, Hielo', 1, 100),
('Sandwich Club', 6900, 'Pan tostado con pollo, tocino, vegetales y salsa especial.', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Pan, Pollo, Tocino, Lechuga, Tomate, Salsa', 1, 50),
('Ensalada César', 5500, 'Clásica ensalada con aderezo césar y crutones.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Lechuga, Pollo, Parmesano, Crutones, Aderezo césar', 1, 50),
('Té Verde', 2000, 'Refrescante y antioxidante', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=1200&q=80&auto=format&fit=crop', 'tes', 'Té verde, Agua caliente', 1, 200),
('Croissant', 3000, 'Panadería francesa tradicional', 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=1200&q=80&auto=format&fit=crop', 'desayuno', 'Harina, Mantequilla, Levadura, Sal', 1, 200);

-- 6) Datos iniciales: pedido de ejemplo (asigna al admin id=1)
INSERT INTO `pedidos` (`id_usuario`, `tipo_pedido`, `productos`, `total`, `estado`, `fecha_pedido`)
VALUES (
  1,
  'local',
  '[{"id":1,"cantidad":2},{"id":3,"cantidad":1}]',
  14700.00,
  'Pendiente',
  NOW()
);

-- 7) Consultas de verificación (solo lectura)
-- Conteos rápidos
SELECT 'usuarios' AS tabla, COUNT(*) AS total FROM usuarios
UNION ALL
SELECT 'productos' AS tabla, COUNT(*) AS total FROM productos
UNION ALL
SELECT 'pedidos'  AS tabla, COUNT(*) AS total FROM pedidos;

-- Revisar nulos importantes (email no debe ser NULL por diseño)
SELECT id, username, email FROM usuarios WHERE email IS NULL OR email = '';

-- Listar productos activos y recientes
SELECT id, nombre, precio, categoria, activo, fecha_creacion
FROM productos
WHERE activo = 1
ORDER BY fecha_creacion DESC
LIMIT 20;

-- Validar integridad de FK en pedidos
SELECT p.id_pedido, p.id_usuario
FROM pedidos p
LEFT JOIN usuarios u ON u.id = p.id_usuario
WHERE u.id IS NULL; -- Debe retornar 0 filas




